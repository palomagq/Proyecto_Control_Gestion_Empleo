<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tabla_tareas', function (Blueprint $table) {
            // Eliminar fecha_limite y agregar fecha_inicio y fecha_fin
            $table->dropColumn('fecha_limite');
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->time('hora_inicio')->nullable();
            $table->time('hora_fin')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('tabla_tareas', function (Blueprint $table) {
            $table->date('fecha_limite')->nullable();
            $table->dropColumn(['fecha_inicio', 'fecha_fin', 'hora_inicio', 'hora_fin']);
        });
    }
};
