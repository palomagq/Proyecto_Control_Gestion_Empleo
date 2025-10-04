<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Empleado; 
use App\Models\Credencial; 
use App\Models\Rol;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use Yajra\DataTables\Facades\DataTables;
use Datetime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Exports\EmpleadosMesExport;

class AdminController extends Controller
{
    //


    public function dashboard(){
        return view('admin.dashboard');
    }
    
    public function empleados(){
        return view('admin.sections.empleados');
    }

public function storeEmployee(Request $request)
{
    Log::info('📥 Datos recibidos para crear empleado:', $request->all());

    try {
        // Calcular fecha mínima (16 años atrás desde hoy)
        $fechaMinima = now()->subYears(16)->format('Y-m-d');

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'dni' => 'required|unique:tabla_empleados,dni|regex:/^[0-9]{8}[A-Za-z]$/',
            'fecha_nacimiento' => 'required|date|before_or_equal:' . $fechaMinima,
            'domicilio' => 'required|string|max:500',
            'telefono' => 'required|string|max:9|regex:/^[+]?[0-9\s\-]+$/',
            'username' => 'required|unique:tabla_credenciales,username',
            'password' => 'required|string|min:4|max:4|confirmed',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
        ], [
            'fecha_nacimiento.before_or_equal' => 'El empleado debe tener al menos 16 años de edad.',
            'password.min' => 'La contraseña debe tener exactamente 4 dígitos.',
            'password.max' => 'La contraseña debe tener exactamente 4 dígitos.',
            'dni.unique' => 'El DNI ya existe en el sistema.',
            'dni.regex' => 'El formato del DNI es inválido. Debe ser 8 números seguidos de 1 letra.',
            'username.unique' => 'El nombre de usuario ya está en uso.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'nombre.required' => 'El campo nombre es obligatorio.',
            'telefono.required' => 'El campo teléfono es obligatorio.',
            'telefono.regex' => 'El formato del teléfono es inválido. Use formato internacional: +34 612 345 678',
        ]);

        Log::info('✅ Validación pasada:', $validated);

        // Validación adicional: Verificar que la contraseña tenga exactamente 4 dígitos numéricos
        if (!preg_match('/^\d{4}$/', $validated['password'])) {
            Log::error('❌ Contraseña inválida:', ['password' => $validated['password']]);
            return response()->json([
                'success' => false,
                'message' => 'La contraseña debe contener exactamente 4 dígitos numéricos.'
            ], 422);
        }

        // Validar que password y confirmación coincidan
        if ($validated['password'] !== $request->password_confirmation) {
            return response()->json([
                'success' => false,
                'message' => 'La contraseña y su confirmación no coinciden.'
            ], 422);
        }

        // Validación adicional de edad (doble verificación)
        $fechaNacimiento = \Carbon\Carbon::parse($validated['fecha_nacimiento']);
        $edad = $fechaNacimiento->diffInYears(now());
        
        Log::info('📅 Cálculo de edad:', [
            'fecha_nacimiento' => $validated['fecha_nacimiento'],
            'edad_calculada' => $edad,
            'fecha_minima' => $fechaMinima
        ]);

        if ($edad < 16) {
            return response()->json([
                'success' => false,
                'message' => 'El empleado debe tener al menos 16 años de edad. Edad calculada: ' . $edad . ' años'
            ], 422);
        }

        // Validar letra del DNI
        $dni = strtoupper($validated['dni']);
        $numero = substr($dni, 0, 8);
        $letra = substr($dni, 8, 1);
        $letrasValidas = 'TRWAGMYFPDXBNJZSQVHLCKE';
        $letraCalculada = $letrasValidas[$numero % 23];

        if ($letra !== $letraCalculada) {
            return response()->json([
                'success' => false,
                'message' => 'La letra del DNI es incorrecta. La letra debería ser: ' . $letraCalculada
            ], 422);
        }

        // ✅ Obtener el rol_id para empleado desde tabla_roles
        $rolEmpleado = DB::table('tabla_roles')->where('nombre', 'empleado')->first();
        
