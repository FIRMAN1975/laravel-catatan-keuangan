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
        Schema::create('cashflows', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->enum('type', ['inflow', 'outflow']); // jenis transaksi: pemasukan/pengeluaran
            $table->enum('source', ['cash', 'savings', 'loans']); // sumber dana
            $table->string('label'); // kategori ringkas, contoh: “Gaji”, “Makan”, “Transport”
            $table->text('description')->nullable(); // deskripsi lengkap transaksi
            $table->decimal('amount', 15, 2); // nominal uang
            $table->string('cover')->nullable(); // gambar bukti transaksi
            $table->timestamps();

            // relasi ke users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cashflows');
    }
};
