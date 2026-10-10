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
        Schema::create('pengalaman_proyeks', function (Blueprint $table) {
            $table->id();
            $table->string('nama_proyek')->unique();
            $table->string('pemberi_kerja');
            $table->integer('tahun_pelaksanaan');
            $table->bigInteger('nilai_kontrak');
            $table->string('lokasi_proyek');
            $table->string('status_proyek');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengalaman_proyeks');
    }
};
