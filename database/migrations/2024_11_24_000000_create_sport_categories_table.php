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
        Schema::create('sport_categories', function (Blueprint $table) {
            $table->id();
            $table->string('nama'); // Futsal, Basket, Tenis, Badminton, Voli, dll
            $table->string('slug')->unique();
            $table->text('deskripsi')->nullable();
            $table->string('icon')->nullable(); // untuk icon kategori
            $table->string('gambar')->nullable(); // gambar representasi olahraga
            $table->boolean('aktif')->default(true);
            $table->integer('urutan')->default(0); // untuk sorting
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sport_categories');
    }
};
