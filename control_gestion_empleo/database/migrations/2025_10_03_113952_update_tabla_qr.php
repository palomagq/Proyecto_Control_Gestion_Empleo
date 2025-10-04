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
        Schema::table('tabla_qr', function (Blueprint $table) {
            $table->binary('imagen_qr'); // Almacena la imagen del QR en formato binario
            $table->string('codigo_unico')->unique(); // Código único para identificar el QR
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('tabla_qr', function (Blueprint $table) {
            $table->dropColumn(['imagen_qr', 'codigo_unico']);
        });
    }
};
