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
        Schema::table('tabla_empleados', function (Blueprint $table) {
            $table->boolean('en_linea')->default(false);
            $table->timestamp('ultima_conexion')->nullable();
            $table->string('dispositivo_conectado')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('tabla_empleados', function (Blueprint $table) {
            $table->dropColumn(['en_linea', 'ultima_conexion', 'dispositivo_conectado']);
        });
    }
};
