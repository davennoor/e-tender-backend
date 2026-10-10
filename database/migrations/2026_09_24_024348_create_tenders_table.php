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
        Schema::create('tenders', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tender');
            $table->string('instansi');
            $table->string('lokasi_pekerjaan')->nullable();
            $table->string('kota_pengadaan');
            $table->date('tanggal_tender');
            $table->integer('tahun_anggaran');
            $table->integer('durasi');
            $table->bigInteger('nilai_penawaran');
            $table->string('file_mdp')->nullable();
            $table->enum('status_generate',['draft','generated'])->default('draft');
            $table->foreignId('perusahaan_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenders');
    }
};
