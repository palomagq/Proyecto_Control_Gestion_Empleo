<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Empleado; 
use App\Models\Credencial; 

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller
{
    //


    public function dashboard(){
        return view('admin.dashboard');
    }
    
    public function empleados(){
        return view('admin.sections.empleados');
    }

public function storeEmployee(Request $request){
    $validated = $request->validate([
        'nombre' => 'required|string|max:255',
        'apellidos' => 'required|string|max:255',
        'dni' => 'required|unique:tabla_empleados,dni|regex:/^[0-9]{8}[A-Za-z]$/',
        'fecha_nacimiento' => 'required|date|before:-18 years',
        'domicilio' => 'required|string|max:500',
        'username' => 'required|unique:tabla_credenciales,username',
        'password' => 'required|min:8|confirmed|regex:/^[0-9]+$/',
        'latitud' => 'nullable|numeric',
        'longitud' => 'nullable|numeric',
    ]);

    try {
        // Guardar en tabla_empleados
        $empleado = Empleado::create([
            'nombre' => $validated['nombre'],
            'apellidos' => $validated['apellidos'],
            'dni' => $validated['dni'],
            'fecha_nacimiento' => $validated['fecha_nacimiento'],
            'domicilio' => $validated['domicilio'],
            'latitud' => $validated['latitud'],
            'longitud' => $validated['longitud'],
        ]);
        
        // Guardar en tabla_credencial
        Credencial::create([
            'empleado_id' => $empleado->id,
            'username' => $validated['username'],
            'password' => bcrypt($validated['password']),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Empleado creado exitosamente'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al crear empleado: ' . $e->getMessage()
        ], 500);
    }
}

      public function getEmpleadosDataTable(Request $request){
        // Filtrar solo usuarios con rol de empleado
        $query = Empleado::where('role', 'empleado');

        // Aplicar filtros
        if ($request->has('dni') && !empty($request->dni)) {
            $query->where('dni', 'like', '%' . $request->dni . '%');
        }

        if ($request->has('nombre') && !empty($request->nombre)) {
            $query->where('nombre', 'like', '%' . $request->nombre . '%');
        }

        // Filtro por rango de fechas (mes completo)
        if ($request->has('fecha_inicio') && !empty($request->fecha_inicio) && 
            $request->has('fecha_fin') && !empty($request->fecha_fin)) {
            $query->whereBetween('fecha_nacimiento', [$request->fecha_inicio, $request->fecha_fin]);
        }

        return DataTables::eloquent($query)
            ->addColumn('acciones', function($usuario) {
                return '';
            })
            ->editColumn('fecha_nacimiento', function($usuario) {
                return $usuario->fecha_nacimiento ? \Carbon\Carbon::parse($usuario->fecha_nacimiento)->format('d/m/Y') : '';
            })
            ->editColumn('activo', function($usuario) {
                return $usuario->activo ? 
                    '<span class="badge badge-success">Activo</span>' : 
                    '<span class="badge badge-danger">Inactivo</span>';
            })
            ->rawColumns(['acciones', 'activo'])
            ->toJson();
    }

    public function destroyEmployee($id){
        try {
            $empleado = Empleado::findOrFail($id);
            $empleado->delete();

            return response()->json([
                'success' => true,
                'message' => 'Empleado eliminado correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar empleado'
            ], 500);
        }
    }
    
}
