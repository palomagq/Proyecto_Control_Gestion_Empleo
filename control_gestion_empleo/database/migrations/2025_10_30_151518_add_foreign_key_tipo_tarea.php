<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //
        Schema::table('tabla_registros_tiempo', function (Blueprint $table) {
            // Añadir foreign key
            $table->foreign('tipo_tarea_id')
                  ->references('id')
                  ->on('tabla_tipos_tarea')
                  ->onDelete('restrict');
            
            // Añadir índice para mejor performance
            $table->index('tipo_tarea_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('tabla_registros_tiempo', function (Blueprint $table) {
            // Eliminar foreign key
            $table->dropForeign(['tipo_tarea_id']);
            
            // Eliminar índice
            $table->dropIndex(['tipo_tarea_id']);
        });
    }
};
