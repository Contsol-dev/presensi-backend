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
        Schema::create('detail_users', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->string('nama');
            $table->string('asal_sekolah');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('nomor_hp');
            $table->string('nip', 255);
            $table->string('os');
            $table->string('browser');
            $table->timestamps();

            $table->foreign('id')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('nip')->references('nip')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_users');
    }
};
