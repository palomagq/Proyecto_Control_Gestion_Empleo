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
            $table->integer('precision_gps')->nullable()->after('longitud');
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