        if (!$rolEmpleado) {
            // Si no existe el rol "empleado", buscar cualquier rol disponible
            $cualquierRol = DB::table('tabla_roles')->first();
            if ($cualquierRol) {
                $rolId = $cualquierRol->id;
                Log::warning('⚠️ Rol "empleado" no encontrado, usando primer rol disponible:', [
                    'rol_id' => $rolId, 
                    'rol_nombre' => $cualquierRol->nombre
                ]);
            } else {
                // Si no hay roles en la tabla, usar valor por defecto común para empleados
                $rolId = 2;
                Log::warning('⚠️ No hay roles en la tabla tabla_roles, usando valor por defecto:', [
                    'rol_id' => $rolId
                ]);
            }
        } else {
            $rolId = $rolEmpleado->id;
            Log::info('✅ Rol encontrado:', [
                'rol_id' => $rolId, 
                'rol_nombre' => $rolEmpleado->nombre
            ]);
        }

        // Iniciar transacción para asegurar consistencia de datos
        DB::beginTransaction();

        try {
            Log::info('🔄 Iniciando creación de empleado en transacción...');

            // **PRIMERO: Generar y guardar el QR**
            $qrData = $this->generarQR($dni, $validated['nombre'] . ' ' . $validated['apellidos']);
            $qr = \App\Models\Qr::create([
                'imagen_qr' => $qrData['imagen'],
                'codigo_unico' => $qrData['codigo_unico']
            ]);

            Log::info('✅ QR generado y guardado:', ['qr_id' => $qr->id]);

            // **SEGUNDO: Crear la credencial CON rol_id**
            $credencial = Credencial::create([
                'username' => $validated['username'],
                'password' => bcrypt($validated['password']),
                'rol_id' => $rolId,
                // empleado_id se actualizará después
            ]);

            Log::info('✅ Credencial creada:', [
                'credencial_id' => $credencial->id, 
                'rol_id' => $rolId,
                'username' => $validated['username']
            ]);

            // **TERCERO: Crear el empleado con el credencial_id, rol_id Y qr_id**
            $empleado = Empleado::create([
                'nombre' => $validated['nombre'],
                'apellidos' => $validated['apellidos'],
                'dni' => $dni,
                'fecha_nacimiento' => $validated['fecha_nacimiento'],
                'telefono' => $validated['telefono'], // NUEVO CAMPO
                'domicilio' => $validated['domicilio'],
                'latitud' => $validated['latitud'] ?? '40.4168',
                'longitud' => $validated['longitud'] ?? '-3.7038',
                'credencial_id' => $credencial->id,
                'qr_id' => $qr->id, // NUEVO CAMPO
                'rol_id' => $rolId,
            ]);

            Log::info('✅ Empleado creado:', [
                'empleado_id' => $empleado->id,
                'nombre' => $empleado->nombre,
                'dni' => $empleado->dni,
                'telefono' => $empleado->telefono,
                'qr_id' => $empleado->qr_id,
                'rol_id' => $rolId
            ]);

            // **ACTUALIZAR la credencial con el empleado_id**
            $credencial->update([
                'empleado_id' => $empleado->id,
            ]);

            Log::info('✅ Credencial actualizada con empleado_id:', [
                'credencial_id' => $credencial->id,
                'empleado_id' => $empleado->id
            ]);

            // Confirmar transacción
            DB::commit();

            Log::info('🎉 Empleado creado exitosamente con QR', [
                'empleado_id' => $empleado->id,
                'username' => $validated['username'],
                'rol_id' => $rolId,
                'edad' => $edad,
                'qr_id' => $qr->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Empleado creado exitosamente',
                'data' => [
                    'empleado_id' => $empleado->id,
                    'username' => $validated['username'],
                    'password' => $validated['password'],
                    'edad' => $edad,
                    'rol_id' => $rolId,
                    'qr_id' => $qr->id,
                    'qr_image' => base64_encode($qrData['imagen']) // Para mostrar en frontend
                ]
            ]);

        } catch (\Exception $e) {
            // Revertir transacción en caso de error
            DB::rollBack();
            Log::error('❌ Error en transacción al crear empleado:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error en transacción al crear empleado: ' . $e->getMessage()
            ], 500);
        }

    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::error('❌ Error de validación:', $e->errors());
        return response()->json([
            'success' => false,
            'message' => 'Errores de validación',
            'errors' => $e->errors()
        ], 422);
        
    } catch (\Exception $e) {
        Log::error('❌ Error general al crear empleado:', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
        return response()->json([
            'success' => false,
            'message' => 'Error interno del servidor: ' . $e->getMessage()
        ], 500);
    }
}

