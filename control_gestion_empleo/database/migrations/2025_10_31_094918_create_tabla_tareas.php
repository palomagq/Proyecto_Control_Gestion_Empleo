<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tabla_tareas', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->string('area')->nullable(); // Proyecto/área de la tarea
            
            // Relación con tipo de tarea
            $table->unsignedBigInteger('tipo_tarea_id');
            
            // Campos de estado y prioridad
            $table->enum('prioridad', ['baja', 'media', 'alta', 'urgente'])->default('media');
            $table->enum('estado', ['pendiente', 'en_progreso', 'completada', 'cancelada'])->default('pendiente');
            $table->date('fecha_limite')->nullable();
            
            // Creador de la tarea (puede ser admin O empleado)
            $table->enum('creador_tipo', ['admin', 'empleado'])->default('admin');
            
            // Si es admin, guardamos el admin_id
            $table->unsignedBigInteger('admin_creador_id')->nullable();
            
            // Si es empleado, guardamos el empleado_id  
            $table->unsignedBigInteger('empleado_creador_id')->nullable();
            
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('tipo_tarea_id')->references('id')->on('tabla_tipos_tarea')->onDelete('restrict');
            $table->foreign('admin_creador_id')->references('id')->on('tabla_admin')->onDelete('cascade');
            $table->foreign('empleado_creador_id')->references('id')->on('tabla_empleados')->onDelete('cascade');
            
            // Índices
            $table->index('creador_tipo');
            $table->index('admin_creador_id');
            $table->index('empleado_creador_id');
            $table->index('area');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tabla_tareas');
    }
};