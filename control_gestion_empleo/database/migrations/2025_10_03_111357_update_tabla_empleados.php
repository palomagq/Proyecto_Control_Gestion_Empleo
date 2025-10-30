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
        //
        Schema::table('tabla_empleados', function (Blueprint $table) {
            $table->string('telefono')->nullable()->after('domicilio');
            $table->foreignId('qr_id')->nullable()->constrained('tabla_qr')->onDelete('set null')->after('credencial_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('tabla_empleados', function (Blueprint $table) {
            $table->dropForeign(['qr_id']);
            $table->dropColumn(['telefono', 'qr_id']);
        });
    }
};
