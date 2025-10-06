<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/login',[LoginController::class, 'showLoginForm'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/empleados', [AdminController::class, 'empleados'])->name('admin.empleados');
    Route::post('/empleados/store', [AdminController::class, 'storeEmployee'])->name('admin.empleados.store');
     Route::get('/empleados/datatable', [AdminController::class, 'getEmpleadosDataTable'])->name('admin.empleados.datatable');
     Route::get('/empleados/stats', [AdminController::class, 'getStats'])->name('admin.empleados.stats');

     Route::get('/admin/empleados/buscar-por-dni/{dni}', [AdminController::class, 'buscarPorDni'])->name('admin.empleados.buscar-por-dni');

    Route::get('/admin/empleados/verificar-username/{username}', [AdminController::class, 'verificarUsername'])->name('admin.empleados.verificar-username');
    Route::get('/empleados/exportar-excel-mes', [AdminController::class, 'exportarExcelMes'])->name('admin.empleados.exportar-excel-mes');

    Route::get('/empleados/{id}/edit', [AdminController::class, 'editEmployee'])->name('admin.empleados.edit');
    Route::put('/empleados/{id}', [AdminController::class, 'updateEmployee'])->name('admin.empleados.update');
    Route::get('/empleados/{id}', [AdminController::class, 'show'])->name('admin.empleados.show');
    Route::delete('/empleados/{id}', [AdminController::class, 'destroyEmployee'])->name('admin.empleados.destroy');


});

