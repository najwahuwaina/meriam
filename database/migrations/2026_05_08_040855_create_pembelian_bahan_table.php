<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembelian_bahan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pembelian')->unique();
            $table->foreignId('bahan_baku_id')
                  ->constrained('bahan_baku')
                  ->cascadeOnDelete();
            $table->foreignId('supplier_id')
                  ->nullable()
                  ->constrained('suppliers')
                  ->nullOnDelete();
            $table->date('tanggal');
            $table->integer('jumlah');
            $table->decimal('harga_beli', 12, 2);
            $table->decimal('tagihan', 12, 2)->default(0);
            $table->decimal('dibayar', 12, 2)->default(0);
            $table->decimal('sisa', 12, 2)->default(0);
            $table->enum('status_pembayaran', ['belum_bayar', 'sebagian', 'lunas'])
                  ->default('belum_bayar');
            $table->enum('metode_pembayaran', ['cash', 'debit', 'kredit'])
                  ->nullable();
            $table->date('jatuh_tempo')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('foto_struk')->nullable();
            $table->timestamps();
            $table->index(['bahan_baku_id', 'tanggal']);
        });
    }

    public function down(): void // fix typo 'voids'
    {
        Schema::dropIfExists('pembelian_bahan');
    }
};