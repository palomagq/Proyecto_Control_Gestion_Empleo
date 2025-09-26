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
     Route::delete('/empleados/{id}', [AdminController::class, 'destroyEmployee'])->name('admin.empleados.destroy');
});