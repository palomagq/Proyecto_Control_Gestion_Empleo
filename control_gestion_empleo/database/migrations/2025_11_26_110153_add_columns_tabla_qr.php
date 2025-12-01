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
            $table->text('contenido_qr')->nullable(); // Contenido/texto del QR
            $table->foreignId('empleado_id')->nullable()->constrained('tabla_empleados')->onDelete('cascade');
            $table->boolean('activo')->default(true);
            $table->timestamp('expiracion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
