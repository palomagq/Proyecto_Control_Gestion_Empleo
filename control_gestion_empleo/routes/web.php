<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\LoginQrController;
use App\Http\Controllers\TareaController;

    use Illuminate\Support\Facades\DB;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


// Rutas para login por QR
Route::get('/empleado/qr-login/{token}', [App\Http\Controllers\AdminController::class, 'qrLogin'])->name('empleado.qr.login');

// Rutas para gestión de QR
Route::get('/admin/empleado/{id}/qr-login-url', [App\Http\Controllers\AdminController::class, 'getQrLoginUrl'])->name('admin.empleado.qr-login-url');
    
Route::get('/admin/empleado/{id}/qr-login-info', [App\Http\Controllers\AdminController::class, 'getQrLoginInfo'])->name('admin.empleado.qr-login-info');
    
Route::post('/admin/empleado/{id}/renovar-qr', [App\Http\Controllers\AdminController::class, 'renovarQr'])->name('admin.empleado.renovar-qr');

Route::prefix('auth')->group(function () {
    // Generar QR para login
    Route::get('/qr/generate', [LoginQrController::class, 'generateQr'])->name('login.qr.generate');
    
    // Página de escaneo
    Route::get('/qr/scan/{token}', [LoginQrController::class, 'showScanPage'])->name('login.qr.scan');
    
    // Procesar escaneo
    Route::post('/qr/scan/{token}', [LoginQrController::class, 'processScan'])->name('login.qr.process');
});



Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/empleados', [AdminController::class, 'empleados'])->name('admin.empleados');
    Route::post('/empleados/store', [AdminController::class, 'storeEmployee'])->name('admin.empleados.store');
    Route::get('/empleados/datatable', [AdminController::class, 'getEmpleadosDataTable'])->name('admin.empleados.datatable');
    Route::get('/empleados/stats', [AdminController::class, 'getStats'])->name('admin.empleados.stats');

    Route::get('/empleados/buscar-por-dni/{dni}', [AdminController::class, 'buscarPorDni'])->name('admin.empleados.buscar-por-dni');

    Route::get('/empleados/verificar-username/{username}', [AdminController::class, 'verificarUsername'])->name('admin.empleados.verificar-username');
    Route::get('/empleados/exportar-excel-mes', [AdminController::class, 'exportarExcelMes'])->name('admin.empleados.exportar-excel-mes');
    Route::get('/empleados/exportar-pdf-mes', [AdminController::class, 'exportarPdfMes'])->name('admin.empleados.exportar-pdf-mes');
    Route::get('/empleados/{id}/exportar-registro-horario', [AdminController::class, 'exportarRegistroHorarioIndividual'])->name('admin.empleados.exportar-registro-horario');


    Route::get('/empleados/conectados', [AdminController::class, 'getEmpleadosConectadosParaAsignacion'])->name('admin.empleados.conectados');
    Route::post('/empleados/{empleado}/conexion', [AdminController::class, 'actualizarEstadoConexion'])->name('admin.empleados.conexion');
    Route::get('/estadisticas/conexion', [AdminController::class, 'getEstadisticasConexion'])->name('admin.estadisticas.conexion');

     // Rutas para gestión de QR
    Route::get('/empleados/{id}/qr-info', [AdminController::class, 'getQRInfo'])->name('admin.empleados.qr-info');
    Route::post('/empleados/generar-qr-preview', [AdminController::class, 'generarQRPreview'])->name('admin.empleados.generar-qr-preview');
    Route::post('/empleados/{id}/enviar-whatsapp', [AdminController::class, 'enviarQRWhatsApp'])->name('admin.empleados.enviar-whatsapp');
    Route::get('/empleados/{id}/generar-pdf', [AdminController::class, 'generarPDFQR'])->name('admin.empleados.generar-pdf-qr');


    // Rutas para los registros del empleado en el modal de vista
    Route::get('/empleados/registros/{id}/datatable', [AdminController::class, 'getRegistrosDataTable'])->name('admin.empleados.registros.datatable');
    Route::get('/empleados/registros/{id}/resumen', [AdminController::class, 'getResumenRegistros'])->name('admin.empleados.registros.resumen');
    Route::get('/empleados/{empleadoId}/registros/{registroId}/detalles', [AdminController::class, 'getDetallesRegistroAdmin']) ->name('admin.empleados.registros.detalles');


    // Tareas
    Route::get('/tareas', [AdminController::class, 'tareas'])->name('admin.tareas');

    // API Tareas
    Route::get('/tareas/datatable', [AdminController::class, 'getTareasDataTable'])->name('admin.tareas.datatable');
    Route::get('/tareas/tipos', [AdminController::class, 'getTiposTarea'])->name('admin.tareas.tipos');
    Route::get('/tareas/empleados', [AdminController::class, 'getEmpleadosParaAsignacion'])->name('admin.tareas.empleados');
    Route::post('/tareas', [AdminController::class, 'storeTarea'])->name('admin.tareas.store');
    Route::get('/tareas/{id}', [AdminController::class, 'getTarea'])->name('admin.tareas.show');
    Route::put('/tareas/{id}', [AdminController::class, 'updateTarea'])->name('admin.tareas.update');
    Route::delete('/tareas/{id}', [AdminController::class, 'destroyTarea'])->name('admin.tareas.destroy');
    Route::post('/tareas/{id}/asignar', [AdminController::class, 'asignarEmpleadosTarea'])->name('admin.tareas.asignar');
    Route::post('/tareas/{id}/duplicar', [AdminController::class, 'duplicarTarea'])->name('admin.tareas.duplicar'); 
    
    // Rutas para Tipos de Tarea
    Route::get('/tipos-tarea', [AdminController::class, 'getTodosTiposTarea'])->name('admin.tipos-tarea.index');
    Route::post('/tipos-tarea', [AdminController::class, 'storeTipoTarea'])->name('admin.tipos-tarea.store');
    Route::get('/tipos-tarea/{id}', [AdminController::class, 'editTipoTarea'])->name('admin.tipos-tarea.edit');
    Route::put('/tipos-tarea/{id}', [AdminController::class, 'updateTipoTarea'])->name('admin.tipos-tarea.update');
    Route::delete('/tipos-tarea/{id}', [AdminController::class, 'destroyTipoTarea'])->name('admin.tipos-tarea.destroy');

    Route::get('tareas/estadisticas', [AdminController::class, 'getTareasEstadisticas'])->name('admin.tareas.estadisticas');

    //crud empleados
    Route::get('/empleados/{id}/edit', [AdminController::class, 'editEmployee'])->name('admin.empleados.edit');
    Route::put('/empleados/{id}', [AdminController::class, 'updateEmployee'])->name('admin.empleados.update');
    Route::get('/empleados/{id}', [AdminController::class, 'show'])->name('admin.empleados.show');
    Route::delete('/empleados/{id}', [AdminController::class, 'destroyEmployee'])->name('admin.empleados.destroy');

    // Nuevas rutas para el perfil del administrador
    Route::get('/profile', [AdminController::class, 'showProfile'])->name('admin.profile');
    Route::post('/profile', [AdminController::class, 'updateProfile'])->name('admin.profile.update');
    Route::get('/admin/stats', [AdminController::class, 'getAdminStats'])->name('admin.stats');

    // Rutas para el dashboard de gráficos
    Route::get('/empleados/estadisticas/graficos', [AdminController::class, 'getEstadisticasGraficos'])->name('admin.empleados.estadisticas.graficos');
        
    Route::get('/empleados/estadisticas/anios', [AdminController::class, 'getAniosDisponibles'])->name('admin.empleados.estadisticas.anios');

});



