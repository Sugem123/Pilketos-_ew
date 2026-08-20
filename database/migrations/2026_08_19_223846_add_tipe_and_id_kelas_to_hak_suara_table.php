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
            $table->string('tipe', 20)->default('siswa')->after('nisn');
            $table->foreignId('id_kelas')->nullable()->after('tipe')->constrained('kelas')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hak_suara', function (Blueprint $table) {
            $table->dropForeign(['id_kelas']);
            $table->dropColumn(['tipe', 'id_kelas']);
        });
    }
};

