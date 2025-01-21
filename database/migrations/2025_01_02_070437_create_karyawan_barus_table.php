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
        Schema::create('karyawan_barus', function (Blueprint $table) {
            $table->id();
            $table->nik();
            $table->string('nama');
            $table->unsignedBigInteger('level');
            $table->unsignedBigInteger('workplace');
            $table->string('tempat_lahir');
            $table->timestamps('tgl_lahir');
            $table->timestamps('tgl_masuk');
            $table->timestamps();

            // Foreign key constraints  
            $table->foreign('level')->references('id')->on('posisi')->onDelete('cascade');  
            $table->foreign('workplace')->references('id')->on('departemen')->onDelete('cascade');  
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawan_barus');
    }
};
