<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->id('id');
            $table->string('username');
            $table->date('tanggal');
            $table->time('masuk')->nullable();
            $table->time('istirahat')->nullable();
            $table->time('kembali')->nullable();
            $table->time('pulang')->nullable();
            $table->text('log_activity')->nullable();
            $table->text('kebaikan')->nullable();
            $table->text('catatan')->nullable();
            $table->boolean('terlambat_masuk')->nullable();
            $table->boolean('istirahat_awal')->nullable();
            $table->boolean('terlambat_kembali')->nullable();
            $table->boolean('pulang_awal')->nullable();
            $table->text('kehadiran')->nullable();
            $table->timestamps();

            $table->foreign('username')->references('username')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('logs');
    }
};
