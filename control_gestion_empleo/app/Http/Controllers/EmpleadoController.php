<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log; 

class EmpleadoController extends Controller
{
    /**
     * Mostrar el dashboard individual del empleado
     */
    public function perfil($id)
    {
        // Verificar que el usuario esté autenticado
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Debes iniciar sesión primero.');
        }

        $user = Auth::user();

        // Buscar el empleado relacionado con el usuario autenticado
        $empleado = DB::table('tabla_empleados')
            ->where('id', $id)
            ->where('credencial_id', $user->id)
            ->first();
        
        if (!$empleado) {
            abort(403, 'No tienes permiso para acceder a esta página.');
        }

        // Obtener registro activo
        $registroActivo = DB::table('tabla_registros_tiempo')
            ->where('empleado_id', $empleado->id)
            ->whereNull('fin')
            ->whereDate('created_at', now()->today())
            ->first();

        // Obtener historial de hoy
        $historialHoy = DB::table('tabla_registros_tiempo')
            ->where('empleado_id', $empleado->id)
            ->whereDate('created_at', now()->today())
            ->orderBy('created_at', 'desc')
            ->get();

        // Obtener estadísticas del mes
        $estadisticasMes = $this->obtenerEstadisticasMes($empleado->id);

        // OBTENER NUEVOS DATOS PARA LAS TARJETAS
        $progresoSemanal = $this->obtenerProgresoSemanal($empleado->id);
        $logros = $this->obtenerLogros($empleado->id);

        // Pasar la API Key a la vista
        $googleMapsApiKey = env('GOOGLE_MAPS_API_KEY');

        // Pasar el empleado a la vista
        return view('empleado.sections.perfil', compact(
            'empleado', 
            'registroActivo', 
            'historialHoy',
            'estadisticasMes',
            'googleMapsApiKey', // Agregar esta variable
            'progresoSemanal', // NUEVO
            'logros' // NUEVO

        ));
    }
    
/**
 * Iniciar el tiempo de trabajo - VERSIÓN CORREGIDA
 */
