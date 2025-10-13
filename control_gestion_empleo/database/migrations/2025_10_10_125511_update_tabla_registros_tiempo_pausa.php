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
        // Verificar y añadir columnas que no existan
        if (!Schema::hasColumn('tabla_registros_tiempo', 'pausa_inicio')) {
            Schema::table('tabla_registros_tiempo', function (Blueprint $table) {
                $table->timestamp('pausa_inicio')->nullable()->after('estado');
            });
        }

        if (!Schema::hasColumn('tabla_registros_tiempo', 'pausa_fin')) {
            Schema::table('tabla_registros_tiempo', function (Blueprint $table) {
                $table->timestamp('pausa_fin')->nullable()->after('pausa_inicio');
            });
        }

        if (!Schema::hasColumn('tabla_registros_tiempo', 'tiempo_pausa_total')) {
            Schema::table('tabla_registros_tiempo', function (Blueprint $table) {
                $table->integer('tiempo_pausa_total')->default(0)->after('tiempo_total');
            });
        }
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
