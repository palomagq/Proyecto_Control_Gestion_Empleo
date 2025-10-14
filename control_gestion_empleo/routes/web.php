<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EmpleadoController;
    use Illuminate\Support\Facades\DB;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/empleados', [AdminController::class, 'empleados'])->name('admin.empleados');
    Route::post('/empleados/store', [AdminController::class, 'storeEmployee'])->name('admin.empleados.store');
    Route::get('/empleados/datatable', [AdminController::class, 'getEmpleadosDataTable'])->name('admin.empleados.datatable');
    Route::get('/empleados/stats', [AdminController::class, 'getStats'])->name('admin.empleados.stats');

    Route::get('/empleados/buscar-por-dni/{dni}', [AdminController::class, 'buscarPorDni'])->name('admin.empleados.buscar-por-dni');

    Route::get('/empleados/verificar-username/{username}', [AdminController::class, 'verificarUsername'])->name('admin.empleados.verificar-username');
    Route::get('/empleados/exportar-excel-mes', [AdminController::class, 'exportarExcelMes'])->name('admin.empleados.exportar-excel-mes');
     // Rutas para gestión de QR
    Route::get('/empleados/{id}/qr-info', [AdminController::class, 'getQRInfo'])->name('admin.empleados.qr-info');
    Route::post('/empleados/generar-qr-preview', [AdminController::class, 'generarQRPreview'])->name('admin.empleados.generar-qr-preview');
    Route::post('/empleados/{id}/enviar-whatsapp', [AdminController::class, 'enviarQRWhatsApp'])->name('admin.empleados.enviar-whatsapp');
    Route::get('/empleados/{id}/generar-pdf', [AdminController::class, 'generarPDFQR'])->name('admin.empleados.generar-pdf-qr');


    // Rutas para los registros del empleado en el modal de vista
    Route::get('/empleados/{id}/registros/datatable', [AdminController::class, 'getRegistrosDataTable'])->name('admin.empleados.registros.datatable');
    Route::get('/empleados/{id}/registros/resumen', [AdminController::class, 'getResumenRegistros'])->name('admin.empleados.registros.resumen');

    Route::get('/empleados/{id}/edit', [AdminController::class, 'editEmployee'])->name('admin.empleados.edit');
    Route::put('/empleados/{id}', [AdminController::class, 'updateEmployee'])->name('admin.empleados.update');
    Route::get('/empleados/{id}', [AdminController::class, 'show'])->name('admin.empleados.show');
    Route::delete('/empleados/{id}', [AdminController::class, 'destroyEmployee'])->name('admin.empleados.destroy');


    
});



Route::prefix('empleado')->group(function () {

    // Dashboard individual del empleado con ID en la URL
    Route::get('/perfil/{id}', [EmpleadoController::class, 'perfil'])->name('empleado.perfil');
    // Ruta para el dashboard del empleado (si es necesaria)
    Route::get('/empleado/dashboard', function () {
        return redirect()->route('empleado.perfil', ['id' => Auth::user()->empleado->id ?? 1]);
    })->name('empleado.dashboard_empleado');

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
