<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
         // Obtener el ID del tipo 'general' para valor por defecto
        $generalId = DB::table('tabla_tipos_tarea')->where('nombre', 'general')->value('id');

        Schema::table('tabla_registros_tiempo', function (Blueprint $table) use ($generalId) {
            // Añadir tipo_tarea_id
            $table->unsignedBigInteger('tipo_tarea_id')
                  ->after('empleado_id')
                  ->nullable()
                  ->default($generalId);

            // Añadir nombre de la tarea
            $table->string('tarea')
                  ->after('tipo_tarea_id')
                  ->default('Tarea sin nombre');

            // Añadir descripción de la tarea
            $table->text('descripcion_tarea')
                  ->after('tarea')
                  ->nullable();
        });

        // Actualizar registros existentes para asegurar que tienen valor
        DB::table('tabla_registros_tiempo')
            ->whereNull('tipo_tarea_id')
            ->update(['tipo_tarea_id' => $generalId]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('tabla_registros_tiempo', function (Blueprint $table) {
            $table->dropColumn(['tipo_tarea_id', 'tarea', 'descripcion_tarea']);
        });
    }
};
