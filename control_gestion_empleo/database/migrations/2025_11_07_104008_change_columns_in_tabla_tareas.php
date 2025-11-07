<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('tabla_tareas', function (Blueprint $table) {
            // Eliminar las columnas antiguas
            $table->dropColumn(['fecha_inicio', 'fecha_fin', 'hora_inicio', 'hora_fin']);
            
            // Agregar las nuevas columnas
            $table->date('fecha_tarea')->after('prioridad');
            $table->decimal('horas_tarea', 4, 2)->default(0)->after('fecha_tarea'); // 999.99 horas máximo
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('tabla_tareas', function (Blueprint $table) {
            // Revertir los cambios
            $table->dropColumn(['fecha_tarea', 'horas_tarea']);
            
            $table->date('fecha_inicio')->after('prioridad');
            $table->date('fecha_fin')->after('fecha_inicio');
            $table->time('hora_inicio')->nullable()->after('fecha_fin');
            $table->time('hora_fin')->nullable()->after('hora_inicio');
        });
    }
};
