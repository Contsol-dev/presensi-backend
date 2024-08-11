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
        Schema::create('alumni_files', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('sertifikat')->nullable();
            $table->string('member_card')->nullable();
            $table->string('nilai')->nullable();
            $table->string('whatsapp')->nullable();
            $table->timestamps();

            $table->foreign('username')->references('username')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alumni_files');
    }
};