Route::prefix('empleado')->group(function () {

    // Dashboard individual del empleado con ID en la URL
    Route::get('/perfil/{id}', [EmpleadoController::class, 'perfil'])->name('empleado.perfil');
    // Ruta para el dashboard del empleado (si es necesaria)
    Route::get('/empleado/dashboard', function () {
        return redirect()->route('empleado.perfil', ['id' => Auth::user()->empleado->id ?? 1]);
    })->name('empleado.dashboard_empleado');

    Route::post('/{id}/conexion/actualizar', [EmpleadoController::class, 'actualizarConexion'])->name('empleado.conexion.actualizar');
    Route::get('/{id}/conexion/estado', [EmpleadoController::class, 'getEstadoConexion'])->name('empleado.conexion.estado');

    // Rutas para el control de tiempo con ID
    Route::post('/registro/{id}/start', [EmpleadoController::class, 'startTiempo'])->name('empleado.registro.start');
    Route::post('/registro/{id}/pause', [EmpleadoController::class, 'pauseTiempo'])->name('empleado.registro.pause');
    Route::post('/registro/{id}/stop', [EmpleadoController::class, 'stopTiempo'])->name('empleado.registro.stop');
    
    // Obtener estado actual con ID
    Route::get('/registro/{id}/estado', [EmpleadoController::class, 'getEstado'])->name('empleado.registro.estado');
    
    // Obtener historial con ID
    Route::get('/registro/{id}/historial', [EmpleadoController::class, 'getHistorial'])->name('empleado.registro.historial');
    

    Route::get('/registro/{id}/datatable', [EmpleadoController::class, 'getDataTable'])->name('empleado.registro.datatable');
    Route::get('/registro/{id}/resumen-periodo', [EmpleadoController::class, 'getResumenPeriodo'])->name('empleado.registro.resumen-periodo');

    Route::get('/registro/{id}/estadisticas-mes', [EmpleadoController::class, 'getEstadisticasMes']);

    Route::get('/registro/{empleado}/detalles/{registro}', [EmpleadoController::class, 'getDetallesRegistro'])
    ->name('empleado.registro.detalles');


     // Obtener tipos de tarea
    Route::get('/tipos-tarea', [EmpleadoController::class, 'getTiposTareaEmpleado'])->name('empleado.tipos-tarea');
    
    // Gestión de tareas del empleado
    Route::post('/{id}/tareas/crear', [EmpleadoController::class, 'crearTareaEmpleado'])->name('empleado.tareas.crear');
    Route::post('/{empleadoId}/tareas/{tareaId}/estado', [EmpleadoController::class, 'actualizarEstadoTareaEmpleado'])->name('empleado.tareas.estado');
    Route::get('/{empleadoId}/tareas/{tareaId}/detalles', [EmpleadoController::class, 'getDetallesTareaEmpleado'])->name('empleado.tareas.detalles');
    Route::get('/{id}/tareas/estadisticas', [EmpleadoController::class, 'getEstadisticasTareas'])->name('empleado.tareas.estadisticas');
    Route::put('/{empleado}/tareas/{tarea}/actualizar', [EmpleadoController::class, 'actualizarTareaEmpleado'])->name('empleado.tareas.actualizar');
     // Eliminar tarea
    Route::delete('/tareas/{tarea}', [EmpleadoController::class, 'eliminarTareaEmpleado'])->name('empleado.tareas.eliminar');
    Route::get('/{id}/tareas/datatable', [EmpleadoController::class, 'getTareasDataTable'])->name('empleado.tareas.datatable');

    // Redirección por defecto para empleados
    Route::get('/', function () {
        $empleadoId = Auth::user()->empleado->id;
        return redirect()->route('empleado.individual', ['id' => $empleadoId]);
    })->name('empleado.dashboard');


});







    // Diagnóstico completo de credenciales y empleados
