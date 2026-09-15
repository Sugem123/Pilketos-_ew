<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bilik_suara', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bilik', 100);
            $table->string('pairing_code', 20)->unique()->index();
            $table->boolean('is_active')->default(true);
            $table->string('session_token', 64)->nullable()->unique()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('paired_at')->nullable();
            $table->timestamp('last_seen_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bilik_suara');
    }
};
