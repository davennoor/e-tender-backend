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
        Schema::create('personels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perusahaan_id')->constrained();
            $table->string('nama_personel');
            $table->string('jabatan');
            $table->string('pendidikan');
            $table->string('sertifikasi');
            $table->integer('pengalaman_kerja');
            $table->string('nomor_sertifikat')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personels');
    }
};
