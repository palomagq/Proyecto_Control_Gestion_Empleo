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
        Schema::create('tabla_qr', function (Blueprint $table) {
            $table->id();
            $table->longBinary('imagen_qr'); // Almacena la imagen del QR en formato binario
            $table->string('codigo_unico')->unique(); // Código único para identificar el QR
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tabla_qr');
    }
};