public function startTiempo(Request $request, $id)
    {
        try {
            // Verificación básica
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No autenticado'
                ], 401);
            }

            // Verificar registro activo
            $registroActivo = DB::table('tabla_registros_tiempo')
                ->where('empleado_id', $id)
                ->whereNull('fin')
                ->whereDate('created_at', Carbon::today())
                ->first();

            if ($registroActivo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya tienes un tiempo activo'
                ]);
            }
            // Obtener datos de geolocalización del request
            $geolocalizacion = $this->procesarGeolocalizacion($request);

            // Insertar con campos NULLABLE
            $now = Carbon::now();
            
            $registroId = DB::table('tabla_registros_tiempo')->insertGetId([
                'empleado_id' => $id,
                'inicio' => $now,
                'estado' => 'activo',
                'latitud' => $geolocalizacion['latitud'],
                'longitud' => $geolocalizacion['longitud'],
                'direccion' => $geolocalizacion['direccion'],
                'ciudad' => $geolocalizacion['ciudad'],
                'pais' => $geolocalizacion['pais'],
                'precision_gps' => $geolocalizacion['precision'] ?? null,
                'dispositivo' => $request->userAgent(),
                'ip_address' => $request->ip(),
                'created_at' => $now,
                'updated_at' => $now
                // 'fin' y 'tiempo_total' serán NULL automáticamente
            ]);

            Log::info('Nuevo registro iniciado con geolocalización', [
                'empleado_id' => $id,
                'registro_id' => $registroId,
                'geolocalizacion' => $geolocalizacion
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tiempo iniciado correctamente',
                'registro_id' => $registroId,
                'geolocalizacion' => $geolocalizacion

            ]);

        } catch (\Exception $e) {
            Log::error('Error al iniciar tiempo con geolocalización: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

     /**
 * Pausar o reanudar el tiempo - VERSIÓN CORREGIDA
 */
public function pauseTiempo(Request $request, $id)
{
    try {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'No autenticado'
            ], 401);
        }

        // Obtener el registro activo
        $registro = DB::table('tabla_registros_tiempo')
            ->where('empleado_id', $id)
            ->whereNull('fin')
            ->whereDate('created_at', Carbon::today())
            ->first();
        
        if (!$registro) {
            return response()->json([
                'success' => false,
                'message' => 'No hay tiempo activo para pausar'
            ]);
        }
        
        $now = Carbon::now();
        $nuevoEstado = $registro->estado === 'activo' ? 'pausado' : 'activo';
        
        Log::info("=== PAUSA/REANUDAR ===");
        Log::info("Registro ID: {$registro->id}");
        Log::info("Estado actual: {$registro->estado}");
        Log::info("Nuevo estado: {$nuevoEstado}");

        if ($nuevoEstado === 'pausado') {
            // INICIAR PAUSA - solo registrar el inicio
            DB::table('tabla_registros_tiempo')
                ->where('id', $registro->id)
                ->update([
                    'estado' => 'pausado',
                    'pausa_inicio' => $now,
                    'pausa_fin' => null, // Asegurar que sea null
                    'updated_at' => $now
                ]);
                
            Log::info("PAUSA INICIADA - Hora: {$now}");
               
            $mensaje = 'Tiempo pausado';
        } else {
            // REANUDAR - calcular tiempo de pausa actual y acumular
            if (!$registro->pausa_inicio) {
                Log::error("No hay pausa_inicio para reanudar");
                return response()->json([
                    'success' => false,
                    'message' => 'No hay pausa iniciada para reanudar'
                ]);
            }
            
            $pausaInicio = Carbon::parse($registro->pausa_inicio);
            
            // Calcular tiempo de esta pausa específica
            $tiempoEstaPausa = $now->diffInSeconds($pausaInicio);
            
            // Tiempo de pausa total acumulado (anterior + actual)
            $tiempoPausaAnterior = $registro->tiempo_pausa_total ?? 0;
            $nuevoTiempoPausaTotal = $tiempoPausaAnterior + $tiempoEstaPausa;
            
            Log::info("=== REANUDAR PAUSA ===");
            Log::info("Pausa desde: {$registro->pausa_inicio}");
            Log::info("Reanudar en: {$now}");
            Log::info("Tiempo esta pausa: {$tiempoEstaPausa} segundos");
            Log::info("Tiempo pausa anterior: {$tiempoPausaAnterior} segundos");
            Log::info("Nuevo tiempo pausa total: {$nuevoTiempoPausaTotal} segundos");
            
            DB::table('tabla_registros_tiempo')
                ->where('id', $registro->id)
                ->update([
                    'estado' => 'activo',
                    'pausa_fin' => $now,
                    'tiempo_pausa_total' => $nuevoTiempoPausaTotal,
                    'updated_at' => $now
                ]);
                
            $mensaje = 'Tiempo reanudado';
        }
        
        return response()->json([
            'success' => true,
            'message' => $mensaje,
            'estado' => $nuevoEstado
        ]);
        
    } catch (\Exception $e) {
        Log::error('Error al pausar el tiempo: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error al pausar el tiempo: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * Detener el tiempo - USANDO EL MISMO CÁLCULO QUE EL MODAL
 */
public function stopTiempo(Request $request, $id)
{
    Log::info("=== INICIANDO STOP TIEMPO ===");
    Log::info("Empleado ID: {$id}");
    Log::info("Usuario autenticado: " . (Auth::check() ? Auth::id() : 'No autenticado'));

    try {
        if (!Auth::check()) {
            Log::warning('Usuario no autenticado en stopTiempo');
            return response()->json([
                'success' => false,
                'message' => 'No autenticado'
            ], 401);
        }

        $user = Auth::user();
        
        // VERIFICAR QUE EL EMPLEADO PERTENEZCA AL USUARIO
        $empleado = DB::table('tabla_empleados')
            ->where('id', $id)
            ->where('credencial_id', $user->id)
            ->first();
        
        if (!$empleado) {
            Log::warning('Empleado no encontrado o no autorizado', [
                'empleado_id' => $id,
                'user_id' => $user->id
            ]);
            return response()->json([
                'success' => false,
                'message' => 'No autorizado para este empleado'
            ], 403);
        }

        Log::info("Empleado validado: {$empleado->id}");

        $registro = DB::table('tabla_registros_tiempo')
            ->where('empleado_id', $id)
            ->whereNull('fin')
            ->whereDate('created_at', Carbon::today())
            ->first();
        
        Log::info("Registro encontrado para STOP:", [
            'registro_id' => $registro ? $registro->id : 'NULL',
            'estado' => $registro ? $registro->estado : 'NULL',
            'inicio' => $registro ? $registro->inicio : 'NULL'
        ]);

        if (!$registro) {
            Log::warning('No hay registro activo para detener');
            return response()->json([
                'success' => false,
                'message' => 'No hay tiempo activo para detener'
            ], 404);
        }
        
        Log::info("=== STOP TIEMPO - CÁLCULO CONSISTENTE ===");

        // Mismo cálculo que en el JavaScript
        $inicio = strtotime($registro->inicio);
        $fin = time();
        
        // 1. TIEMPO BRUTO
        $tiempoBrutoSegundos = $fin - $inicio;

        // 2. CALCULAR PAUSA MANUALMENTE
        $tiempoPausaTotal = 0;
        
        if ($registro->pausa_inicio && $registro->pausa_fin) {
            $pausaInicio = strtotime($registro->pausa_inicio);
            $pausaFin = strtotime($registro->pausa_fin);
            $tiempoPausaTotal = $pausaFin - $pausaInicio;
        } else if ($registro->pausa_inicio && !$registro->pausa_fin) {
            $pausaInicio = strtotime($registro->pausa_inicio);
            $tiempoPausaTotal = $fin - $pausaInicio;
        }

        // 3. TIEMPO NETO
        $tiempoNeto = max(0, $tiempoBrutoSegundos - $tiempoPausaTotal);

        Log::info("CÁLCULO FINAL: {$tiempoBrutoSegundos}s - {$tiempoPausaTotal}s = {$tiempoNeto}s");

        // Preparar datos de actualización
        $updateData = [
            'fin' => date('Y-m-d H:i:s', $fin),
            'estado' => 'completado',
            'tiempo_total' => $tiempoNeto,
            'tiempo_pausa_total' => $tiempoPausaTotal,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Si hay pausa activa, cerrarla
        if ($registro->pausa_inicio && !$registro->pausa_fin) {
            $updateData['pausa_fin'] = date('Y-m-d H:i:s', $fin);
        }

        Log::info("Actualizando registro con:", $updateData);

        // Actualizar registro
        $actualizado = DB::table('tabla_registros_tiempo')
            ->where('id', $registro->id)
            ->update($updateData);

        Log::info("Registro actualizado: " . ($actualizado ? 'SÍ' : 'NO'));

        if (!$actualizado) {
            throw new \Exception('No se pudo actualizar el registro en la base de datos');
        }
        
        Log::info("✅ STOP completado exitosamente");

        return response()->json([
            'success' => true,
            'message' => 'Tiempo detenido correctamente',
            'tiempo_total' => $tiempoNeto,
            'tiempo_formateado' => $this->formatearTiempo($tiempoNeto),
            'tiempo_pausa_formateado' => $this->formatearTiempo($tiempoPausaTotal),
            'detalles' => [
                'inicio' => $registro->inicio,
                'fin' => date('Y-m-d H:i:s', $fin),
                'duracion_bruta' => $this->formatearTiempo($tiempoBrutoSegundos),
                'pausa_total' => $this->formatearTiempo($tiempoPausaTotal),
                'duracion_neta' => $this->formatearTiempo($tiempoNeto)
            ]
        ]);
        
    } catch (\Exception $e) {
        Log::error('❌ Error crítico en stopTiempo: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        
        return response()->json([
            'success' => false,
            'message' => 'Error interno del servidor: ' . $e->getMessage()
        ], 500);
    }
}



/**
 * Obtener estado actual del tiempo - CÁLCULO CORREGIDO DE PAUSAS
 */
public function getEstado(Request $request, $id)
{
    try {
        if (!Auth::check()) {
            return response()->json([
                'activo' => false,
                'estado' => 'no_autenticado'
            ]);
        }

        $user = Auth::user();

        $empleado = DB::table('tabla_empleados')
            ->where('id', $id)
            ->where('credencial_id', $user->id)
            ->first();
        
        if (!$empleado) {
            return response()->json([
                'activo' => false,
                'estado' => 'no_autorizado'
            ]);
        }

        $registro = DB::table('tabla_registros_tiempo')
            ->where('empleado_id', $id)
            ->whereNull('fin')
            ->whereDate('created_at', Carbon::today())
            ->first();
        
        if (!$registro) {
            return response()->json([
                'activo' => false,
                'estado' => 'inactivo'
            ]);
        }

        Log::info("=== CÁLCULO CORREGIDO CON PAUSAS ===");

        // CÁLCULO MANUAL
        $inicio = strtotime($registro->inicio);
        $now = time();
        
        // 1. TIEMPO BRUTO
        $tiempoBrutoSegundos = $now - $inicio;

        // 2. TIEMPO PAUSA TOTAL - USAR DIRECTAMENTE EL VALOR DE LA BD
        $tiempoPausaTotal = max(0, intval($registro->tiempo_pausa_total ?? 0));
        
        Log::info("Tiempo pausa total desde BD: " . $tiempoPausaTotal);
        Log::info("Pausa inicio: " . ($registro->pausa_inicio ?: 'NULL'));
        Log::info("Pausa fin: " . ($registro->pausa_fin ?: 'NULL'));

        // 3. SI HAY PAUSA ACTIVA (pausa_inicio SIN pausa_fin), CALCULARLA
        if ($registro->estado === 'pausado' && $registro->pausa_inicio && !$registro->pausa_fin) {
            $pausaInicio = strtotime($registro->pausa_inicio);
            $pausaActual = $now - $pausaInicio;
            $tiempoPausaTotal += $pausaActual;
            Log::info("Pausa activa agregada: " . $pausaActual . " segundos");
        }

        Log::info("Tiempo pausa total final: " . $tiempoPausaTotal . " segundos");

        // 4. TIEMPO NETO
        $tiempoNeto = max(0, $tiempoBrutoSegundos - $tiempoPausaTotal);

        Log::info("RESULTADO: {$tiempoBrutoSegundos}s - {$tiempoPausaTotal}s = {$tiempoNeto}s");

        $debugData = [
            'tiempo_bruto_formateado' => $this->formatearTiempo($tiempoBrutoSegundos),
            'pausa_formateada' => $this->formatearTiempo($tiempoPausaTotal),
            'formula' => "({$this->formatearTiempo($tiempoBrutoSegundos)} bruto) - ({$this->formatearTiempo($tiempoPausaTotal)} pausa) = {$this->formatearTiempo($tiempoNeto)} neto",
            'tiempo_bruto_segundos' => $tiempoBrutoSegundos,
            'pausa_total_segundos' => $tiempoPausaTotal,
            'tiempo_neto_segundos' => $tiempoNeto,
            'pausa_inicio_bd' => $registro->pausa_inicio,
            'pausa_fin_bd' => $registro->pausa_fin,
            'tiempo_pausa_total_bd' => $registro->tiempo_pausa_total
        ];

        return response()->json([
            'activo' => true,
            'estado' => $registro->estado,
            'inicio' => $registro->inicio,
            'tiempo_transcurrido' => $tiempoNeto,
            'tiempo_formateado' => $this->formatearTiempo($tiempoNeto),
            'tiempo_pausa_total' => $tiempoPausaTotal,
            'pausado' => $registro->estado === 'pausado',
            'debug' => $debugData
        ]);
        
    } catch (\Exception $e) {
        Log::error('Error en getEstado: ' . $e->getMessage());
        return response()->json([
            'activo' => false,
            'estado' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
}

    /**
     * Obtener estadísticas del mes
     */
    /**
 * Obtener estadísticas del mes
 */
private function obtenerEstadisticasMes($empleadoId)
{
    $inicioMes = Carbon::now()->startOfMonth();
    $finMes = Carbon::now()->endOfMonth();

    // Obtener todos los registros del mes
    $registrosMes = DB::table('tabla_registros_tiempo')
        ->where('empleado_id', $empleadoId)
        ->whereBetween('created_at', [$inicioMes, $finMes])
        ->get();

    // Calcular horas totales CORRECTAMENTE
    $totalSegundos = 0;
    foreach ($registrosMes as $registro) {
        if ($registro->tiempo_total && $registro->tiempo_total > 0) {
            // Registro completado con tiempo positivo
            $totalSegundos += $registro->tiempo_total;
        } else if ($registro->fin === null && $registro->inicio) {
            // Registro activo - calcular tiempo hasta ahora
            $inicio = Carbon::parse($registro->inicio);
            $now = Carbon::now();
            $tiempoActivo = $now->diffInSeconds($inicio);
            
            // Asegurar que no sea negativo
            $totalSegundos += max(0, $tiempoActivo);
        }
    }

    // Asegurar que el total no sea negativo
    $totalSegundos = max(0, $totalSegundos);
    $totalHoras = $totalSegundos / 3600;
    $totalRegistros = $registrosMes->count();
    
    // CORREGIDO: Días trabajados = días distintos con AL MENOS UN registro
    $diasTrabajados = DB::table('tabla_registros_tiempo')
        ->where('empleado_id', $empleadoId)
        ->whereBetween('created_at', [$inicioMes, $finMes])
        ->where(function($query) {
            $query->whereNotNull('inicio')
                  ->orWhere('estado', 'activo')
                  ->orWhere('estado', 'pausado')
                  ->orWhere('estado', 'completado');
        })
        ->distinct()
        ->select(DB::raw('DATE(created_at) as fecha'))
        ->get()
        ->count();

    // Promedio diario basado en días trabajados
    $promedioDiario = $diasTrabajados > 0 ? $totalHoras / $diasTrabajados : 0;

    // AGREGAR VERSIONES FORMATEADAS
    return [
        'total_horas' => number_format($totalHoras, 2),
        'total_horas_formateado' => $this->formatDecimalHoursToHM($totalHoras),
        'total_registros' => $totalRegistros,
        'promedio_horas' => number_format($promedioDiario, 2),
        'promedio_horas_formateado' => $this->formatDecimalHoursToHM($promedioDiario),
        'dias_trabajados' => $diasTrabajados
    ];
}



/**
 * Formatear tiempo en segundos a formato legible
 */
private function formatearTiempo($segundos)
{
    $segundos = max(0, intval($segundos));
    
    $horas = floor($segundos / 3600);
    $minutos = floor(($segundos % 3600) / 60);
    $segundos = $segundos % 60;
    
    if ($horas > 0) {
        return sprintf('%d:%02d:%02d', $horas, $minutos, $segundos);
    } else {
        return sprintf('%d:%02d', $minutos, $segundos);
    }
}

    /**
     * Obtener historial del día
     */
    public function getHistorial($id)
    {
        try {
            $user = Auth::user();
            
            $empleado = DB::table('tabla_empleados')
                ->where('id', $id)
                ->where('credencial_id', $user->id)
                ->first();

            if (!$empleado) {
                return response()->json([
                    'success' => false,
                    'message' => 'No autorizado'
                ], 403);
            }

            $historial = DB::table('tabla_registros_tiempo')
                ->where('empleado_id', $empleado->id)
                ->whereDate('created_at', Carbon::today())
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($registro) {
                    return [
                        'inicio' => $registro->inicio,
                        'fin' => $registro->fin,
                        'tiempo_total' => $registro->tiempo_total,
                        'estado' => $registro->estado
                    ];
                });

            return response()->json([
                'success' => true,
                'historial' => $historial
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener historial'
            ], 500);
        }
    }


/**
 * Obtener datos para DataTable
 */
/**
 * Obtener datos para DataTable - VERSIÓN CORREGIDA
 */
public function getDataTable(Request $request, $id)
{
    try {
        $user = Auth::user();
        $empleado = DB::table('tabla_empleados')
            ->where('id', $id)
            ->where('credencial_id', $user->id)
            ->first();

        if (!$empleado) {
            return response()->json([
                'draw' => intval($request->input('draw', 1)),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ]);
        }

        // OBTENER PARÁMETROS DE DATATABLES
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $draw = $request->input('draw', 1);
        $search = $request->input('search.value');

        Log::info("📊 DataTable request:", $request->all());

        // QUERY BASE
        $query = DB::table('tabla_registros_tiempo')
            ->where('empleado_id', $empleado->id)
            ->select([
                'id', 
                'inicio', 
                'fin', 
                'tiempo_total', 
                'pausa_inicio',
                'pausa_fin', 
                'tiempo_pausa_total',
                'estado', 
                'created_at',
                'latitud',
                'longitud',
                'direccion',
                'ciudad',
                'pais'
            ]);

        // APLICAR FILTROS DE FECHA
        $month = $request->input('month');
        $year = $request->input('year');

        if ($month && $year) {
            $query->whereYear('created_at', $year)
                  ->whereMonth('created_at', $month);
        } else {
            // Por defecto, mes actual
            $now = Carbon::now();
            $query->whereYear('created_at', $now->year)
                  ->whereMonth('created_at', $now->month);
        }

        // CONTAR TOTAL DE REGISTROS (sin paginación)
        $recordsTotal = $query->count();

        // APLICAR BÚSQUEDA SI EXISTE
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('estado', 'like', "%{$search}%")
                  ->orWhere('direccion', 'like', "%{$search}%")
                  ->orWhere('ciudad', 'like', "%{$search}%")
                  ->orWhere('pais', 'like', "%{$search}%")
                  ->orWhereDate('created_at', 'like', "%{$search}%");
            });
        }

        // CONTAR REGISTROS FILTRADOS
        $recordsFiltered = $query->count();

        // APLICAR ORDENACIÓN
        $orderColumn = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'desc');
        
        $columns = ['created_at', 'inicio', 'fin', 'pausa_inicio', 'pausa_fin', 'tiempo_pausa_total', 'tiempo_total', 'direccion', 'estado'];
        $orderBy = $columns[$orderColumn] ?? 'created_at';
        
        $query->orderBy($orderBy, $orderDirection);

        // APLICAR PAGINACIÓN
        if ($length != -1) {
            $query->skip($start)->take($length);
        }

        // OBTENER DATOS
        $data = $query->get()->map(function($registro) {
            // Asegurar que los tiempos no sean negativos
            $tiempoTotal = max(0, $registro->tiempo_total ?? 0);
            $tiempoPausaTotal = max(0, $registro->tiempo_pausa_total ?? 0);
            
            return [
                'id' => $registro->id,
                'inicio' => $registro->inicio,
                'fin' => $registro->fin,
                'tiempo_total' => $tiempoTotal,
                'pausa_inicio' => $registro->pausa_inicio,
                'pausa_fin' => $registro->pausa_fin,
                'tiempo_pausa_total' => $tiempoPausaTotal,
                'estado' => $registro->estado,
                'created_at' => $registro->created_at,
                'latitud' => $registro->latitud,
                'longitud' => $registro->longitud,
                'direccion' => $registro->direccion,
                'ciudad' => $registro->ciudad,
                'pais' => $registro->pais
            ];
        });

        Log::info("✅ DataTable response:", [
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data_count' => $data->count()
        ]);

        // RESPUESTA CORRECTA PARA DATATABLES
        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ]);

    } catch (\Exception $e) {
        Log::error('❌ Error en DataTable:', [
            'empleado_id' => $id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'draw' => intval($request->input('draw', 1)),
            'recordsTotal' => 0,
            'recordsFiltered' => 0,
            'data' => [],
            'error' => 'Error al cargar datos'
        ], 500);
    }
}

