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
        Schema::table('hak_suara', function (Blueprint $table) {
            $table->string('token', 20)->nullable()->unique()->after('id_kelas');
            $table->boolean('token_used')->default(false)->after('token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hak_suara', function (Blueprint $table) {
            $table->dropColumn(['token', 'token_used']);
        });
    }
};

