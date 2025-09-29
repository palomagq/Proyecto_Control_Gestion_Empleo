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

            // **PRIMERO crear la credencial CON rol_id**
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

            // **LUEGO crear el empleado con el credencial_id Y rol_id**
            $empleado = Empleado::create([
                'nombre' => $validated['nombre'],
                'apellidos' => $validated['apellidos'],
                'dni' => $dni,
                'fecha_nacimiento' => $validated['fecha_nacimiento'],
                'domicilio' => $validated['domicilio'],
                'latitud' => $validated['latitud'] ?? '40.4168',
                'longitud' => $validated['longitud'] ?? '-3.7038',
                'credencial_id' => $credencial->id,
                'rol_id' => $rolId, // ✅ AÑADIR rol_id A LA TABLA EMPLEADOS TAMBIÉN
            ]);

            Log::info('✅ Empleado creado:', [
                'empleado_id' => $empleado->id,
                'nombre' => $empleado->nombre,
                'dni' => $empleado->dni,
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

            Log::info('🎉 Empleado creado exitosamente', [
                'empleado_id' => $empleado->id,
                'username' => $validated['username'],
                'rol_id' => $rolId,
                'edad' => $edad
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Empleado creado exitosamente',
                'data' => [
                    'empleado_id' => $empleado->id,
                    'username' => $validated['username'],
                    'password' => $validated['password'],
                    'edad' => $edad,
                    'rol_id' => $rolId
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

    } catch (ValidationException $e) {
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
        $query = Empleado::with('credencial');

        \Log::info('🔍 Consulta base creada');

        // **DEBUG: Log de todos los filtros recibidos**
        \Log::info('🎯 Filtros recibidos:', [
            'filterDni' => $request->filterDni,
            'filterNombre' => $request->filterNombre,
            'filterMes' => $request->filterMes,
            'search' => $request->search
        ]);

        // Aplicar filtros - CORREGIDO: Usar los mismos nombres que en JavaScript
        if ($request->has('filterDni') && !empty($request->filterDni)) {
            $dniFilter = $request->filterDni;
            $query->where('dni', 'like', '%' . $dniFilter . '%');
            \Log::info('✅ Filtro DNI aplicado:', [
                'dni_buscado' => $dniFilter,
                'query' => '%' . $dniFilter . '%'
            ]);
        }

        if ($request->has('filterNombre') && !empty($request->filterNombre)) {
            $nombreFilter = $request->filterNombre;
            $query->where(function($q) use ($nombreFilter) {
                $q->where('nombre', 'like', '%' . $nombreFilter . '%')
                  ->orWhere('apellidos', 'like', '%' . $nombreFilter . '%');
            });
            \Log::info('✅ Filtro Nombre aplicado:', [
                'nombre_buscado' => $nombreFilter,
                'query' => '%' . $nombreFilter . '%'
            ]);
        }

        // Filtro por mes - MEJORADO
        if ($request->has('filterMes') && !empty($request->filterMes)) {
            try {
                $fecha = \Carbon\Carbon::createFromFormat('Y-m', $request->filterMes);
                $startDate = $fecha->startOfMonth()->format('Y-m-d');
                $endDate = $fecha->endOfMonth()->format('Y-m-d');
                $query->whereBetween('fecha_nacimiento', [$startDate, $endDate]);
                \Log::info('✅ Filtro mes aplicado:', [
                    'mes' => $request->filterMes,
                    'desde' => $startDate,
                    'hasta' => $endDate
                ]);
            } catch (\Exception $e) {
                \Log::error('❌ Error en filtro de mes:', [
                    'mes' => $request->filterMes,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Búsqueda global de DataTables
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function($q) use ($search) {
                $q->where('dni', 'like', "%{$search}%")
                  ->orWhere('nombre', 'like', "%{$search}%")
                  ->orWhere('apellidos', 'like', "%{$search}%")
                  ->orWhere('domicilio', 'like', "%{$search}%")
                  ->orWhereHas('credencial', function($q) use ($search) {
                      $q->where('username', 'like', "%{$search}%");
                  });
            });
            \Log::info('✅ Búsqueda global aplicada:', ['search' => $search]);
        }

        // **DEBUG: Contar antes de ordenar**
        $countBeforeOrder = $query->count();
        \Log::info('📊 Registros antes de ordenar:', ['count' => $countBeforeOrder]);

        // Ordenamiento
        $orderColumn = $request->order[0]['column'] ?? 0;
        $orderDirection = $request->order[0]['dir'] ?? 'asc';
        
        $columns = ['id', 'dni', 'nombre', 'apellidos', 'fecha_nacimiento', 'domicilio', 'username'];
        
        if (isset($columns[$orderColumn])) {
            $query->orderBy($columns[$orderColumn], $orderDirection);
            \Log::info('✅ Orden aplicado:', [
                'columna' => $columns[$orderColumn], 
                'direccion' => $orderDirection
            ]);
        } else {
            $query->orderBy('id', 'asc');
            \Log::info('✅ Orden por defecto aplicado: id ASC');
        }

        $totalRecords = $query->count();
        \Log::info('📈 Total de registros filtrados:', ['total' => $totalRecords]);
        
        // Paginación
        $start = $request->start ?? 0;
        $length = $request->length ?? 10;
        
        $empleados = $query->skip($start)->take($length)->get();
        
        // **DEBUG: Log de los empleados encontrados**
        \Log::info('👥 Empleados encontrados:', [
            'count' => $empleados->count(),
            'ids' => $empleados->pluck('id')->toArray(),
            'nombres' => $empleados->pluck('nombre')->toArray()
        ]);

        $data = $empleados->map(function($empleado) {
            $edad = \Carbon\Carbon::parse($empleado->fecha_nacimiento)->age;

            return [
                'id' => $empleado->id,
                'dni' => $empleado->dni,
                'nombre' => $empleado->nombre,
                'apellidos' => $empleado->apellidos,
                'fecha_nacimiento' => \Carbon\Carbon::parse($empleado->fecha_nacimiento)->format('d/m/Y'),
                'edad' => $edad . ' años',
                'domicilio' => $empleado->domicilio,
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
            'draw' => $request->draw ?? 1,
            'recordsTotal' => Empleado::count(),
            'recordsFiltered' => $totalRecords,
            'data' => $data
        ];

        \Log::info('✅ Respuesta datatable preparada');

        return response()->json($response);

    } catch (\Exception $e) {
        \Log::error('❌ Error en datatable empleados:', [
            'error' => $e->getMessage(), 
            'trace' => $e->getTraceAsString()
        ]);
        return response()->json([
            'draw' => $request->draw ?? 1,
            'recordsTotal' => 0,
            'recordsFiltered' => 0,
            'data' => [],
            'error' => $e->getMessage()
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
        
        $data = [
            'id' => $empleado->id,
            'nombre' => $empleado->nombre,
            'apellidos' => $empleado->apellidos,
            'dni' => $empleado->dni,
            'fecha_nacimiento' => $empleado->fecha_nacimiento,
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
        \Log::error('Error obteniendo empleado:', ['id' => $id, 'error' => $e->getMessage()]);
        return response()->json([
            'success' => false,
            'message' => 'Empleado no encontrado'
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
    
}