/**
 * Obtener resumen del período - VERSIÓN CORREGIDA (días con registros)
 */
public function getResumenPeriodo(Request $request, $id)
{
    try {
        $user = Auth::user();
        $empleado = DB::table('tabla_empleados')
            ->where('id', $id)
            ->where('credencial_id', $user->id)
            ->first();

        if (!$empleado) {
            return response()->json([
                'total_horas' => '0.00',
                'total_horas_formateado' => '0h 00m',
                'total_registros' => 0,
                'promedio_diario' => '0.00',
                'promedio_diario_formateado' => '0h 00m',
                'dias_trabajados' => 0
            ]);
        }

        $query = DB::table('tabla_registros_tiempo')
            ->where('empleado_id', $empleado->id);

        // Aplicar filtros de mes y año
        $month = $request->input('month');
        $year = $request->input('year');

        if ($month && $year) {
            $query->whereYear('created_at', $year)
                  ->whereMonth('created_at', $month);
        } else {
            // Por defecto, mes actual
            $now = Carbon::now();
            $query->whereYear('created_at', $now->year)
                  ->whereMonth('created_at', $now->month);
        }

        $registros = $query->get();
        
        Log::info('Registros encontrados para resumen:', [
            'total' => $registros->count(),
            'empleado_id' => $empleado->id,
            'month' => $month,
            'year' => $year
        ]);

        // CALCULO MEJORADO - Incluir registros activos y calcular tiempos correctamente
        $totalSegundos = 0;
        $registrosValidos = 0;

        foreach ($registros as $registro) {
            Log::info('Analizando registro:', [
                'id' => $registro->id,
                'inicio' => $registro->inicio,
                'fin' => $registro->fin,
                'tiempo_total' => $registro->tiempo_total,
                'estado' => $registro->estado
            ]);

            if ($registro->tiempo_total && $registro->tiempo_total > 0) {
                // Registro completado con tiempo positivo
                $totalSegundos += $registro->tiempo_total;
                $registrosValidos++;
                Log::info('Registro válido - tiempo total:', ['tiempo' => $registro->tiempo_total]);
            } else if ($registro->fin === null && $registro->inicio && $registro->estado !== 'pausado') {
                // Registro activo - calcular tiempo hasta ahora
                $inicio = Carbon::parse($registro->inicio);
                $now = Carbon::now();
                
                // Calcular tiempo total bruto
                $tiempoBruto = $now->diffInSeconds($inicio);
                
                // Restar tiempo de pausas si existe
                $tiempoPausa = $registro->tiempo_pausa_total ?? 0;
                $tiempoNeto = max(0, $tiempoBruto - $tiempoPausa);
                
                $totalSegundos += $tiempoNeto;
                $registrosValidos++;
                
                Log::info('Registro activo - tiempo calculado:', [
                    'tiempo_bruto' => $tiempoBruto,
                    'tiempo_pausa' => $tiempoPausa,
                    'tiempo_neto' => $tiempoNeto
                ]);
            } else if ($registro->fin && (!$registro->tiempo_total || $registro->tiempo_total == 0)) {
                // Registro con fin pero sin tiempo_total - calcular manualmente
                $inicio = Carbon::parse($registro->inicio);
                $fin = Carbon::parse($registro->fin);
                
                $tiempoBruto = $fin->diffInSeconds($inicio);
                $tiempoPausa = $registro->tiempo_pausa_total ?? 0;
                $tiempoNeto = max(0, $tiempoBruto - $tiempoPausa);
                
                $totalSegundos += $tiempoNeto;
                $registrosValidos++;
                
                Log::info('Registro sin tiempo_total - calculado:', [
                    'tiempo_bruto' => $tiempoBruto,
                    'tiempo_pausa' => $tiempoPausa,
                    'tiempo_neto' => $tiempoNeto
                ]);
            }
        }

        // Asegurar que el total no sea negativo
        $totalSegundos = max(0, $totalSegundos);
        $totalHoras = $totalSegundos / 3600;
        $totalRegistros = $registros->count();
        
        // CORREGIDO: Días trabajados = días distintos con AL MENOS UN registro (iniciado)
        $diasTrabajados = DB::table('tabla_registros_tiempo')
            ->where('empleado_id', $empleado->id)
            ->where(function($query) {
                $query->whereNotNull('inicio')
                      ->orWhere('estado', 'activo')
                      ->orWhere('estado', 'pausado')
                      ->orWhere('estado', 'completado');
            });

        // Aplicar mismos filtros de fecha
        if ($month && $year) {
            $diasTrabajados->whereYear('created_at', $year)
                          ->whereMonth('created_at', $month);
        } else {
            $now = Carbon::now();
            $diasTrabajados->whereYear('created_at', $now->year)
                          ->whereMonth('created_at', $now->month);
        }

        $diasTrabajados = $diasTrabajados
            ->distinct()
            ->select(DB::raw('DATE(created_at) as fecha'))
            ->get()
            ->count();

        Log::info('Días trabajados calculados:', [
            'dias' => $diasTrabajados,
            'empleado_id' => $empleado->id
        ]);

        // Promedio diario basado en días trabajados
        $promedioDiario = $diasTrabajados > 0 ? $totalHoras / $diasTrabajados : 0;

        Log::info('Resumen final calculado:', [
            'total_segundos' => $totalSegundos,
            'total_horas' => $totalHoras,
            'total_registros' => $totalRegistros,
            'registros_validos' => $registrosValidos,
            'dias_trabajados' => $diasTrabajados,
            'promedio_diario' => $promedioDiario
        ]);

       // AGREGAR VERSIONES FORMATEADAS EN LA RESPUESTA
        return response()->json([
            'total_horas' => number_format($totalHoras, 2),
            'total_horas_formateado' => $this->formatDecimalHoursToHM($totalHoras),
            'total_registros' => $totalRegistros,
            'promedio_diario' => number_format($promedioDiario, 2),
            'promedio_diario_formateado' => $this->formatDecimalHoursToHM($promedioDiario),
            'dias_trabajados' => $diasTrabajados
        ]);

    } catch (\Exception $e) {
        Log::error('Error en resumen período:', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return response()->json([
            'total_horas' => '0.00',
            'total_horas_formateado' => '0h 00m',
            'total_registros' => 0,
            'promedio_diario' => '0.00',
            'promedio_diario_formateado' => '0h 00m',
            'dias_trabajados' => 0
        ]);
    }
}

/**
 * Obtener estadísticas del mes para AJAX
 */
public function getEstadisticasMes($id)
{
    try {
        $user = Auth::user();
        $empleado = DB::table('tabla_empleados')
            ->where('id', $id)
            ->where('credencial_id', $user->id)
            ->first();

        if (!$empleado) {
            return response()->json([
                'total_horas' => '0.00',
                'total_horas_formateado' => '0h 00m',
                'total_registros' => 0,
                'promedio_horas' => '0.00',
                'promedio_horas_formateado' => '0h 00m',
                'dias_trabajados' => 0
            ]);
        }

        $estadisticas = $this->obtenerEstadisticasMes($empleado->id);
        
        return response()->json($estadisticas);
        
    } catch (\Exception $e) {
        \Log::error('Error en getEstadisticasMes: ' . $e->getMessage());
        return response()->json([
            'total_horas' => '0.00',
            'total_horas_formateado' => '0h 00m',
            'total_registros' => 0,
            'promedio_horas' => '0.00',
            'promedio_horas_formateado' => '0h 00m',
            'dias_trabajados' => 0
        ]);
    }
}


/**
 * Obtener detalles de un registro específico
 */
public function getDetallesRegistro($empleadoId, $registroId)
{
    try {
        $user = Auth::user();
        
        $empleado = DB::table('tabla_empleados')
            ->where('id', $empleadoId)
            ->where('credencial_id', $user->id)
            ->first();

        if (!$empleado) {
            return response()->json([
                'success' => false,
                'message' => 'No autorizado'
            ], 403);
        }

        // Obtener el registro específico con datos de geolocalización
            $registro = DB::table('tabla_registros_tiempo')
                ->where('id', $registroId)
                ->where('empleado_id', $empleado->id)
                ->first();

            if (!$registro) {
                return response()->json([
                    'success' => false,
                    'message' => 'Registro no encontrado'
                ]);
            }

        // Obtener estadísticas del día del registro
        $fechaRegistro = Carbon::parse($registro->created_at)->format('Y-m-d');
        
        $estadisticasDia = DB::table('tabla_registros_tiempo')
            ->where('empleado_id', $empleado->id)
            ->whereDate('created_at', $fechaRegistro)
            ->select(
                DB::raw('COUNT(*) as total_registros_dia'),
                DB::raw('COALESCE(SUM(tiempo_total), 0) as total_segundos_dia')
            )
            ->first();

        // Calcular horas totales del día
        $totalHorasDia = number_format(($estadisticasDia->total_segundos_dia / 3600), 2);
        $promedioPorRegistro = $estadisticasDia->total_registros_dia > 0 
            ? number_format(($estadisticasDia->total_segundos_dia / $estadisticasDia->total_registros_dia / 3600), 2)
            : '0.00';

        return response()->json([
            'success' => true,
            'registro' => $registro,
            'estadisticasDia' => [
                'total_registros_dia' => $estadisticasDia->total_registros_dia,
                'total_horas_dia' => $totalHorasDia,
                'promedio_por_registro' => $promedioPorRegistro
            ]
        ]);

    } catch (\Exception $e) {
        \Log::error('Error en getDetallesRegistro: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error al obtener detalles del registro'
        ], 500);
    }
}



