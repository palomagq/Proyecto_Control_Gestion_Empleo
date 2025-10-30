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
            //
            $table->decimal('latitud', 10, 8)->nullable()->after('estado');
            $table->decimal('longitud', 11, 8)->nullable()->after('latitud');
            $table->string('direccion')->nullable()->after('longitud');
            $table->string('ciudad')->nullable()->after('direccion');
            $table->string('pais')->nullable()->after('ciudad');
            $table->string('dispositivo')->nullable()->after('pais');
            $table->string('ip_address')->nullable()->after('dispositivo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tabla_registros_tiempo', function (Blueprint $table) {
            //
        });
    }
};