Route::get('/diagnostico-completo', function () {
    
    echo "<h3>🔍 DIAGNÓSTICO COMPLETO - CREDENCIALES Y EMPLEADOS</h3>";
    
    // 1. Todas las credenciales
    $credenciales = DB::table('tabla_credenciales')->get();
    echo "<h4>📋 TOTAL CREDENCIALES: " . $credenciales->count() . "</h4>";
    
    foreach ($credenciales as $credencial) {
        echo "<div style='border: 1px solid #ccc; margin: 10px; padding: 10px;'>";
        echo "<strong>Credencial:</strong> ID: {$credencial->id} | Usuario: {$credencial->username} | Rol ID: {$credencial->id}";
        
        // Buscar empleado vinculado
        $empleado = DB::table('tabla_empleados')
            ->where('credencial_id', $credencial->id)
            ->first();
            
        if ($empleado) {
            echo " | ✅ <strong>EMPLEADO VINCULADO:</strong> ID: {$empleado->id} | Nombre: {$empleado->nombre}";
            echo " | <a href='/empleado/individual/{$empleado->id}' target='_blank'>ACCEDER</a>";
        } else {
            echo " | ❌ <strong>NO TIENE EMPLEADO VINCULADO</strong>";
        }
        
        echo "</div>";
    }
    
    // 2. Empleados sin credencial
    echo "<h4>👥 EMPLEADOS SIN CREDENCIAL:</h4>";
    $empleadosSinCredencial = DB::table('tabla_empleados')
        ->whereNull('credencial_id')
        ->get();
        
    foreach ($empleadosSinCredencial as $empleado) {
        echo "ID: {$empleado->id} | Nombre: {$empleado->nombre} | <em>Sin credencial</em><br>";
    }
    
    return "";
});

