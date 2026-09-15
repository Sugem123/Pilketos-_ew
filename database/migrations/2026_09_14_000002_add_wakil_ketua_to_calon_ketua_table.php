<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('calon_ketua', function (Blueprint $table) {
            $table->string('nama_wakil_1', 256)->nullable()->after('nama');
            $table->foreignId('id_kelas_wakil_1')->nullable()->after('id_kelas')->constrained('kelas')->nullOnDelete();
            $table->string('nama_wakil_2', 256)->nullable()->after('nama_wakil_1');
            $table->foreignId('id_kelas_wakil_2')->nullable()->after('id_kelas_wakil_1')->constrained('kelas')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('calon_ketua', function (Blueprint $table) {
            $table->dropForeign(['id_kelas_wakil_1']);
            $table->dropForeign(['id_kelas_wakil_2']);
            $table->dropColumn(['nama_wakil_1', 'id_kelas_wakil_1', 'nama_wakil_2', 'id_kelas_wakil_2']);
        });
    }
};
