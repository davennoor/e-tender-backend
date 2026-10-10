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
        Schema::create('dokumen_pendukungs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_dokumen');
            $table->string('jenis_dokumen');
            $table->date('tanggal_upload');
            $table->longText('file_dokumen');
            $table->enum('status_dokumen',['aktif','tidak_aktif'])->default('aktif');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen_pendukungs');
        
    }
};
