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

        // Pasar el empleado a la vista
        return view('empleado.sections.perfil', compact(
            'empleado', 
            'registroActivo', 
            'historialHoy',
            'estadisticasMes'
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

            // Insertar con campos NULLABLE
            $now = Carbon::now();
            
            $registroId = DB::table('tabla_registros_tiempo')->insertGetId([
                'empleado_id' => $id,
                'inicio' => $now,
                'estado' => 'activo',
                'created_at' => $now,
                'updated_at' => $now
                // 'fin' y 'tiempo_total' serán NULL automáticamente
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tiempo iniciado correctamente',
                'registro_id' => $registroId
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

      /**
 * Pausar o reanudar el tiempo - VERSIÓN COMPLETAMENTE CORREGIDA
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
            
            \Log::info("=== PAUSA/RENAUDAR ===");
            \Log::info("Registro ID: {$registro->id}");
            \Log::info("Estado actual: {$registro->estado}");
            \Log::info("Nuevo estado: {$nuevoEstado}");
            \Log::info("Pausa inicio actual: " . ($registro->pausa_inicio ?? 'NULO'));
            \Log::info("Pausa fin actual: " . ($registro->pausa_fin ?? 'NULO'));
            \Log::info("Tiempo pausa acumulado: " . ($registro->tiempo_pausa_total ?? 0));
            
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
                    
                \Log::info("PAUSA INICIADA - Hora: {$now}");
                    
                $mensaje = 'Tiempo pausado';
            } else {
                // REANUDAR - calcular tiempo de pausa actual y acumular
                if (!$registro->pausa_inicio) {
                    \Log::error("No hay pausa_inicio para reanudar");
                    return response()->json([
                        'success' => false,
                        'message' => 'No hay pausa iniciada para reanudar'
                    ]);
                }
                
                $pausaInicio = Carbon::parse($registro->pausa_inicio);
                
                // Verificar que la pausa no sea en el futuro
                if ($pausaInicio->greaterThan($now)) {
                    \Log::warning('Pausa inicio en el futuro, corrigiendo...');
                    $pausaInicio = $now->copy()->subSecond();
                }
                
                $tiempoPausaActual = $now->diffInSeconds($pausaInicio);
                
                // Tiempo de pausa total acumulado (anterior + actual)
                $tiempoPausaAnterior = $registro->tiempo_pausa_total ?? 0;
                $tiempoPausaTotal = $tiempoPausaActual - $tiempoPausaAnterior;
                
                \Log::info("=== REANUDAR PAUSA ===");
                \Log::info("Pausa desde: {$registro->pausa_inicio}");
                \Log::info("Reanudar en: {$now}");
                \Log::info("Tiempo pausa actual: {$tiempoPausaActual} segundos");
                \Log::info("Tiempo pausa anterior: {$tiempoPausaAnterior} segundos");
                \Log::info("Tiempo pausa total acumulado: {$tiempoPausaTotal} segundos");
                
                DB::table('tabla_registros_tiempo')
                    ->where('id', $registro->id)
                    ->update([
                        'estado' => 'activo',
                        'pausa_fin' => $now,
                        'tiempo_pausa_total' => $tiempoPausaTotal,
                        'updated_at' => $now
                    ]);
                    
                $mensaje = 'Tiempo reanudado';
            }
            
            // Obtener el registro actualizado para verificar
            $registroActualizado = DB::table('tabla_registros_tiempo')
                ->where('id', $registro->id)
                ->first();
                
            \Log::info("Registro actualizado:", [
                'estado' => $registroActualizado->estado,
                'pausa_inicio' => $registroActualizado->pausa_inicio,
                'pausa_fin' => $registroActualizado->pausa_fin,
                'tiempo_pausa_total' => $registroActualizado->tiempo_pausa_total
            ]);
            
            return response()->json([
                'success' => true,
                'message' => $mensaje,
                'estado' => $nuevoEstado,
                'tiempo_pausa_total' => $registroActualizado->tiempo_pausa_total ?? 0
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error al pausar el tiempo: ' . $e->getMessage());
            \Log::error('Trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Error al pausar el tiempo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
 * Detener el tiempo - VERSIÓN CON CÁLCULO SIMPLIFICADO
 */
public function stopTiempo(Request $request, $id)
{
    try {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'No autenticado'
            ], 401);
        }

        $registro = DB::table('tabla_registros_tiempo')
            ->where('empleado_id', $id)
            ->whereNull('fin')
            ->whereDate('created_at', Carbon::today())
            ->first();
        
        if (!$registro) {
            return response()->json([
                'success' => false,
                'message' => 'No hay tiempo activo para detener'
            ]);
        }
        
        $fin = Carbon::now();
        $inicio = Carbon::parse($registro->inicio);
        
        \Log::info("=== STOP TIEMPO - CÁLCULO SIMPLIFICADO ===");

        // CÁLCULO SIMPLIFICADO
        // 1. Tiempo total bruto
        $tiempoTotalSegundos = $fin->getTimestamp() - $inicio->getTimestamp();
        $tiempoTotalSegundos = max(0, $tiempoTotalSegundos);
        \Log::info("Tiempo total bruto: {$tiempoTotalSegundos} segundos");

        // 2. Tiempo de pausa total
        $tiempoPausaTotal = max(0, intval($registro->tiempo_pausa_total ?? 0));
        \Log::info("Tiempo pausa almacenado: {$tiempoPausaTotal} segundos");

        // 3. Si hay pausa activa, agregar tiempo actual
        if ($registro->pausa_inicio && !$registro->pausa_fin) {
            $pausaInicio = Carbon::parse($registro->pausa_inicio);
            $tiempoPausaActual = $fin->getTimestamp() - $pausaInicio->getTimestamp();
            $tiempoPausaActual = max(0, $tiempoPausaActual);
            $tiempoPausaTotal += $tiempoPausaActual;
            \Log::info("Pausa activa agregada: {$tiempoPausaActual} segundos");
        }

        // 4. Tiempo neto
        $tiempoNeto = max(0, $tiempoTotalSegundos - $tiempoPausaTotal);

        \Log::info("Tiempo neto final: {$tiempoNeto} segundos");

        // Actualizar registro
        DB::table('tabla_registros_tiempo')
            ->where('id', $registro->id)
            ->update([
                'fin' => $fin,
                'pausa_fin' => $fin,
                'estado' => 'completado',
                'tiempo_total' => $tiempoNeto,
                'tiempo_pausa_total' => $tiempoPausaTotal,
                'updated_at' => Carbon::now()
            ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Tiempo detenido correctamente',
            'tiempo_total' => $tiempoNeto,
            'tiempo_formateado' => $this->formatearTiempo($tiempoNeto),
            'tiempo_pausa_formateado' => $this->formatearTiempo($tiempoPausaTotal)
        ]);
        
    } catch (\Exception $e) {
        \Log::error('Error en stopTiempo: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error al detener el tiempo: ' . $e->getMessage()
        ], 500);
    }
}



