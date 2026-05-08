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
        Schema::create('pembayaran', function (Blueprint $table) {
    $table->id();

    $table->unsignedBigInteger('id_pesanan');

    $table->date('tgl_bayar');
    $table->integer('subtotal');
    $table->integer('ppn');
    $table->integer('total_bayar');

    $table->timestamps();

    $table->foreign('id_pesanan')
        ->references('id_pesanan')
        ->on('pesanan')
        ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
