<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('calon_ketua', function (Blueprint $table) {
            $table->string('tipe', 20)->default('osis')->after('id')->index();
        });

        Schema::table('votes', function (Blueprint $table) {
            $table->string('tipe_pemilihan', 20)->default('osis')->after('id_nisn')->index();
        });
    }

    public function down(): void
    {
        Schema::table('calon_ketua', function (Blueprint $table) {
            $table->dropIndex(['tipe']);
            $table->dropColumn('tipe');
        });

        Schema::table('votes', function (Blueprint $table) {
            $table->dropIndex(['tipe_pemilihan']);
            $table->dropColumn('tipe_pemilihan');
        });
    }
};