/**
 * Obtener estado actual del tiempo - VERSIÓN CON CÁLCULO DIRECTO
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
        
        \Log::info("=== GET ESTADO - CÁLCULO DIRECTO ===");
        \Log::info("Inicio: {$registro->inicio}");
        \Log::info("Estado: {$registro->estado}");

        $inicio = Carbon::parse($registro->inicio);
        $now = Carbon::now();
        
        // CÁLCULO DIRECTO Y SIMPLE
        // 1. Tiempo total desde inicio hasta ahora
        $segundosTranscurridos = $now->getTimestamp() - $inicio->getTimestamp();
        \Log::info("Tiempo total (timestamp): {$segundosTranscurridos} segundos");
        
        // Asegurar que no sea negativo
        $segundosTranscurridos = max(0, $segundosTranscurridos);
        \Log::info("Tiempo total (corregido): {$segundosTranscurridos} segundos");

        // 2. Tiempo de pausa total
        $tiempoPausaTotal = max(0, intval($registro->tiempo_pausa_total ?? 0));
        \Log::info("Tiempo pausa almacenado: {$tiempoPausaTotal} segundos");

        // 3. Si está PAUSADO actualmente, agregar tiempo de pausa actual
        if ($registro->estado === 'pausado' && $registro->pausa_inicio) {
            $pausaInicio = Carbon::parse($registro->pausa_inicio);
            $pausaActual = $now->getTimestamp() - $pausaInicio->getTimestamp();
            $pausaActual = max(0, $pausaActual);
            
            $tiempoPausaTotal += $pausaActual;
            \Log::info("Pausa activa - tiempo agregado: {$pausaActual} segundos");
        }

        \Log::info("Tiempo pausa total: {$tiempoPausaTotal} segundos");

        // 4. Tiempo neto trabajado
        $segundosNetos = max(0, $segundosTranscurridos - $tiempoPausaTotal);
        
        \Log::info("=== RESUMEN FINAL ===");
        \Log::info("Tiempo bruto: {$segundosTranscurridos} segundos");
        \Log::info("Tiempo pausa: {$tiempoPausaTotal} segundos");
        \Log::info("Tiempo neto: {$segundosNetos} segundos");

        // Formatear tiempos
        $tiempoFormateado = $this->formatearTiempo($segundosNetos);
        $tiempoBrutoFormateado = $this->formatearTiempo($segundosTranscurridos);
        $pausaFormateada = $this->formatearTiempo($tiempoPausaTotal);

        return response()->json([
            'activo' => true,
            'estado' => $registro->estado,
            'inicio' => $registro->inicio,
            'tiempo_transcurrido' => $segundosNetos,
            'tiempo_formateado' => $tiempoFormateado,
            'tiempo_pausa_total' => $tiempoPausaTotal,
            'pausado' => $registro->estado === 'pausado',
            'debug' => [
                'segundos_totales' => $segundosTranscurridos,
                'pausa_acumulada' => $tiempoPausaTotal,
                'segundos_netos' => $segundosNetos,
                'tiempo_bruto_formateado' => $tiempoBrutoFormateado,
                'pausa_formateada' => $pausaFormateada,
                'pausa_inicio' => $registro->pausa_inicio,
                'pausa_fin' => $registro->pausa_fin,
                'timestamp_inicio' => $inicio->getTimestamp(),
                'timestamp_ahora' => $now->getTimestamp()
            ]
        ]);
        
    } catch (\Exception $e) {
        \Log::error('Error en getEstado: ' . $e->getMessage());
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
        
        // Días trabajados = días distintos con registros
        $diasTrabajados = $registrosMes->unique(function($registro) {
            return Carbon::parse($registro->created_at)->format('Y-m-d');
        })->count();

        // Promedio diario basado en días trabajados
        $promedioDiario = $diasTrabajados > 0 ? $totalHoras / $diasTrabajados : 0;

        return [
            'total_horas' => number_format($totalHoras, 2),
            'total_registros' => $totalRegistros,
            'promedio_horas' => number_format($promedioDiario, 2),
            'dias_trabajados' => $diasTrabajados
        ];
    }



    /**
     * Formatear tiempo en segundos a HH:MM:SS
     */
    private function formatearTiempo($segundos)
    {
        // Asegurar que sea un número y positivo
        $segundos = intval($segundos);
        $esNegativo = $segundos < 0;
        $segundos = abs($segundos); // Trabajar con valor absoluto
        
        $horas = floor($segundos / 3600);
        $minutos = floor(($segundos % 3600) / 60);
        $segundos = $segundos % 60;
        
        $formateado = sprintf('%02d:%02d:%02d', $horas, $minutos, $segundos);
        
        // Si era negativo, agregar signo
        if ($esNegativo) {
            $formateado = '-' . $formateado;
        }
        
        return $formateado;
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
                'created_at'
            ]);

        // Aplicar filtros de mes y año
        $month = $request->input('month');
        $year = $request->input('year');

        if ($month && $year) {
            $query->whereYear('created_at', $year)
                  ->whereMonth('created_at', $month);
        } else {
            // Por defecto, mes actual
            $query->whereYear('created_at', date('Y'))
                  ->whereMonth('created_at', date('m'));
        }

        // Obtener datos y procesar tiempos
        $data = $query->orderBy('created_at', 'desc')->get()->map(function($registro) {
            // Asegurar que los tiempos no sean negativos
            $tiempoTotal = max(0, $registro->tiempo_total ?? 0);
            $tiempoPausaTotal = max(0, $registro->tiempo_pausa_total ?? 0);
            
            // DEBUG para verificar datos
            \Log::info("Registro ID {$registro->id}:");
            \Log::info("  - Inicio: {$registro->inicio}, Fin: {$registro->fin}");
            \Log::info("  - Pausa inicio: {$registro->pausa_inicio}, Pausa fin: {$registro->pausa_fin}");
            \Log::info("  - Tiempo total: {$tiempoTotal}, Tiempo pausa: {$tiempoPausaTotal}");
            
            return [
                'id' => $registro->id,
                'inicio' => $registro->inicio,
                'fin' => $registro->fin,
                'tiempo_total' => $tiempoTotal,
                'pausa_inicio' => $registro->pausa_inicio,
                'pausa_fin' => $registro->pausa_fin,
                'tiempo_pausa_total' => $tiempoPausaTotal,
                'estado' => $registro->estado,
                'created_at' => $registro->created_at
            ];
        });

        $recordsTotal = $data->count();

        return response()->json([
            'draw' => intval($request->input('draw', 1)),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => $data
        ]);

    } catch (\Exception $e) {
        \Log::error('Error en DataTable', [
            'empleado_id' => $id,
            'error' => $e->getMessage()
        ]);
        
        return response()->json([
            'draw' => intval($request->input('draw', 1)),
            'recordsTotal' => 0,
            'recordsFiltered' => 0,
            'data' => []
        ]);
    }
}


