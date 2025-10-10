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
            // SOLUCIÓN SIMPLIFICADA - Sin verificaciones complejas
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No autenticado'
                ], 401);
            }

            $user = Auth::user();

            // Verificar si ya existe un registro activo para hoy
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

            // Crear nuevo registro - CON TODOS LOS CAMPOS REQUERIDOS
            $now = Carbon::now();
            
            // Inserción completa con todos los campos que necesita la tabla
            $registroId = DB::table('tabla_registros_tiempo')->insertGetId([
                'empleado_id' => $id,
                'inicio' => $now,
                'fin' => null,           // ← EXPLÍCITAMENTE NULL
                'estado' => 'activo',
                'tiempo_total' => null,  // ← EXPLÍCITAMENTE NULL
                'created_at' => $now,
                'updated_at' => $now
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tiempo iniciado correctamente',
                'registro_id' => $registroId,
                'inicio' => $now->toDateTimeString()
            ]);

        } catch (\Exception $e) {
            // Log correcto usando el facade
            \Log::error('Error en startTiempo: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * Pausar o reanudar el tiempo
     */
    public function pauseTiempo(Request $request, $id)
    {
        try {
            // Verificar que el empleado solo pueda operar sobre su propio tiempo
            if (Auth::user()->empleado->id != $id) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permiso para esta acción'
                ], 403);
            }

            $empleado = Auth::user()->empleado;
            
            // Obtener el registro activo
            $registro = DB::table('tabla_registros_tiempo')
                ->where('empleado_id', $empleado->id)
                ->whereNull('fin')
                ->whereDate('created_at', Carbon::today())
                ->first();
            
            if (!$registro) {
                return response()->json([
                    'success' => false,
                    'message' => 'No hay tiempo activo para pausar'
                ]);
            }
            
            $nuevoEstado = $registro->estado === 'activo' ? 'pausado' : 'activo';
            $pausaInicio = $nuevoEstado === 'pausado' ? Carbon::now() : null;
            
            // Actualizar registro
            DB::table('tabla_registros_tiempo')
                ->where('id', $registro->id)
                ->update([
                    'estado' => $nuevoEstado,
                    'pausa_inicio' => $pausaInicio,
                    'updated_at' => Carbon::now()
                ]);
            
            // Registrar evento de pausa/reanudación
            DB::table('tabla_eventos_tiempo')->insert([
                'registro_id' => $registro->id,
                'tipo' => $nuevoEstado === 'pausado' ? 'pausa' : 'reanudacion',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => $nuevoEstado === 'pausado' ? 'Tiempo pausado' : 'Tiempo reanudado',
                'estado' => $nuevoEstado
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al pausar el tiempo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Detener el tiempo de trabajo
     */
    public function stopTiempo(Request $request, $id)
    {
        try {
            // Verificar que el empleado solo pueda operar sobre su propio tiempo
            if (Auth::user()->empleado->id != $id) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permiso para esta acción'
                ], 403);
            }

            $empleado = Auth::user()->empleado;
            
            // Obtener el registro activo
            $registro = DB::table('tabla_registros_tiempo')
                ->where('empleado_id', $empleado->id)
                ->whereNull('fin')
                ->whereDate('created_at', Carbon::today())
                ->first();
            
            if (!$registro) {
                return response()->json([
                    'success' => false,
                    'message' => 'No hay tiempo activo para detener'
                ]);
            }
            
            // Calcular tiempo total
            $inicio = Carbon::parse($registro->inicio);
            $fin = Carbon::now();
            
            // Calcular tiempo de pausas si existe
            $tiempoPausado = 0;
            if ($registro->pausa_inicio) {
                $pausaInicio = Carbon::parse($registro->pausa_inicio);
                $tiempoPausado = $fin->diffInSeconds($pausaInicio);
            }
            
            $tiempoTotal = $fin->diffInSeconds($inicio) - $tiempoPausado;
            
            // Actualizar registro
            DB::table('tabla_registros_tiempo')
                ->where('id', $registro->id)
                ->update([
                    'fin' => $fin,
                    'estado' => 'completado',
                    'tiempo_total' => $tiempoTotal,
                    'updated_at' => Carbon::now()
                ]);
            
            // Registrar evento de fin
            DB::table('tabla_eventos_tiempo')->insert([
                'registro_id' => $registro->id,
                'tipo' => 'fin',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Tiempo detenido correctamente',
                'tiempo_total' => $this->formatearTiempo($tiempoTotal),
                'fin' => $fin->toDateTimeString()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al detener el tiempo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener estado actual del tiempo
     */
    public function getEstado(Request $request, $id)
    {
        try {
            $user = Auth::user();
            
            $empleado = DB::table('tabla_empleados')
                ->where('id', $id)
                ->where('credencial_id', $user->id)
                ->first();

            if (!$empleado) {
                return response()->json([
                    'activo' => false,
                    'estado' => 'no_autorizado'
                ], 403);
            }
            
            $registro = DB::table('tabla_registros_tiempo')
                ->where('empleado_id', $empleado->id)
                ->whereNull('fin')
                ->whereDate('created_at', Carbon::today())
                ->first();
            
            if (!$registro) {
                return response()->json([
                    'activo' => false,
                    'estado' => 'inactivo'
                ]);
            }
            
            return response()->json([
                'activo' => true,
                'estado' => $registro->estado,
                'inicio' => $registro->inicio
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'activo' => false,
                'estado' => 'error'
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

        $registrosMes = DB::table('tabla_registros_tiempo')
            ->where('empleado_id', $empleadoId)
            ->whereBetween('created_at', [$inicioMes, $finMes])
            ->whereNotNull('tiempo_total')
            ->get();

        $totalHoras = $registrosMes->sum('tiempo_total') / 3600;
        $totalRegistros = $registrosMes->count();
        $diasLaborables = Carbon::now()->daysInMonth;

        return [
            'total_horas' => number_format($totalHoras, 2),
            'total_registros' => $totalRegistros,
            'promedio_horas' => $diasLaborables > 0 ? number_format($totalHoras / $diasLaborables, 2) : '0.00'
        ];
    }

    /**
     * Formatear tiempo en segundos a HH:MM:SS
     */
    private function formatearTiempo($segundos)
    {
        $horas = floor($segundos / 3600);
        $minutos = floor(($segundos % 3600) / 60);
        $segundos = $segundos % 60;
        
        return sprintf('%02d:%02d:%02d', $horas, $minutos, $segundos);
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
            ->select(['id', 'inicio', 'fin', 'tiempo_total', 'estado', 'created_at']);

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

        // Obtener el total de registros
        $recordsTotal = $query->count();
        $data = $query->orderBy('created_at', 'desc')->get();

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
 * Obtener resumen del período
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
            ->where('empleado_id', $empleado->id)
            ->whereNotNull('tiempo_total');

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

        $registros = $query->get();
        $totalHoras = $registros->sum('tiempo_total') / 3600;
        $totalRegistros = $registros->count();
        $diasTrabajados = $registros->unique('created_at')->count();

        return response()->json([
            'total_horas' => number_format($totalHoras, 2),
            'total_registros' => $totalRegistros,
            'promedio_diario' => $diasTrabajados > 0 ? number_format($totalHoras / $diasTrabajados, 2) : '0.00',
            'dias_trabajados' => $diasTrabajados
        ]);

    } catch (\Exception $e) {
        \Log::error('Error en resumen período', ['error' => $e->getMessage()]);
        return response()->json([
            'total_horas' => '0.00',
            'total_registros' => 0,
            'promedio_diario' => '0.00',
            'dias_trabajados' => 0
        ]);
    }
}

}