// Vincular automáticamente credenciales con empleados
Route::get('/vincular-credenciales', function () {
    
    echo "<h3>🔗 VINCULANDO CREDENCIALES CON EMPLEADOS</h3>";
    
    $credenciales = DB::table('tabla_credenciales')
        ->where('rol_id', 2) // Solo credenciales de empleados
        ->get();
        
    $empleadosSinCredencial = DB::table('tabla_empleados')
        ->whereNull('credencial_id')
        ->get();
    
    echo "Credenciales de empleado: " . $credenciales->count() . "<br>";
    echo "Empleados sin credencial: " . $empleadosSinCredencial->count() . "<br><br>";
    
    $vinculados = 0;
    
    foreach ($credenciales as $index => $credencial) {
        // Verificar si ya tiene empleado
        $empleadoExistente = DB::table('tabla_empleados')
            ->where('credencial_id', $credencial->id)
            ->first();
            
        if ($empleadoExistente) {
            echo "✅ Credencial <strong>{$credencial->username}</strong> ya tiene empleado: {$empleadoExistente->nombre}<br>";
            continue;
        }
        
        // Si no tiene empleado, asignar uno disponible
        if (isset($empleadosSinCredencial[$index])) {
            $empleado = $empleadosSinCredencial[$index];
            
            DB::table('tabla_empleados')
                ->where('id', $empleado->id)
                ->update(['credencial_id' => $credencial->id]);
                
            echo "🔗 VINCULADO: Credencial <strong>{$credencial->username}</strong> → Empleado {$empleado->nombre}<br>";
            $vinculados++;
        }
    }
    
    echo "<br><strong>Total vinculados: {$vinculados}</strong><br>";
    echo "<a href='/diagnostico-completo'>Ver resultado final</a>";
    
    return "";
});

// Corregir roles de credenciales - ESTA ES LA SOLUCIÓN
Route::get('/corregir-roles', function () {
    $html = "<h3>🔧 CORRIGIENDO ROLES DE CREDENCIALES</h3>";
    
    // Admin queda con rol_id = 1
    DB::table('tabla_credenciales')->where('id', 1)->update(['rol_id' => 1]);
    
    // TODOS los demás son empleados (rol_id = 2)
    $result = DB::table('tabla_credenciales')
        ->where('id', '!=', 1)
        ->update(['rol_id' => 2]);
    
    $html .= "✅ Credenciales actualizadas: {$result}<br>";
    
    // Verificar
    $credenciales = DB::table('tabla_credenciales')->get();
    foreach ($credenciales as $credencial) {
        $status = $credencial->rol_id == 1 ? '👑 ADMIN' : '👤 EMPLEADO';
        $html .= "{$status} - {$credencial->username}: Rol ID = {$credencial->rol_id}<br>";
    }
    
    $html .= "<br><a href='/diagnostico-completo'>Ver diagnóstico actualizado</a>";
    $html .= "<br><a href='/login'>Probar login</a>";
    
    return $html;
});

// Redirección por defecto para empleados
/*Route::get('/empleado', function () {
    return redirect()->route('empleado.dashboard_empleado');
});*/
