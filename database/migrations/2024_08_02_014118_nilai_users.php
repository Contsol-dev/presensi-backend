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
        Schema::create('nilai_users', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('subcategory_id');
            $table->float('nilai');
            $table->foreign('category_id')->references('id')->on('nilai_categories')->onDelete('cascade');
            $table->foreign('subcategory_id')->references('id')->on('nilai_subcategories')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_users');
    }
};