/**
 * Reparar todos los registros con fechas de inicio en el futuro
 */
public function repararFechasFuturas($empleadoId)
{
    try {
        $registros = DB::table('tabla_registros_tiempo')
            ->where('empleado_id', $empleadoId)
            ->whereNull('fin')
            ->whereDate('created_at', Carbon::today())
            ->get();

        $corregidos = 0;
        
        foreach ($registros as $registro) {
            $inicio = Carbon::parse($registro->inicio);
            $now = Carbon::now();
            
            if ($inicio->greaterThan($now)) {
                // Corregir la fecha
                $nuevoInicio = $now->copy()->subMinutes(30);
                
                DB::table('tabla_registros_tiempo')
                    ->where('id', $registro->id)
                    ->update([
                        'inicio' => $nuevoInicio,
                        'updated_at' => Carbon::now()
                    ]);
                    
                $corregidos++;
                \Log::info("Registro {$registro->id} corregido: {$registro->inicio} -> {$nuevoInicio}");
            }
        }

        return "Registros corregidos: {$corregidos}";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
}


 /**
     * Procesar datos de geolocalización
     */
    private function procesarGeolocalizacion(Request $request)
    {
         // Obtener datos directamente del request
        $latitud = $request->input('latitud');
        $longitud = $request->input('longitud');
        $direccion = $request->input('direccion');
        $ciudad = $request->input('ciudad');
        $pais = $request->input('pais');
        $precision = $request->input('precision');

        Log::info('Datos de geolocalización recibidos en el request:', [
            'latitud' => $latitud,
            'longitud' => $longitud,
            'direccion' => $direccion,
            'ciudad' => $ciudad,
            'pais' => $pais,
            'precision' => $precision
        ]);

        // Validar que tengamos al menos coordenadas
        // Si tenemos datos válidos de Google Maps, usarlos directamente
         // SI TENEMOS COORDENADAS DEL GPS, USARLAS DIRECTAMENTE
        if ($latitud && $longitud) {
            Log::info('✅ Usando datos de GPS del frontend');
            
            // Si la ciudad o país son los valores por defecto, usar coordenadas
            $valoresNoDeseados = [
                'Por coordenadas GPS',
                'Ubicación por GPS', 
                'Ciudad no disponible',
                'País no disponible',
                'Ciudad no especificada',
                'País no especificado',
                'Local', // ← QUITAR ESTE
                'Entorno local/desarrollo' // ← Y ESTE
            ];
            
            $ciudadValida = $ciudad && !in_array($ciudad, $valoresNoDeseados);
            $paisValido = $pais && !in_array($pais, $valoresNoDeseados);
            
            if ($ciudadValida && $paisValido) {
                // Tenemos ciudad y país válidos
                return [
                    'latitud' => $latitud,
                    'longitud' => $longitud,
                    'direccion' => $direccion,
                    'ciudad' => $ciudad,
                    'pais' => $pais,
                    'precision' => $precision
                ];
            } else {
                // Tenemos coordenadas pero no ciudad/pais válidos
                // Crear una descripción basada en coordenadas
                $coordenadasFormateadas = number_format($latitud, 6) . ', ' . number_format($longitud, 6);
                
                return [
                    'latitud' => $latitud,
                    'longitud' => $longitud,
                    'direccion' => "Ubicación GPS: $coordenadasFormateadas",
                    'ciudad' => 'Ubicación por GPS',
                    'pais' => 'GPS',
                    'precision' => $precision
                ];
            }
        }

        // Fallback: obtener ubicación por IP
        Log::warning('❌ No se recibieron coordenadas GPS');
        return [
            'latitud' => null,
            'longitud' => null,
            'direccion' => 'Geolocalización no disponible',
            'ciudad' => 'GPS no disponible',
            'pais' => 'Sin ubicación',
            'precision' => null
        ];
    }



    /**
     * Validar coordenadas geográficas
     */
    private function validarCoordenadas($latitud, $longitud)
    {
        return is_numeric($latitud) && 
               is_numeric($longitud) &&
               $latitud >= -90 && $latitud <= 90 &&
               $longitud >= -180 && $longitud <= 180;
    }

    /**
     * Formatear horas decimales a formato "Xh Xm"
     */
    private function formatDecimalHoursToHM($decimalHours)
    {
        if (!$decimalHours || $decimalHours == 0) {
            return '0h 00m';
        }

        // Si es string, convertir a float
        if (is_string($decimalHours)) {
            $decimalHours = floatval($decimalHours);
        }

        $horas = floor($decimalHours);
        $minutosDecimal = ($decimalHours - $horas) * 60;
        $minutos = round($minutosDecimal);

        // Si los minutos son 60, sumar una hora
        if ($minutos == 60) {
            return ($horas + 1) . 'h 00m';
        }

        return $horas . 'h ' . str_pad($minutos, 2, '0', STR_PAD_LEFT) . 'm';
    }


    /**
 * Obtener progreso semanal del empleado
 */
private function obtenerProgresoSemanal($empleadoId)
{
    $progreso = [];
    $diasSemana = [
        'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'
    ];

    // USAR created_at PARA LAS FECHAS - importante
    $inicioSemana = Carbon::now()->startOfWeek();
    $finSemana = Carbon::now()->endOfWeek();

    Log::info('🔍 BUSCANDO PROGRESO SEMANAL', [
        'empleado_id' => $empleadoId,
        'semana_inicio' => $inicioSemana->format('Y-m-d H:i:s'),
        'semana_fin' => $finSemana->format('Y-m-d H:i:s'),
        'hoy' => Carbon::now()->format('Y-m-d H:i:s')
    ]);

    // Colores para las barras de progreso
    $colores = ['success', 'info', 'warning', 'danger', 'primary', 'secondary', 'dark'];

    foreach ($diasSemana as $index => $dia) {
        $fecha = $inicioSemana->copy()->addDays($index);
        $fechaInicio = $fecha->copy()->startOfDay();
        $fechaFin = $fecha->copy()->endOfDay();
        
        Log::info("📅 Procesando día: {$dia} - {$fecha->format('Y-m-d')}");

        // Obtener registros del día específico - BUSCAR POR created_at
        $registrosDia = DB::table('tabla_registros_tiempo')
            ->where('empleado_id', $empleadoId)
            ->whereBetween('created_at', [$fechaInicio, $fechaFin])
            ->get();

        Log::info("📊 Registros encontrados para {$dia}: " . $registrosDia->count());

        // Calcular horas trabajadas en el día
        $totalSegundosDia = 0;
        
        foreach ($registrosDia as $registro) {
            Log::info("🕒 Procesando registro ID: {$registro->id}", [
                'inicio' => $registro->inicio,
                'fin' => $registro->fin,
                'tiempo_total' => $registro->tiempo_total,
                'estado' => $registro->estado,
                'created_at' => $registro->created_at
            ]);

            $tiempoRegistro = 0;

            if ($registro->tiempo_total && $registro->tiempo_total > 0) {
                // Registro completado con tiempo positivo
                $tiempoRegistro = $registro->tiempo_total;
                Log::info("✅ Tiempo desde BD: {$tiempoRegistro}s");
            } else if ($registro->fin === null && $registro->inicio) {
                // Registro activo - calcular tiempo hasta ahora
                try {
                    $inicio = Carbon::parse($registro->inicio);
                    $now = Carbon::now();
                    
                    if ($inicio->greaterThan($now)) {
                        Log::warning("⚠️  Fecha de inicio en el futuro: {$registro->inicio}");
                        $tiempoRegistro = 0;
                    } else {
                        $tiempoBruto = $now->diffInSeconds($inicio);
                        $tiempoPausa = $registro->tiempo_pausa_total ?? 0;
                        $tiempoRegistro = max(0, $tiempoBruto - $tiempoPausa);
                        Log::info("⏱️  Tiempo activo calculado: {$tiempoRegistro}s");
                    }
                } catch (\Exception $e) {
                    Log::error("❌ Error calculando tiempo activo: " . $e->getMessage());
                    $tiempoRegistro = 0;
                }
            } else if ($registro->fin && $registro->inicio) {
                // Calcular manualmente si no hay tiempo_total
                try {
                    $inicio = Carbon::parse($registro->inicio);
                    $fin = Carbon::parse($registro->fin);
                    
                    if ($fin->greaterThan($inicio)) {
                        $tiempoBruto = $fin->diffInSeconds($inicio);
                        $tiempoPausa = $registro->tiempo_pausa_total ?? 0;
                        $tiempoRegistro = max(0, $tiempoBruto - $tiempoPausa);
                        Log::info("🔧 Tiempo manual calculado: {$tiempoRegistro}s");
                    } else {
                        Log::warning("⚠️  Fin anterior a inicio: {$registro->inicio} -> {$registro->fin}");
                        $tiempoRegistro = 0;
                    }
                } catch (\Exception $e) {
                    Log::error("❌ Error calculando tiempo manual: " . $e->getMessage());
                    $tiempoRegistro = 0;
                }
            }

            $totalSegundosDia += $tiempoRegistro;
        }

        $horasDecimal = $totalSegundosDia / 3600;
        
        // Calcular porcentaje (asumiendo 8 horas como 100%)
        $horasMeta = 8;
        $porcentaje = $horasMeta > 0 ? min(100, ($horasDecimal / $horasMeta) * 100) : 0;

        $progreso[] = [
            'nombre' => $dia,
            'horas' => number_format($horasDecimal, 1),
            'porcentaje' => round($porcentaje, 1),
            'color' => $colores[$index] ?? 'primary',
            'fecha' => $fecha->format('Y-m-d'),
            'registros' => $registrosDia->count(),
            'total_segundos' => $totalSegundosDia
        ];

        Log::info("📈 Resultado final para {$dia}", [
            'horas' => number_format($horasDecimal, 1),
            'porcentaje' => round($porcentaje, 1),
            'segundos' => $totalSegundosDia,
            'registros' => $registrosDia->count()
        ]);
    }

    Log::info('🎯 PROGRESO SEMANAL FINAL', $progreso);
    return $progreso;
}

/**
 * Obtener logros del empleado
 */
private function obtenerLogros($empleadoId)
{
    $logros = [];
    $inicioSemana = Carbon::now()->startOfWeek();
    $finSemana = Carbon::now()->endOfWeek();

    Log::info('Calculando logros para la semana:', [
        'inicio' => $inicioSemana->format('Y-m-d'),
        'fin' => $finSemana->format('Y-m-d')
    ]);

    // Calcular horas totales de la semana
    $registrosSemana = DB::table('tabla_registros_tiempo')
        ->where('empleado_id', $empleadoId)
        ->whereBetween('created_at', [$inicioSemana, $finSemana])
        ->get();

    Log::info('Registros de la semana encontrados:', ['count' => $registrosSemana->count()]);

    $totalSegundosSemana = 0;
    foreach ($registrosSemana as $registro) {
        if ($registro->tiempo_total && $registro->tiempo_total > 0) {
            $totalSegundosSemana += $registro->tiempo_total;
        } else if ($registro->fin === null && $registro->inicio && $registro->estado !== 'pausado') {
            $inicio = Carbon::parse($registro->inicio);
            $now = Carbon::now();
            $tiempoBruto = $now->diffInSeconds($inicio);
            $tiempoPausa = $registro->tiempo_pausa_total ?? 0;
            $tiempoNeto = max(0, $tiempoBruto - $tiempoPausa);
            $totalSegundosSemana += $tiempoNeto;
        } else if ($registro->fin && (!$registro->tiempo_total || $registro->tiempo_total == 0)) {
            $inicio = Carbon::parse($registro->inicio);
            $fin = Carbon::parse($registro->fin);
            $tiempoBruto = $fin->diffInSeconds($inicio);
            $tiempoPausa = $registro->tiempo_pausa_total ?? 0;
            $tiempoNeto = max(0, $tiempoBruto - $tiempoPausa);
            $totalSegundosSemana += $tiempoNeto;
        }
    }

    $totalHorasSemanaDecimal = $totalSegundosSemana / 3600;

    Log::info('Total horas semana calculado:', [
        'segundos' => $totalSegundosSemana,
        'horas' => $totalHorasSemanaDecimal
    ]);

    // Logro: +40h esta semana
    if ($totalHorasSemanaDecimal >= 40) {
        $logros[] = [
            'icono' => 'clock',
            'texto' => '+40h esta semana',
            'color' => 'primary',
            'completado' => true
        ];
    } else {
        $logros[] = [
            'icono' => 'clock',
            'texto' => number_format($totalHorasSemanaDecimal, 1) . 'h / 40h esta semana',
            'color' => 'primary',
            'completado' => false,
            'progreso' => min(100, ($totalHorasSemanaDecimal / 40) * 100)
        ];
    }

    // Logro: Días consecutivos
    $diasConsecutivos = $this->calcularDiasConsecutivos($empleadoId);
    
    if ($diasConsecutivos >= 5) {
        $logros[] = [
            'icono' => 'calendar-check',
            'texto' => $diasConsecutivos . ' días consecutivos',
            'color' => 'success',
            'completado' => true
        ];
    } else {
        $logros[] = [
            'icono' => 'calendar-check',
            'texto' => $diasConsecutivos . ' de 5 días consecutivos',
            'color' => 'success',
            'completado' => false,
            'progreso' => min(100, ($diasConsecutivos / 5) * 100)
        ];
    }

    // Logro adicional: Días trabajados esta semana
    $diasTrabajadosSemana = DB::table('tabla_registros_tiempo')
        ->where('empleado_id', $empleadoId)
        ->whereBetween('created_at', [$inicioSemana, $finSemana])
        ->distinct()
        ->select(DB::raw('DATE(created_at) as fecha'))
        ->get()
        ->count();

    if ($diasTrabajadosSemana >= 5) {
        $logros[] = [
            'icono' => 'star',
            'texto' => $diasTrabajadosSemana . ' días esta semana',
            'color' => 'warning',
            'completado' => true
        ];
    } else {
        $logros[] = [
            'icono' => 'star',
            'texto' => $diasTrabajadosSemana . ' de 5 días esta semana',
            'color' => 'warning',
            'completado' => false,
            'progreso' => min(100, ($diasTrabajadosSemana / 5) * 100)
        ];
    }

    Log::info('Logros calculados:', $logros);
    return $logros;
}

/**
 * Calcular días consecutivos trabajados
 */
private function calcularDiasConsecutivos($empleadoId)
{
    $hoy = Carbon::today();
    $diasConsecutivos = 0;

    for ($i = 0; $i < 7; $i++) {
        $fecha = $hoy->copy()->subDays($i);
        
        $trabajoDia = DB::table('tabla_registros_tiempo')
            ->where('empleado_id', $empleadoId)
            ->whereDate('created_at', $fecha->format('Y-m-d'))
            ->exists();

        if ($trabajoDia) {
            $diasConsecutivos++;
        } else {
            break;
        }
    }

    return $diasConsecutivos;
}

/**
 * Obtener datos de progreso semanal via AJAX
 */
public function getProgresoSemanal($id)
{
    try {
        $user = Auth::user();
        $empleado = DB::table('tabla_empleados')
            ->where('id', $id)
            ->where('credencial_id', $user->id)
            ->first();

        if (!$empleado) {
            return response()->json([
                'success' => false,
                'message' => 'No autorizado'
            ], 403);
        }

        $progresoSemanal = $this->obtenerProgresoSemanal($empleado->id);
        $logros = $this->obtenerLogros($empleado->id);

        return response()->json([
            'success' => true,
            'progresoSemanal' => $progresoSemanal,
            'logros' => $logros
        ]);

    } catch (\Exception $e) {
        Log::error('Error en getProgresoSemanal: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error al obtener progreso semanal'
        ], 500);
    }
}

    /**
 * Obtener ubicación aproximada por IP
 */
    /*private function obtenerUbicacionPorIP($ip)
    {
        try {
            // Para IPs locales, usar datos por defecto
            if ($ip === '127.0.0.1' || $ip === '::1') {
                return [
                    'latitud' => null,
                    'longitud' => null,
                    'direccion' => 'Entorno local/desarrollo',
                    'ciudad' => 'Local',
                    'pais' => 'Local',
                    'precision' => null
                ];
            }

            // Usar servicio de geolocalización por IP
            $url = "http://ipapi.co/{$ip}/json/";
            
            $response = file_get_contents($url);
            $data = json_decode($response, true);

            if (isset($data['latitude']) && isset($data['longitude'])) {
                return [
                    'latitud' => $data['latitude'],
                    'longitud' => $data['longitude'],
                    'direccion' => ($data['city'] ?? '') . ', ' . ($data['region'] ?? ''),
                    'ciudad' => $data['city'] ?? 'Ciudad desconocida',
                    'pais' => $data['country_name'] ?? 'País desconocido',
                    'precision' => 50000 // Baja precisión por IP
                ];
            }

        } catch (\Exception $e) {
            Log::warning('Error al obtener ubicación por IP: ' . $e->getMessage());
        }

        return [
            'latitud' => null,
            'longitud' => null,
            'direccion' => 'Ubicación no disponible',
            'ciudad' => 'Ubicación no disponible',
            'pais' => 'Ubicación no disponible',
            'precision' => null
        ];
    }*/

}