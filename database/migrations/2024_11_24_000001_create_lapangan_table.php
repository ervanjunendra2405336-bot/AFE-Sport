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
        Schema::create('lapangan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sport_category_id')->constrained('sport_categories')->onDelete('cascade');
            $table->string('nama');
            $table->string('kode_lapangan')->unique(); // contoh: FTS-001, BSK-001
            $table->text('deskripsi');
            $table->string('tipe'); // indoor/outdoor
            $table->string('lokasi')->nullable(); // lokasi spesifik di venue
            $table->string('kota')->nullable(); // Jakarta Selatan, Bandung, dll
            $table->string('alamat')->nullable();
            $table->decimal('harga_per_jam', 10, 2);
            $table->decimal('harga_weekend', 10, 2)->nullable(); // harga berbeda untuk weekend
            $table->string('foto')->nullable();
            $table->json('galeri')->nullable(); // multiple photos
            $table->json('fasilitas')->nullable(); // array fasilitas: ['Kantin', 'Parkir', 'Toilet', 'Mushola']
            $table->integer('kapasitas')->nullable(); // jumlah orang
            $table->string('ukuran')->nullable(); // misal: 40x20m untuk futsal
            $table->text('aturan')->nullable(); // peraturan khusus lapangan
            $table->integer('jumlah_lapangan')->default(1); // jumlah lapangan di venue
            $table->boolean('tersedia')->default(true);
            $table->integer('rating')->default(0); // rating 0-5
            $table->integer('jumlah_review')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lapangan');
    }
};
