<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tabla_asignaciones_tareas', function (Blueprint $table) {
            $table->id();
            
            // Relación con la tarea
            $table->unsignedBigInteger('tarea_id');
            
            // Relación con el empleado asignado
            $table->unsignedBigInteger('empleado_id');
            
            // Estado específico de esta asignación
            $table->enum('estado_asignacion', ['asignada', 'en_progreso', 'completada', 'rechazada'])->default('asignada');
            $table->text('comentarios')->nullable();
            
            // Fechas importantes
            $table->timestamp('fecha_asignacion')->useCurrent();
            $table->timestamp('fecha_completado')->nullable();
            
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('tarea_id')->references('id')->on('tabla_tareas')->onDelete('cascade');
            $table->foreign('empleado_id')->references('id')->on('tabla_empleados')->onDelete('cascade');
            
            // Índice único para evitar duplicados
            $table->unique(['tarea_id', 'empleado_id']);
            
            // Índices para mejor performance
            $table->index('estado_asignacion');
            $table->index('fecha_asignacion');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tabla_asignaciones_tareas');
    }
};