/**
 * Obtener resumen del período - VERSIÓN CORREGIDA
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
                'total_registros' => 0,
                'promedio_diario' => '0.00',
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
        
        \Log::info('Registros encontrados para resumen:', [
            'total' => $registros->count(),
            'empleado_id' => $empleado->id,
            'month' => $month,
            'year' => $year
        ]);

        // CALCULO MEJORADO - Incluir registros activos y calcular tiempos correctamente
        $totalSegundos = 0;
        $registrosValidos = 0;

        foreach ($registros as $registro) {
            \Log::info('Analizando registro:', [
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
                \Log::info('Registro válido - tiempo total:', ['tiempo' => $registro->tiempo_total]);
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
                
                \Log::info('Registro activo - tiempo calculado:', [
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
                
                \Log::info('Registro sin tiempo_total - calculado:', [
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
        
        // Días trabajados = días distintos con registros válidos
        $diasTrabajados = $registros->filter(function($registro) {
            return ($registro->tiempo_total && $registro->tiempo_total > 0) || 
                   ($registro->fin === null && $registro->inicio);
        })->unique(function($registro) {
            return Carbon::parse($registro->created_at)->format('Y-m-d');
        })->count();

        // Promedio diario basado en días trabajados
        $promedioDiario = $diasTrabajados > 0 ? $totalHoras / $diasTrabajados : 0;

        \Log::info('Resumen final calculado:', [
            'total_segundos' => $totalSegundos,
            'total_horas' => $totalHoras,
            'total_registros' => $totalRegistros,
            'registros_validos' => $registrosValidos,
            'dias_trabajados' => $diasTrabajados,
            'promedio_diario' => $promedioDiario
        ]);

        return response()->json([
            'total_horas' => number_format($totalHoras, 2),
            'total_registros' => $totalRegistros,
            'promedio_diario' => number_format($promedioDiario, 2),
            'dias_trabajados' => $diasTrabajados
        ]);

    } catch (\Exception $e) {
        \Log::error('Error en resumen período:', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return response()->json([
            'total_horas' => '0.00',
            'total_registros' => 0,
            'promedio_diario' => '0.00',
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
                'total_registros' => 0,
                'promedio_horas' => '0.00',
                'dias_trabajados' => 0
            ]);
        }

        $estadisticas = $this->obtenerEstadisticasMes($empleado->id);
        
        return response()->json($estadisticas);
        
    } catch (\Exception $e) {
        \Log::error('Error en getEstadisticasMes: ' . $e->getMessage());
        return response()->json([
            'total_horas' => '0.00',
            'total_registros' => 0,
            'promedio_horas' => '0.00',
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

        // Obtener el registro específico
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

}