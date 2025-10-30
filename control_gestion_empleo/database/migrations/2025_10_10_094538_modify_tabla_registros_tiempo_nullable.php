<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // ← AÑADE ESTA LÍNEA

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tabla_registros_tiempo', function (Blueprint $table) {
            
            // Hacer las columnas nullable
            $table->dateTime('fin')->nullable()->change();
            $table->integer('tiempo_total')->nullable()->change();
            $table->string('estado', 50)->nullable()->change();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tabla_registros_tiempo', function (Blueprint $table) {
            // Revertir los cambios
            $table->dateTime('fin')->nullable(false)->change();
            $table->integer('tiempo_total')->nullable(false)->change();
            $table->string('estado', 50)->nullable(false)->change();
        });
    }
};
