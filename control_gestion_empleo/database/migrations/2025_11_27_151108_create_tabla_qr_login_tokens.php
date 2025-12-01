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
        Schema::create('tabla_qr_login_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('token')->unique();
            $table->foreignId('empleado_id')->constrained('tabla_empleados')->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_confirmed')->default(false);
            $table->timestamp('expires_at');
            $table->timestamps();
            
            // Índices para mejor rendimiento
            $table->index(['token', 'is_active']);
            $table->index(['empleado_id', 'is_active']);
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tabla_qr_login_tokens');
    }
};