// En el método getEmpleadosDataTable, agrega logs:
public function getEmpleadosDataTable(Request $request)
{
    \Log::info('📊 Datatable request recibida:', $request->all());
    
    try {
        // Consulta base con todos los empleados
        $query = Empleado::with('credencial')->select('*');

        \Log::info('🔍 Consulta base creada');

        // **OBTENER FILTROS**
        $filterDni = $request->get('filterDni', '');
        $filterNombre = $request->get('filterNombre', '');
        $filterMes = $request->get('filterMes', '');

        \Log::info('🎯 Filtros recibidos:', [
            'dni' => $filterDni,
            'nombre' => $filterNombre,
            'mes' => $filterMes
        ]);

        // ✅ APLICAR FILTROS SI ESTÁN PRESENTES
        if (!empty($filterDni)) {
            $query->where('dni', 'like', '%' . $filterDni . '%');
            \Log::info('🔍 Filtro DNI aplicado:', ['dni' => $filterDni]);
        }

        if (!empty($filterNombre)) {
            $query->where(function($q) use ($filterNombre) {
                $q->where('nombre', 'like', '%' . $filterNombre . '%')
                  ->orWhere('apellidos', 'like', '%' . $filterNombre . '%');
            });
            \Log::info('🔍 Filtro Nombre aplicado:', ['nombre' => $filterNombre]);
        }

        if (!empty($filterMes)) {
            try {
                // Validar y convertir el formato del mes
                if (preg_match('/^\d{4}-\d{2}$/', $filterMes)) {
                    $fechaInicio = Carbon::createFromFormat('Y-m', $filterMes)->startOfMonth();
                    $fechaFin = Carbon::createFromFormat('Y-m', $filterMes)->endOfMonth();
                    
                    $query->whereBetween('created_at', [$fechaInicio, $fechaFin]);
                    
                    \Log::info('📅 Filtro Mes aplicado:', [
                        'mes' => $filterMes,
                        'fecha_inicio' => $fechaInicio->format('Y-m-d H:i:s'),
                        'fecha_fin' => $fechaFin->format('Y-m-d H:i:s')
                    ]);
                } else {
                    \Log::warning('⚠️ Formato de mes inválido:', ['mes' => $filterMes]);
                }
            } catch (\Exception $e) {
                \Log::error('❌ Error procesando filtro de mes:', [
                    'mes' => $filterMes,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Obtener TODOS los registros (sin paginación para client-side)
        $empleados = $query->orderBy('id', 'asc')->get();

        \Log::info('📋 Total de empleados encontrados:', ['count' => $empleados->count()]);

        $data = $empleados->map(function($empleado) {
            $edad = Carbon::parse($empleado->fecha_nacimiento)->age;

            return [
                'id' => $empleado->id,
                'dni' => $empleado->dni,
                'nombre' => $empleado->nombre,
                'apellidos' => $empleado->apellidos,
                'fecha_nacimiento' => Carbon::parse($empleado->fecha_nacimiento)->format('d/m/Y'),
                'edad' => $edad . ' años',
                'domicilio' => $empleado->domicilio,
                'telefono' => $empleado->telefono,
                'username' => $empleado->credencial->username ?? 'N/A',
                'acciones' => '
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-info" onclick="verEmpleado(' . $empleado->id . ')" title="Ver">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-warning" onclick="editarEmpleado(' . $empleado->id . ')" title="Editar">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger" onclick="eliminarEmpleado(' . $empleado->id . ')" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                '
            ];
        });

        $response = [
            'draw' => $request->get('draw', 1),
            'recordsTotal' => $empleados->count(),
            'recordsFiltered' => $empleados->count(),
            'data' => $data
        ];

        \Log::info('✅ Respuesta DataTable generada', [
            'draw' => $response['draw'],
            'recordsTotal' => $response['recordsTotal'],
            'recordsFiltered' => $response['recordsFiltered'],
            'data_count' => count($response['data'])
        ]);

        return response()->json($response);

    } catch (\Exception $e) {
        \Log::error('❌ Error en datatable empleados:', [
            'error' => $e->getMessage(), 
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'draw' => $request->get('draw', 1),
            'recordsTotal' => 0,
            'recordsFiltered' => 0,
            'data' => [],
            'error' => 'Error interno del servidor: ' . $e->getMessage()
        ], 500);
    }
}



    // Método para buscar empleado por DNI
    public function buscarPorDni($dni)
    {
        try {
            $empleado = Empleado::where('dni', strtoupper($dni))->first();
            
            return response()->json([
                'exists' => !is_null($empleado),
                'empleado' => $empleado ? [
                    'id' => $empleado->id,
                    'nombre' => $empleado->nombre,
                    'apellidos' => $empleado->apellidos,
                    'dni' => $empleado->dni,
                    'username' => $empleado->credencial->username ?? 'No disponible',
                    'created_at' => $empleado->created_at->format('d/m/Y H:i')
                ] : null
            ]);
        } catch (\Exception $e) {
            \Log::error('Error buscando empleado por DNI:', ['dni' => $dni, 'error' => $e->getMessage()]);
            return response()->json([
                'exists' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Método para verificar username
    public function verificarUsername($username)
    {
        try {
            $exists = Credencial::where('username', $username)->exists();
            
            return response()->json([
                'exists' => $exists
            ]);
        } catch (\Exception $e) {
            \Log::error('Error verificando username:', ['username' => $username, 'error' => $e->getMessage()]);
            return response()->json([
                'exists' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Agrega este método para las estadísticas
public function getStats()
{
    try {
        // Total de empleados
        $total = Empleado::count();
        
        // Registros del mes actual
        $registrosMes = Empleado::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();
        
        // Promedio de edad
        $empleados = Empleado::all();
        $sumaEdades = 0;
        $contador = 0;
        
        foreach ($empleados as $empleado) {
            $edad = \Carbon\Carbon::parse($empleado->fecha_nacimiento)->age;
            $sumaEdades += $edad;
            $contador++;
        }
        
        $promedioEdad = $contador > 0 ? round($sumaEdades / $contador, 1) : 0;

        \Log::info('📊 Estadísticas calculadas:', [
            'total' => $total,
            'registros_mes' => $registrosMes,
            'promedio_edad' => $promedioEdad
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'total' => $total,
                'registros_mes' => $registrosMes,
                'promedio_edad' => $promedioEdad
            ]
        ]);
        
    } catch (\Exception $e) {
        \Log::error('❌ Error obteniendo estadísticas:', ['error' => $e->getMessage()]);
        return response()->json([
            'success' => false,
            'data' => [
                'total' => 0,
                'registros_mes' => 0,
                'promedio_edad' => 0
            ],
            'error' => $e->getMessage()
        ]);
    }
}

// Método para obtener datos del empleado para editar
public function editEmployee($id)
{
    try {
        $empleado = Empleado::with('credencial')->findOrFail($id);
        
        $data = [
            'id' => $empleado->id,
            'nombre' => $empleado->nombre,
            'apellidos' => $empleado->apellidos,
            'dni' => $empleado->dni,
            'fecha_nacimiento' => $empleado->fecha_nacimiento,
            'fecha_nacimiento_formatted' => \Carbon\Carbon::parse($empleado->fecha_nacimiento)->format('d/m/Y'),
            'domicilio' => $empleado->domicilio,
            'latitud' => $empleado->latitud,
            'longitud' => $empleado->longitud,
            'username' => $empleado->credencial->username ?? 'N/A',
            'edad' => \Carbon\Carbon::parse($empleado->fecha_nacimiento)->age
        ];

        return response()->json([
            'success' => true,
            'data' => $data
        ]);

    } catch (\Exception $e) {
        \Log::error('Error editando empleado:', ['id' => $id, 'error' => $e->getMessage()]);
        return response()->json([
            'success' => false,
            'message' => 'Empleado no encontrado'
        ], 404);
    }
}

// Método para actualizar empleado
public function updateEmployee(Request $request, $id)
{
    try {
        $empleado = Empleado::findOrFail($id);

        $validated = $request->validate([
            'domicilio' => 'required|string|max:500',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
        ]);

        $empleado->update($validated);

        \Log::info('Empleado actualizado:', ['id' => $id, 'domicilio' => $validated['domicilio']]);

        return response()->json([
            'success' => true,
            'message' => 'Empleado actualizado correctamente'
        ]);

    } catch (\Exception $e) {
        \Log::error('Error actualizando empleado:', ['id' => $id, 'error' => $e->getMessage()]);
        return response()->json([
            'success' => false,
            'message' => 'Error al actualizar empleado: ' . $e->getMessage()
        ], 500);
    }
}

// Método para obtener datos de un empleado específico
public function show($id)
{
    try {
        $empleado = Empleado::with('credencial')->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $empleado->id,
                'nombre' => $empleado->nombre,
                'apellidos' => $empleado->apellidos,
                'dni' => $empleado->dni,
                'fecha_nacimiento' => $empleado->fecha_nacimiento,
                'fecha_nacimiento_formatted' => \Carbon\Carbon::parse($empleado->fecha_nacimiento)->format('d/m/Y'),
                'edad' => \Carbon\Carbon::parse($empleado->fecha_nacimiento)->age,
                'domicilio' => $empleado->domicilio,
                'latitud' => $empleado->latitud,
                'longitud' => $empleado->longitud,
                'username' => $empleado->credencial->username,
                'created_at' => $empleado->created_at,
                'updated_at' => $empleado->updated_at,
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al cargar el empleado: ' . $e->getMessage()
        ], 404);
    }
}

// Método para eliminar empleado (ya existe, pero asegúrate de que esté así)
public function destroyEmployee($id)
{
    try {
        DB::beginTransaction();

        $empleado = Empleado::findOrFail($id);
        
        // Eliminar la credencial asociada si existe
        if ($empleado->credencial) {
            $empleado->credencial->delete();
        }
        
        // Eliminar el empleado
        $empleado->delete();

        DB::commit();

        \Log::info('Empleado eliminado:', ['id' => $id, 'nombre' => $empleado->nombre]);

        return response()->json([
            'success' => true,
            'message' => 'Empleado eliminado correctamente'
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Error eliminando empleado:', ['id' => $id, 'error' => $e->getMessage()]);
        return response()->json([
            'success' => false,
            'message' => 'Error al eliminar empleado: ' . $e->getMessage()
        ], 500);
    }
}
    
public function exportarExcelMes(Request $request)
{
    try {
        \Log::info('📤 INICIANDO EXPORTACIÓN EXCEL', [
            'mes' => $request->mes,
            'año' => $request->año,
            'todos_los_parametros' => $request->all()
        ]);

        // Validación más flexible
        $validator = \Validator::make($request->all(), [
            'mes' => 'required|integer|between:1,12',
            'año' => 'required|integer|min:2020|max:' . (date('Y') + 1)
        ]);

        if ($validator->fails()) {
            \Log::error('❌ Validación fallida:', $validator->errors()->toArray());
            return response()->json([
                'success' => false,
                'message' => 'Datos inválidos: ' . implode(', ', $validator->errors()->all())
            ], 422);
        }

        $mes = (int) $request->mes;
        $año = (int) $request->año;

        \Log::info('🔍 Parámetros procesados:', ['mes' => $mes, 'año' => $año]);

        // ✅ **DEBUG: Ver TODOS los empleados en el sistema**
<<<<<<< HEAD
    /*    $todosEmpleados = Empleado::with('credencial')
=======
        $todosEmpleados = Empleado::with('credencial')
>>>>>>> db47f97ca6491ce026d72a79284a0d57d54ea54c
            ->select('id', 'dni', 'nombre', 'apellidos', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($emp) {
                return [
                    'id' => $emp->id,
                    'dni' => $emp->dni,
                    'nombre_completo' => $emp->nombre . ' ' . $emp->apellidos,
                    'fecha_registro' => $emp->created_at->format('Y-m-d H:i:s'),
                    'mes_registro' => $emp->created_at->month,
                    'año_registro' => $emp->created_at->year,
                    'username' => $emp->credencial->username ?? 'N/A'
                ];
            });

        \Log::info('📊 EMPLEADOS EN SISTEMA:', [
            'total_empleados' => $todosEmpleados->count(),
            'empleados' => $todosEmpleados->toArray()
<<<<<<< HEAD
        ]);*/
=======
        ]);
>>>>>>> db47f97ca6491ce026d72a79284a0d57d54ea54c

        // ✅ **BUSCAR empleados del mes/año específico**
        $empleadosFiltrados = Empleado::with('credencial')
            ->whereYear('created_at', $año)
            ->whereMonth('created_at', $mes)
            ->orderBy('created_at', 'desc')
            ->get();

        \Log::info('🎯 RESULTADO BÚSQUEDA FILTRADA:', [
            'mes_buscado' => $mes,
            'año_buscado' => $año,
            'total_encontrados' => $empleadosFiltrados->count(),
            'empleados_encontrados' => $empleadosFiltrados->map(function($emp) {
                return [
                    'id' => $emp->id,
                    'dni' => $emp->dni,
                    'nombre' => $emp->nombre,
                    'fecha_registro' => $emp->created_at->format('Y-m-d H:i:s')
                ];
            })->toArray()
        ]);

        if ($empleadosFiltrados->count() === 0) {
            \Log::warning('⚠️ NO HAY EMPLEADOS PARA EXPORTAR', [
                'mes' => $mes,
                'año' => $año,
                'sugerencia' => 'Verificar que las fechas de created_at coincidan con el mes y año'
            ]);

            return response()->json([
                'success' => false,
                'message' => 'No hay empleados registrados en ' . $this->getNombreMes($mes) . ' de ' . $año . 
                            '. Total de empleados en sistema: ' . $todosEmpleados->count() .
                            '. Pruebe con otro mes o año.'
            ], 404);
        }

        $nombreArchivo = 'empleados_' . $this->getNombreMesCorto($mes) . '_' . $año . '.xlsx';

        \Log::info('✅ GENERANDO ARCHIVO EXCEL', [
            'nombre_archivo' => $nombreArchivo,
            'total_empleados' => $empleadosFiltrados->count(),
            'primeros_5' => $empleadosFiltrados->take(5)->pluck('dni', 'nombre')->toArray()
        ]);

        return Excel::download(new EmpleadosMesExport($mes, $año), $nombreArchivo);

    } catch (\Exception $e) {
        \Log::error('💥 ERROR CRÍTICO EN EXPORTACIÓN:', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'request' => $request->all()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Error crítico al generar el archivo: ' . $e->getMessage()
        ], 500);
    }
}

// Método auxiliar para nombre de mes corto
private function getNombreMesCorto($mes)
{
    $meses = [
        1 => 'ene', 2 => 'feb', 3 => 'mar', 4 => 'abr',
        5 => 'may', 6 => 'jun', 7 => 'jul', 8 => 'ago',
        9 => 'sep', 10 => 'oct', 11 => 'nov', 12 => 'dic'
    ];
    return $meses[$mes] ?? 'mes';
}

// Método auxiliar para obtener nombre del mes
private function getNombreMes($mes)
{
    $meses = [
        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
        5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
        9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
    ];
    return $meses[$mes] ?? 'Mes';
}

public function verificarDatosMes(Request $request)
{
    try {
        $mes = $request->mes;
        $año = $request->año;
        
        $fechaInicio = Carbon::create($año, $mes, 1)->startOfMonth();
        $fechaFin = Carbon::create($año, $mes, 1)->endOfMonth();
        
        $existenDatos = Empleado::whereBetween('created_at', [$fechaInicio, $fechaFin])->exists();
        
        return response()->json([
            'existenDatos' => $existenDatos,
            'mes' => $mes,
            'año' => $año
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'existenDatos' => false,
            'error' => $e->getMessage()
        ]);
    }
}

<<<<<<< HEAD
// Método para generar QR
private function generarQR($dni, $nombreCompleto)
{
    try {
        // Generar código único para el QR
        $codigoUnico = 'EMP_' . $dni . '_' . time();
        
        // Datos que se incluirán en el QR
        $qrData = [
            'empleado_dni' => $dni,
            'empleado_nombre' => $nombreCompleto,
            'codigo_unico' => $codigoUnico,
            'fecha_generacion' => now()->toISOString()
        ];
        
        $qrContent = json_encode($qrData);
        
        // Verificar si la librería Simple QrCode está disponible
        if (class_exists('SimpleSoftwareIO\QrCode\Facades\QrCode')) {
            // Generar QR usando la librería
            $qrImage = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')
                ->size(300)
                ->generate($qrContent);
        } else {
            // Fallback: generar QR simple usando GD
            Log::warning('⚠️ Librería QR no disponible, usando fallback GD');
            $qrImage = $this->generarQRFallback($qrContent, $dni);
        }
        
        return [
            'imagen' => $qrImage,
            'codigo_unico' => $codigoUnico
        ];
        
    } catch (\Exception $e) {
        Log::error('❌ Error generando QR:', ['error' => $e->getMessage()]);
        
        // QR por defecto en caso de error
        return [
            'imagen' => $this->generarQRPorDefecto($dni),
            'codigo_unico' => 'EMP_' . $dni . '_' . time()
        ];
    }
}

// Método fallback para generar QR con GD
private function generarQRFallback($content, $dni)
{
    try {
        // Crear una imagen simple como fallback
        $width = 300;
        $height = 300;
        
        $image = imagecreate($width, $height);
        
        // Colores
        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 0, 0, 0);
        
        // Fondo blanco
        imagefill($image, 0, 0, $white);
        
        // Texto simple
        $text = "EMP: " . substr($dni, 0, 8);
        $font = 5; // Fuente built-in
        $textWidth = imagefontwidth($font) * strlen($text);
        $textHeight = imagefontheight($font);
        
        $x = ($width - $textWidth) / 2;
        $y = ($height - $textHeight) / 2;
        
        imagestring($image, $font, $x, $y, $text, $black);
        
        // Capturar la imagen como string
        ob_start();
        imagepng($image);
        $imageData = ob_get_clean();
        imagedestroy($image);
        
        return $imageData;
        
    } catch (\Exception $e) {
        Log::error('❌ Error en fallback QR:', ['error' => $e->getMessage()]);
        return ''; // QR vacío
    }
}

// Método para generar QR por defecto
private function generarQRPorDefecto($dni)
{
    try {
        $width = 300;
        $height = 300;
        
        $image = imagecreate($width, $height);
        
        // Colores
        $white = imagecolorallocate($image, 255, 255, 255);
        $blue = imagecolorallocate($image, 0, 0, 255);
        
        // Fondo blanco
        imagefill($image, 0, 0, $white);
        
        // Texto por defecto
        $text = "EMPLEADO: " . $dni;
        $font = 5;
        
        $textWidth = imagefontwidth($font) * strlen($text);
        $textHeight = imagefontheight($font);
        
        $x = ($width - $textWidth) / 2;
        $y = ($height - $textHeight) / 2;
        
        imagestring($image, $font, $x, $y, $text, $blue);
        
        // Capturar la imagen
        ob_start();
        imagepng($image);
        $imageData = ob_get_clean();
        imagedestroy($image);
        
        return $imageData;
        
    } catch (\Exception $e) {
        Log::error('❌ Error generando QR por defecto:', ['error' => $e->getMessage()]);
        return '';
    }
}

=======
>>>>>>> db47f97ca6491ce026d72a79284a0d57d54ea54c
}
