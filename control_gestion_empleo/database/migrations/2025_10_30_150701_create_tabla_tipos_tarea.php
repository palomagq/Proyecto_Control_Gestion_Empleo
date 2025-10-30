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
        Schema::create('tabla_tipos_tarea', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique();
            $table->string('descripcion')->nullable();
            $table->string('color')->default('#3498db');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        // Insertar tipos de tarea básicos
        DB::table('tabla_tipos_tarea')->insert([
            [
                'nombre' => 'general', 
                'descripcion' => 'Tarea general', 
                'color' => '#3498db',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'desarrollo', 
                'descripcion' => 'Desarrollo de código', 
                'color' => '#2ecc71',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'reunion', 
                'descripcion' => 'Reuniones y coordinación', 
                'color' => '#e74c3c',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'investigacion', 
                'descripcion' => 'Investigación y aprendizaje', 
                'color' => '#9b59b6',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'soporte', 
                'descripcion' => 'Soporte técnico', 
                'color' => '#f39c12',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'administrativo', 
                'descripcion' => 'Tareas administrativas', 
                'color' => '#95a5a6',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tabla_tipos_tarea');
    }
};
