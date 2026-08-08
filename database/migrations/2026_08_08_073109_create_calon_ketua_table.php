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
        Schema::create('calon_ketua', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 256);
            $table->integer('nomor');
            $table->string('visi', 521);
            $table->string('misi', 1000);
            $table->foreignId('id_kelas')->constrained('kelas')->cascadeOnDelete();
            $table->string('url_foto', 521)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calon_ketua');
    }
};
