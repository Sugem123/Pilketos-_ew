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
        Schema::table('votes', function (Blueprint $table) {
            $table->string('status_verifikasi', 20)->default('pending')->after('id_nisn'); // pending, sah, tidak_sah
            $table->string('catatan_verifikasi', 255)->nullable()->after('status_verifikasi');
            $table->timestamp('verified_at')->nullable()->after('catatan_verifikasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            $table->dropColumn(['status_verifikasi', 'catatan_verifikasi', 'verified_at']);
        });
    }
};

