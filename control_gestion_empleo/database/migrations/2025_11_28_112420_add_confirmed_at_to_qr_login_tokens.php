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
        if (Schema::hasTable('tabla_qr_login_tokens')) {
            if (!Schema::hasColumn('tabla_qr_login_tokens', 'confirmed_at')) {
                Schema::table('tabla_qr_login_tokens', function (Blueprint $table) {
                    $table->timestamp('confirmed_at')->nullable()->after('is_confirmed');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('tabla_qr_login_tokens')) {
            if (Schema::hasColumn('tabla_qr_login_tokens', 'confirmed_at')) {
                Schema::table('tabla_qr_login_tokens', function (Blueprint $table) {
                    $table->dropColumn('confirmed_at');
                });
            }
        }
    }
};
