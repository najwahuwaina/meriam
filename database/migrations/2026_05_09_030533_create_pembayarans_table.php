<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('id_pesanan');

            $table->date('tgl_bayar');

            $table->decimal('subtotal', 12, 2);

            $table->decimal('tarif_ppn', 5, 2)->default(11);

            $table->decimal('subtotal_stlh_ppn', 12, 2);

            $table->decimal('jumlah', 12, 2);

            $table->timestamps();

            $table->foreign('id_pesanan')
                ->references('id_pesanan')
                ->on('pesanan')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};