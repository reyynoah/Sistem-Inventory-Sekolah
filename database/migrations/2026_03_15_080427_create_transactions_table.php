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
    Schema::create('transactions', function (Blueprint $table) {
        $table->id();
        // Relasi ke tabel users (petugas) dan items (barang)
        $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
        $table->foreignId('item_id')->constrained('items')->cascadeOnDelete();
        $table->date('borrow_date'); // Tanggal pinjam
        $table->date('return_date')->nullable(); // Tanggal kembali (bisa kosong dulu)
        $table->enum('status', ['dipinjam', 'dikembalikan'])->default('dipinjam');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
