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

         Schema::create('tabla_registros_tiempo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empleado_id')->constrained('tabla_empleados')->onDelete('cascade');
            $table->timestamp('inicio');
            $table->timestamp('fin');
            $table->enum('estado', ['activo', 'pausado', 'completado'])->default('activo');
            $table->timestamp('pausa_inicio')->nullable();
            $table->integer('tiempo_total');
            $table->timestamps();
            
            $table->index(['empleado_id', 'created_at']);
        });
        
        Schema::create('tabla_eventos_tiempo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registro_id')->constrained('tabla_registros_tiempo')->onDelete('cascade');
            $table->enum('tipo', ['inicio', 'pausa', 'reanudacion', 'fin']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tabla_eventos_tiempo');
        Schema::dropIfExists('tabla_registros_tiempo');
    }
};
