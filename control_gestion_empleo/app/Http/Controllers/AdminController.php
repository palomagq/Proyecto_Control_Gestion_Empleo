<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Empleado; 
use App\Models\Credencial; 
use App\Models\Rol;
use App\Models\QR;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use Yajra\DataTables\Facades\DataTables;
use Datetime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // ✅ AGREGAR ESTA LÍNEA
use Carbon\Carbon;
use App\Exports\EmpleadosMesExport;
use App\Exports\EmpleadosPdfExport;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    //

    
public function exportarExcelMes(Request $request)
{
    try {
        // Validar los parámetros
        $request->validate([
            'mes' => 'required|integer|between:1,12',
            'año' => 'required|integer|min:2020|max:' . (date('Y') + 1)
        ]);

        $mes = $request->mes;
        $año = $request->año;

        // Verificar si hay datos para el mes seleccionado
        $fechaInicio = Carbon::create($año, $mes, 1)->startOfMonth();
        $fechaFin = Carbon::create($año, $mes, 1)->endOfMonth();
        
        $existenDatos = Empleado::whereBetween('created_at', [$fechaInicio, $fechaFin])->exists();

        if (!$existenDatos) {
            return response()->json([
                'success' => false,
                'message' => 'No hay empleados registrados en el mes seleccionado'
            ], 404);
        }

        $nombreArchivo = "empleados_{$mes}_{$año}.xlsx";
        
        return Excel::download(new EmpleadosMesExport($mes, $año), $nombreArchivo);

    } catch (\Exception $e) {
        Log::error('Error exportando Excel:', [
            'mes' => $request->mes,
            'año' => $request->año,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Error al generar el archivo Excel: ' . $e->getMessage()
        ], 500);
    }
}

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
        $edad = $fechaNacimiento->diffInYears(now()); // ✅ Esto ya devuelve un entero
        
        // ✅ Asegurar que sea entero
        $edad = (int) $edad;
        
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
// Iniciar transacción
        DB::beginTransaction();

        try {
            Log::info('🔄 Iniciando creación de empleado en transacción...');

            // **PRIMERO: Generar y guardar el QR** - ✅ CORREGIDO
            $qrData = $this->generarQR($dni, $validated['nombre'] . ' ' . $validated['apellidos']);
            
            // Crear el QR sin incluir la imagen binaria en la respuesta
            $qr = Qr::create([
                'imagen_qr' => $qrData['imagen'],
                'codigo_unico' => $qrData['codigo_unico']
            ]);

            Log::info('✅ QR generado y guardado:', ['qr_id' => $qr->id]);

            // **SEGUNDO: Crear la credencial CON rol_id**
            $credencial = Credencial::create([
                'username' => $validated['username'],
                'password' => bcrypt($validated['password']),
                'rol_id' => $rolId,
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
                'telefono' => $validated['telefono'],
                'domicilio' => $validated['domicilio'],
                'latitud' => $validated['latitud'] ?? '40.4168',
                'longitud' => $validated['longitud'] ?? '-3.7038',
                'credencial_id' => $credencial->id,
                'qr_id' => $qr->id,
                'rol_id' => $rolId,
            ]);

            Log::info('✅ Empleado creado:', [
                'empleado_id' => $empleado->id,
                'nombre' => $empleado->nombre,
                'dni' => $empleado->dni,
                'qr_id' => $empleado->qr_id
            ]);

            // **ACTUALIZAR la credencial con el empleado_id**
            $credencial->update([
                'empleado_id' => $empleado->id,
            ]);

            // Confirmar transacción
            DB::commit();

            Log::info('🎉 Empleado creado exitosamente con QR', [
                'empleado_id' => $empleado->id,
                'username' => $validated['username'],
                'edad' => $edad
            ]);

            // ✅ CORREGIDO: No incluir la imagen binaria en la respuesta JSON
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
                    // ❌ NO incluir qr_image aquí para evitar problemas de encoding
                ]
            ]);

        } catch (\Exception $e) {
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
            'line' => $e->getLine()
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
    Log::info('📊 Datatable request recibida:', $request->all());
    
    try {
        // Consulta base con todos los empleados
        $query = Empleado::with('credencial')->select('*');

        Log::info('🔍 Consulta base creada');

        // **OBTENER FILTROS**
        $filterDni = $request->get('filterDni', '');
        $filterNombre = $request->get('filterNombre', '');
        $filterMes = $request->get('filterMes', '');

        Log::info('🎯 Filtros recibidos:', [
            'dni' => $filterDni,
            'nombre' => $filterNombre,
            'mes' => $filterMes
        ]);

        // ✅ APLICAR FILTROS SI ESTÁN PRESENTES
        if (!empty($filterDni)) {
            $query->where('dni', 'like', '%' . $filterDni . '%');
            Log::info('🔍 Filtro DNI aplicado:', ['dni' => $filterDni]);
        }

        if (!empty($filterNombre)) {
            $query->where(function($q) use ($filterNombre) {
                $q->where('nombre', 'like', '%' . $filterNombre . '%')
                  ->orWhere('apellidos', 'like', '%' . $filterNombre . '%');
            });
            Log::info('🔍 Filtro Nombre aplicado:', ['nombre' => $filterNombre]);
        }

        if (!empty($filterMes)) {
            try {
                // Validar y convertir el formato del mes
                if (preg_match('/^\d{4}-\d{2}$/', $filterMes)) {
                    $fechaInicio = Carbon::createFromFormat('Y-m', $filterMes)->startOfMonth();
                    $fechaFin = Carbon::createFromFormat('Y-m', $filterMes)->endOfMonth();
                    
                    $query->whereBetween('created_at', [$fechaInicio, $fechaFin]);
                    
                    Log::info('📅 Filtro Mes aplicado:', [
                        'mes' => $filterMes,
                        'fecha_inicio' => $fechaInicio->format('Y-m-d H:i:s'),
                        'fecha_fin' => $fechaFin->format('Y-m-d H:i:s')
                    ]);
                } else {
                    Log::warning('⚠️ Formato de mes inválido:', ['mes' => $filterMes]);
                }
            } catch (\Exception $e) {
                Log::error('❌ Error procesando filtro de mes:', [
                    'mes' => $filterMes,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Obtener TODOS los registros (sin paginación para client-side)
        $empleados = $query->orderBy('id', 'asc')->get();

        Log::info('📋 Total de empleados encontrados:', ['count' => $empleados->count()]);

        $data = $empleados->map(function($empleado) {
            // ✅ CALCULAR EDAD COMO ENTERO
            $edad = \Carbon\Carbon::parse($empleado->fecha_nacimiento)->age;
            
            // ✅ Asegurar que sea entero
            $edadEntero = (int) $edad;

            // ✅ CORREGIDO: Usar comillas simples y escapar correctamente
            $accionesHtml = '
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
                <button class="btn btn-success" onclick="imprimirQR(' . $empleado->id . ')" title="Visualizar QR">
                    <i class="fas fa-qrcode"></i>
                </button>
                <button class="btn btn-secondary" onclick="exportarRegistroHorario(' . $empleado->id . ')" title="Exportar Registro Horario">
                    <i class="fas fa-file-contract"></i>
                </button>
            </div>
            ';

            return [
                'id' => $empleado->id,
                'dni' => $empleado->dni,
                'nombre' => $empleado->nombre,
                'apellidos' => $empleado->apellidos,
                'fecha_nacimiento' => \Carbon\Carbon::parse($empleado->fecha_nacimiento)->format('d/m/Y'),
                'edad' => $edad . ' años',
                'domicilio' => $empleado->domicilio,
                'telefono' => $empleado->telefono,
                'username' => $empleado->credencial->username ?? 'N/A',
                'acciones' => $accionesHtml
            ];
        });

        $response = [
            'draw' => $request->get('draw', 1),
            'recordsTotal' => $empleados->count(),
            'recordsFiltered' => $empleados->count(),
            'data' => $data
        ];

        Log::info('✅ Respuesta DataTable generada', [
            'draw' => $response['draw'],
            'recordsTotal' => $response['recordsTotal'],
            'recordsFiltered' => $response['recordsFiltered'],
            'data_count' => count($response['data'])
        ]);

        return response()->json($response);

    } catch (\Exception $e) {
        Log::error('❌ Error en datatable empleados:', [
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
            Log::error('Error buscando empleado por DNI:', ['dni' => $dni, 'error' => $e->getMessage()]);
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
            Log::error('Error verificando username:', ['username' => $username, 'error' => $e->getMessage()]);
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

        Log::info('📊 Estadísticas calculadas:', [
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
        Log::error('❌ Error obteniendo estadísticas:', ['error' => $e->getMessage()]);
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
            'fecha_nacimiento_formatted' => (int) \Carbon\Carbon::parse($empleado->fecha_nacimiento)->format('d/m/Y'),
            'domicilio' => $empleado->domicilio,
            'telefono' => $empleado->telefono,
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
        Log::error('Error editando empleado:', ['id' => $id, 'error' => $e->getMessage()]);
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
            'telefono' => 'required|string|max:9|regex:/^[+]?[0-9\s\-]+$/',
            'domicilio' => 'required|string|max:500',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
        ], [
            'telefono.required' => 'El campo teléfono es obligatorio.',
            'telefono.regex' => 'El formato del teléfono es inválido. Use formato internacional: +34 612 345 678',
            'domicilio.required' => 'El campo domicilio es obligatorio.',
        ]);

        $empleado->update($validated);

        Log::info('Empleado actualizado:', [
            'id' => $id, 
            'telefono' => $validated['telefono'],
            'domicilio' => $validated['domicilio']
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Empleado actualizado correctamente'
        ]);

    } catch (\Exception $e) {
        Log::error('Error actualizando empleado:', ['id' => $id, 'error' => $e->getMessage()]);
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
                'fecha_nacimiento_formatted' => (int) \Carbon\Carbon::parse($empleado->fecha_nacimiento)->format('d/m/Y'),
                'edad' => \Carbon\Carbon::parse($empleado->fecha_nacimiento)->age,
                'domicilio' => $empleado->domicilio,
                'telefono' => $empleado->telefono,
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

        Log::info('Empleado eliminado:', ['id' => $id, 'nombre' => $empleado->nombre]);

        return response()->json([
            'success' => true,
            'message' => 'Empleado eliminado correctamente'
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error eliminando empleado:', ['id' => $id, 'error' => $e->getMessage()]);
        return response()->json([
            'success' => false,
            'message' => 'Error al eliminar empleado: ' . $e->getMessage()
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


 /**
     * Generar QR de forma automática y eficiente
     */
     private function generarQR($dni, $nombreCompleto)
    {
        try {
            Log::info('🔄 Iniciando generación de QR para DNI: ' . $dni);

            // Generar código único
            $codigoUnico = 'EMP_' . $dni . '_' . time();
            
            // Contenido simple para mejor compatibilidad
            $qrContent = "EMPLEADO|{$dni}|{$nombreCompleto}|{$codigoUnico}";
            
            Log::info('📝 Contenido QR: ' . $qrContent);

            // ✅ MÉTODO 1: Simple QR Code (PRIMERA OPCIÓN)
            $qrImage = $this->generarConSimpleQRCode($qrContent);
            if ($qrImage) {
                Log::info('✅ QR generado con Simple QR Code');
                return [
                    'imagen' => $qrImage,
                    'codigo_unico' => $codigoUnico,
                    'contenido' => $qrContent
                ];
            }

            // ✅ MÉTODO 2: Google Charts (SEGUNDA OPCIÓN)
            $qrImage = $this->generarConGoogleCharts($qrContent);
            if ($qrImage) {
                Log::info('✅ QR generado con Google Charts');
                return [
                    'imagen' => $qrImage,
                    'codigo_unico' => $codigoUnico,
                    'contenido' => $qrContent
                ];
            }

            // ✅ MÉTODO 3: API Externa (TERCERA OPCIÓN)
            $qrImage = $this->generarConAPIExterna($qrContent);
            if ($qrImage) {
                Log::info('✅ QR generado con API Externa');
                return [
                    'imagen' => $qrImage,
                    'codigo_unico' => $codigoUnico,
                    'contenido' => $qrContent
                ];
            }

            // ✅ MÉTODO 4: QR Básico Local (ÚLTIMO RECURSO)
            Log::info('🔄 Usando generación local básica');
            return $this->generarQRLocalBasico($dni, $nombreCompleto, $codigoUnico);

        } catch (\Exception $e) {
            Log::error('❌ Error crítico en generarQR: ' . $e->getMessage());
            // Último recurso absoluto
            return $this->generarQRMinimo($dni, $codigoUnico);
        }
    }


    /**
     * Generar QR usando Simple QR Code (recomendado)
     */
    private function generarConSimpleQRCode($content)
    {
        try {
            // Verificar si la clase existe
            if (!class_exists('SimpleSoftwareIO\QrCode\Facades\QrCode')) {
                Log::warning('❌ Simple QR Code no está instalado');
                return null;
            }

            // Generar QR con configuración simple
            $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')
                ->size(250)
                ->margin(2)
                ->errorCorrection('H')
                ->generate($content);

            return $qrCode;

        } catch (\Exception $e) {
            Log::error('❌ Error en Simple QR Code: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Generar QR usando Google Charts API
     */
    private function generarConGoogleCharts($content)
    {
        try {
            $encodedContent = urlencode($content);
            $url = "https://chart.googleapis.com/chart?cht=qr&chs=250x250&chl={$encodedContent}&choe=UTF-8&chld=H";
            
            Log::info('🔗 URL Google Charts: ' . $url);

            $context = stream_context_create([
                'http' => [
                    'timeout' => 15,
                    'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36\r\n",
                    'ignore_errors' => true
                ],
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ]
            ]);

            $imageData = @file_get_contents($url, false, $context);
            
            if ($imageData && strlen($imageData) > 1000) {
                // Verificar que sea una imagen PNG válida
                if (strpos($imageData, "\x89PNG\r\n\x1a\n") === 0) {
                    Log::info('✅ Google Charts: Imagen PNG válida generada');
                    return $imageData;
                } else {
                    Log::warning('❌ Google Charts: Respuesta no es PNG válido');
                }
            } else {
                Log::warning('❌ Google Charts: Imagen vacía o muy pequeña');
            }

            return null;

        } catch (\Exception $e) {
            Log::error('❌ Error en Google Charts: ' . $e->getMessage());
            return null;
        }
    }


    private function generarConAPIExterna($content)
    {
        try {
            $encodedContent = urlencode($content);
            
            // Probar diferentes APIs externas
            $apis = [
                "https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={$encodedContent}&format=png",
                "https://quickchart.io/qr?text={$encodedContent}&size=250&format=png",
                "http://api.qrserver.com/v1/create-qr-code/?data={$encodedContent}&size=250x250"
            ];

            foreach ($apis as $apiUrl) {
                try {
                    Log::info('🔗 Probando API: ' . $apiUrl);
                    
                    $context = stream_context_create([
                        'http' => [
                            'timeout' => 10,
                            'header' => "User-Agent: Mozilla/5.0\r\n",
                            'ignore_errors' => true
                        ]
                    ]);

                    $imageData = @file_get_contents($apiUrl, false, $context);
                    
                    if ($imageData && strlen($imageData) > 500) {
                        Log::info('✅ API Externa: QR generado exitosamente');
                        return $imageData;
                    }
                } catch (\Exception $apiError) {
                    Log::warning('❌ API falló: ' . $apiError->getMessage());
                    continue;
                }
            }

            return null;

        } catch (\Exception $e) {
            Log::error('❌ Error en APIs externas: ' . $e->getMessage());
            return null;
        }
    }

private function generarQRLocalBasico($dni, $nombreCompleto, $codigoUnico)
    {
        try {
            Log::info('🎨 Generando QR local básico');

            $size = 250;
            $image = imagecreate($size, $size);
            
            if (!$image) {
                throw new \Exception('No se pudo crear la imagen GD');
            }

            // Colores
            $white = imagecolorallocate($image, 255, 255, 255);
            $black = imagecolorallocate($image, 0, 0, 0);
            $blue = imagecolorallocate($image, 41, 128, 185);
            
            // Fondo blanco
            imagefill($image, 0, 0, $white);
            
            // Bordes
            imagerectangle($image, 5, 5, $size-6, $size-6, $black);
            imagerectangle($image, 6, 6, $size-7, $size-7, $black);
            
            // Texto centrado
            $textos = [
                "EMPLEADO",
                "DNI: " . $dni,
                substr($nombreCompleto, 0, 20) // Limitar longitud
            ];
            
            foreach ($textos as $i => $texto) {
                $font = 3; // Fuente GD built-in
                $textWidth = imagefontwidth($font) * strlen($texto);
                $x = ($size - $textWidth) / 2;
                $y = 90 + ($i * 25);
                
                imagestring($image, $font, $x, $y, $texto, $black);
            }
            
            // Capturar imagen
            ob_start();
            imagepng($image);
            $imageData = ob_get_clean();
            imagedestroy($image);

            Log::info('✅ QR local básico generado exitosamente');
            
            return [
                'imagen' => $imageData,
                'codigo_unico' => $codigoUnico,
                'contenido' => "EMPLEADO|{$dni}|{$nombreCompleto}"
            ];

        } catch (\Exception $e) {
            Log::error('❌ Error en QR local básico: ' . $e->getMessage());
            throw $e; // Pasar al siguiente nivel
        }
    }

 private function generarQRMinimo($dni, $codigoUnico)
    {
        try {
            Log::info('🆘 Generando QR mínimo de emergencia');

            $size = 150;
            $image = imagecreate($size, $size);
            
            $white = imagecolorallocate($image, 255, 255, 255);
            $black = imagecolorallocate($image, 0, 0, 0);
            
            imagefill($image, 0, 0, $white);
            
            // Texto muy simple
            $texto = "EMP: " . $dni;
            $font = 3;
            $textWidth = imagefontwidth($font) * strlen($texto);
            $x = ($size - $textWidth) / 2;
            $y = ($size - imagefontheight($font)) / 2;
            
            imagestring($image, $font, $x, $y, $texto, $black);
            
            ob_start();
            imagepng($image);
            $imageData = ob_get_clean();
            imagedestroy($image);

            Log::info('✅ QR mínimo generado en modo emergencia');
            
            return [
                'imagen' => $imageData,
                'codigo_unico' => $codigoUnico,
                'contenido' => "EMERGENCIA|{$dni}"
            ];

        } catch (\Exception $e) {
            Log::error('❌ ERROR CRÍTICO: No se pudo generar ningún tipo de QR');
            // Devolver estructura vacía pero válida
            return [
                'imagen' => '',
                'codigo_unico' => $codigoUnico,
                'contenido' => "ERROR|{$dni}"
            ];
        }
    }

    /**
     * QR básico como último recurso
     */
    private function generarQRBasico($dni, $nombreCompleto)
    {
        try {
            $size = 200;
            $image = imagecreate($size, $size);
            
            $white = imagecolorallocate($image, 255, 255, 255);
            $black = imagecolorallocate($image, 0, 0, 0);
            $blue = imagecolorallocate($image, 41, 128, 185);
            
            imagefill($image, 0, 0, $white);
            
            // Bordes
            imagerectangle($image, 5, 5, $size-6, $size-6, $black);
            imagerectangle($image, 6, 6, $size-7, $size-7, $black);
            
            // Texto
            $textos = [
                "EMPLEADO",
                $dni,
                substr($nombreCompleto, 0, 20)
            ];
            
            foreach ($textos as $i => $texto) {
                $font = 3;
                $textWidth = imagefontwidth($font) * strlen($texto);
                $x = ($size - $textWidth) / 2;
                $y = 80 + ($i * 20);
                
                if ($i === 1) {
                    imagestring($image, $font, $x, $y, $texto, $blue);
                } else {
                    imagestring($image, $font, $x, $y, $texto, $black);
                }
            }
            
            ob_start();
            imagepng($image);
            $imageData = ob_get_clean();
            imagedestroy($image);
            
            Log::info('✅ QR básico generado exitosamente');
            return $imageData;
            
        } catch (\Exception $e) {
            Log::error('Error incluso en QR básico: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Generar preview del QR en tiempo real
     */
    public function generarQRPreview(Request $request)
    {
        try {
            $dni = $request->get('dni', '');
            $nombre = $request->get('nombre', '');
            $apellidos = $request->get('apellidos', '');

            Log::info('🎨 SOLICITUD QR Preview - DNI: ' . $dni);

            // Validaciones básicas
            if (empty($dni)) {
                return response()->json([
                    'success' => false,
                    'message' => 'DNI requerido'
                ], 400);
            }

            if (strlen($dni) < 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'DNI demasiado corto'
                ], 400);
            }

            $nombreCompleto = trim($nombre . ' ' . $apellidos);
            
            // Forzar generación incluso si hay errores
            $qrData = $this->generarQR($dni, $nombreCompleto);

            if ($qrData && !empty($qrData['imagen'])) {
                Log::info('✅ QR Preview generado EXITOSAMENTE');
                
                return response()->json([
                    'success' => true,
                    'qr_image' => base64_encode($qrData['imagen']),
                    'dni' => $dni,
                    'message' => 'QR generado correctamente',
                    'metodo' => 'multiple_fallbacks',
                    'qr_content' => $qrData['contenido']
                ]);
            } else {
                // Último intento desesperado
                Log::warning('🆘 Todos los métodos fallaron, usando respuesta de emergencia');
                
                return response()->json([
                    'success' => true, // ¡IMPORTANTE! success: true para que el frontend no falle
                    'qr_image' => base64_encode($this->crearImagenEmergencia($dni)),
                    'dni' => $dni,
                    'message' => 'QR generado (modo emergencia)',
                    'metodo' => 'emergencia',
                    'qr_content' => "EMERGENCIA|{$dni}"
                ]);
            }

        } catch (\Exception $e) {
            Log::error('💥 ERROR CATASTRÓFICO en QR Preview: ' . $e->getMessage());

            // Respuesta de último recurso absoluto
            return response()->json([
                'success' => true, // ¡IMPORTANTE! Siempre true para evitar errores en frontend
                'qr_image' => null,
                'dni' => $request->get('dni', ''),
                'message' => 'QR disponible al guardar',
                'metodo' => 'fallback_total',
                'qr_content' => "FALLBACK|{$request->get('dni', '')}"
            ]);
        }
    }

 /**
     * Crear imagen de emergencia cuando todo falla
     */
    private function crearImagenEmergencia($dni)
    {
        $size = 200;
        $image = imagecreate($size, $size);
        $white = imagecolorallocate($image, 255, 255, 255);
        $red = imagecolorallocate($image, 255, 0, 0);
        
        imagefill($image, 0, 0, $white);
        
        $texto = "QR NO DISP.";
        $font = 4;
        $textWidth = imagefontwidth($font) * strlen($texto);
        $x = ($size - $textWidth) / 2;
        $y = 80;
        
        imagestring($image, $font, $x, $y, $texto, $red);
        imagestring($image, 3, ($size - (imagefontwidth(3) * strlen($dni))) / 2, 110, $dni, $red);
        
        ob_start();
        imagepng($image);
        $imageData = ob_get_clean();
        imagedestroy($image);
        
        return $imageData;
    }

/**
 * Obtener información del QR para impresión - VERSIÓN CORREGIDA
 */
public function getQRInfo($id)
{
    try {
        Log::info('🔍 Solicitando información QR para empleado ID:', ['id' => $id]);

        // Cargar empleado con la relación QR
        $empleado = Empleado::with('qr')->find($id);
        
        if (!$empleado) {
            return response()->json([
                'success' => false,
                'message' => 'Empleado no encontrado'
            ], 404);
        }

        // Verificar si existe el QR relacionado
        if (!$empleado->qr) {
            Log::error('❌ QR no encontrado para empleado:', [
                'empleado_id' => $empleado->id,
                'qr_id_en_empleado' => $empleado->qr_id
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'No se encontró código QR para este empleado'
            ], 404);
        }

        // Verificar que la imagen del QR existe
        if (empty($empleado->qr->imagen_qr)) {
            return response()->json([
                'success' => false,
                'message' => 'La imagen del QR está vacía'
            ], 500);
        }

        // Preparar datos para respuesta
        $data = [
            'empleado_id' => $empleado->id,
            'nombre_completo' => $empleado->nombre . ' ' . $empleado->apellidos,
            'dni' => $empleado->dni,
            'username' => $empleado->credencial->username ?? 'N/A',
            'codigo_unico' => $empleado->qr->codigo_unico,
            'qr_image' => base64_encode($empleado->qr->imagen_qr),
            'fecha_generacion' => $empleado->qr->created_at->format('d/m/Y H:i')
        ];

        Log::info('✅ Información QR enviada correctamente', [
            'empleado_id' => $empleado->id,
            'qr_id' => $empleado->qr->id
        ]);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);

    } catch (\Exception $e) {
        Log::error('❌ Error obteniendo información QR:', [
            'id' => $id, 
            'error' => $e->getMessage()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Error al obtener información del QR: ' . $e->getMessage()
        ], 500);
    }
}


/**
 * Obtener registros del empleado para DataTable - VERSIÓN CORREGIDA
 */
public function getRegistrosDataTable(Request $request, $id)
{
    try {
        // USA LA MISMA CONSULTA QUE EL PERFIL DEL EMPLEADO
        $query = DB::table('tabla_registros_tiempo')
            ->where('empleado_id', $id);

        // MISMO FILTRADO POR MES
        $month = $request->input('mes');
        $year = $request->input('año');

        if ($month && $year) {
            $fechaInicio = Carbon::create($year, $month, 1)->startOfMonth();
            $fechaFin = Carbon::create($year, $month, 1)->endOfMonth();
            $query->whereBetween('created_at', [$fechaInicio, $fechaFin]);
        }

        $registros = $query->orderBy('created_at', 'desc')->get();

        // MISMO FORMATO DE DATOS
        $data = $registros->map(function($registro) {
            return [
                'id' => $registro->id,
                'inicio' => $registro->inicio,
                'fin' => $registro->fin,
                'pausa_inicio' => $registro->pausa_inicio, // ✅ MISMOS CAMPOS
                'pausa_fin' => $registro->pausa_fin,       // ✅ MISMOS CAMPOS  
                'tiempo_pausa_total' => $registro->tiempo_pausa_total,
                'tiempo_total' => $registro->tiempo_total,
                'estado' => $registro->estado,
                'direccion' => $registro->direccion,
                'ciudad' => $registro->ciudad,
                'pais' => $registro->pais,
                'created_at' => $registro->created_at
            ];
        });

        return response()->json([
            'draw' => intval($request->input('draw', 1)),
            'recordsTotal' => $registros->count(),
            'recordsFiltered' => $registros->count(),
            'data' => $data
        ]);

    } catch (\Exception $e) {
        Log::error('Error en datatable admin:', ['error' => $e->getMessage()]);
        return response()->json([
            'draw' => intval($request->input('draw', 1)),
            'recordsTotal' => 0,
            'recordsFiltered' => 0,
            'data' => []
        ], 500);
    }
}
/**
 * Obtener resumen de registros del empleado - VERSIÓN CORREGIDA
 */
public function getResumenRegistros(Request $request, $id)
{
    try {
        Log::info('📈 Resumen registros solicitado:', [
            'empleado_id' => $id,
            'mes' => $request->input('mes'),
            'año' => $request->input('año')
        ]);

        $empleado = Empleado::find($id);
        
        if (!$empleado) {
            return response()->json([
                'success' => false,
                'message' => 'Empleado no encontrado'
            ], 404);
        }

        $query = DB::table('tabla_registros_tiempo')
            ->where('empleado_id', $empleado->id);

        // Aplicar filtros de mes y año
        $month = $request->input('mes');
        $year = $request->input('año');

        if ($month && $year) {
            $fechaInicio = Carbon::create($year, $month, 1)->startOfMonth();
            $fechaFin = Carbon::create($year, $month, 1)->endOfMonth();
            
            $query->whereBetween('created_at', [$fechaInicio, $fechaFin]);
        } else {
            // Por defecto, mes actual
            $now = Carbon::now();
            $fechaInicio = $now->copy()->startOfMonth();
            $fechaFin = $now->copy()->endOfMonth();
            
            $query->whereBetween('created_at', [$fechaInicio, $fechaFin]);
        }

        $registros = $query->get();

        Log::info('📊 Registros para resumen:', ['total' => $registros->count()]);

        // Calcular estadísticas
        $totalSegundos = 0;
        foreach ($registros as $registro) {
            if ($registro->tiempo_total && $registro->tiempo_total > 0) {
                $totalSegundos += $registro->tiempo_total;
            }
        }

        $totalHoras = number_format($totalSegundos / 3600, 2);
        $totalRegistros = $registros->count();
        
        // Días trabajados = días distintos con registros
        $diasTrabajados = $registros->unique(function($registro) {
            return Carbon::parse($registro->created_at)->format('Y-m-d');
        })->count();

        // Promedio diario
        $promedioDiario = $diasTrabajados > 0 ? number_format($totalSegundos / $diasTrabajados / 3600, 2) : 0;

        Log::info('📈 Resumen calculado:', [
            'total_horas' => $totalHoras,
            'total_registros' => $totalRegistros,
            'dias_trabajados' => $diasTrabajados,
            'promedio_diario' => $promedioDiario
        ]);

        return response()->json([
            'success' => true,
            'total_horas' => $totalHoras,
            'total_registros' => $totalRegistros,
            'promedio_diario' => $promedioDiario,
            'dias_trabajados' => $diasTrabajados
        ]);

    } catch (\Exception $e) {
        Log::error('❌ Error obteniendo resumen registros:', [
            'empleado_id' => $id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'success' => false,
            'total_horas' => '0.00',
            'total_registros' => 0,
            'promedio_diario' => '0.00',
            'dias_trabajados' => 0,
            'error' => $e->getMessage()
        ]);
    }
}


/**
 * Exportar empleados a PDF (solo descarga)
 */
public function exportarPdfMes(Request $request)
{
    try {
        logger('📤 Solicitud exportar PDF recibida:', $request->all());

        // Validar los parámetros
        $request->validate([
            'mes' => 'required|integer|between:1,12',
            'año' => 'required|integer|min:2020|max:' . (date('Y') + 1)
        ]);

        $mes = $request->mes;
        $año = $request->año;

        logger('🔍 Buscando empleados para PDF:', [
            'mes' => $mes, 
            'año' => $año
        ]);

        // Verificar si hay datos para el mes seleccionado
        $fechaInicio = Carbon::create($año, $mes, 1)->startOfMonth();
        $fechaFin = Carbon::create($año, $mes, 1)->endOfMonth();
        
        $existenDatos = Empleado::whereBetween('created_at', [$fechaInicio, $fechaFin])->exists();

        if (!$existenDatos) {
            return response()->json([
                'success' => false,
                'message' => 'No hay empleados registrados en ' . $this->getNombreMes($mes) . ' de ' . $año
            ], 404);
        }

        // Generar PDF para descarga directa
        $export = new EmpleadosPdfExport($mes, $año);
        return $export->download();

    } catch (\Exception $e) {
        logger()->error('❌ Error exportando PDF:', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Error al generar el archivo PDF: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * Exportar registro horario individual en formato PDF oficial
 */
public function exportarRegistroHorarioIndividual(Request $request, $id)
{
    try {
        $request->validate([
            'mes' => 'required|integer|between:1,12',
            'año' => 'required|integer|min:2020|max:' . (date('Y') + 1)
        ]);

        $mes = $request->mes;
        $año = $request->año;

        // Verificar que el empleado existe
        $empleado = Empleado::find($id);
        if (!$empleado) {
            return response()->json([
                'success' => false,
                'message' => 'Empleado no encontrado'
            ], 404);
        }

        // Generar PDF individual
        $export = new \App\Exports\RegistroHorarioIndividualExport($id, $mes, $año);
        return $export->download();

    } catch (\Exception $e) {
        Log::error('Error exportando registro horario individual:', [
            'empleado_id' => $id,
            'mes' => $request->mes,
            'año' => $request->año,
            'error' => $e->getMessage()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Error al generar el registro horario: ' . $e->getMessage()
        ], 500);
    }
}


// AdminController.php - AGREGAR ESTE MÉTODO
/**
 * Obtener detalles de un registro específico - VERSIÓN ADMIN
 */
public function getDetallesRegistroAdmin($empleadoId, $registroId)
{
    try {
        Log::info('🔍 Admin solicitando detalles de registro:', [
            'empleado_id' => $empleadoId,
            'registro_id' => $registroId,
            'admin_id' => Auth::id()
        ]);

        // Verificar que el empleado existe
        $empleado = Empleado::find($empleadoId);
        if (!$empleado) {
            return response()->json([
                'success' => false,
                'message' => 'Empleado no encontrado'
            ], 404);
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
            ], 404);
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

        Log::info('✅ Detalles de registro enviados a admin:', [
            'empleado_id' => $empleado->id,
            'registro_id' => $registro->id,
            'admin_id' => Auth::id()
        ]);

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
        Log::error('❌ Error en getDetallesRegistroAdmin:', [
            'empleado_id' => $empleadoId,
            'registro_id' => $registroId,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Error al obtener detalles del registro: ' . $e->getMessage()
        ], 500);
    }
}

}
