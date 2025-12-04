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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lapangan_id')->constrained('lapangan')->onDelete('cascade');
            $table->string('nama_pemesan');
            $table->string('email');
            $table->string('telepon');
            $table->date('tanggal_booking');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->integer('durasi_jam');
            $table->decimal('total_harga', 10, 2);
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
            $table->string('metode_pembayaran')->nullable(); // transfer, cash
            $table->string('bukti_pembayaran')->nullable(); // path file upload
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
