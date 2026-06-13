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
    Schema::create('bahan_baku', function (Blueprint $table) {
        $table->id();
        $table->string('kode_bahan');      // contoh: BB001
        $table->string('nama_bahan');
        $table->string('satuan');          // contoh: kg, liter, pcs
        $table->integer('harga_beli');
        $table->integer('stok');
        $table->integer('stok_minimum');   // untuk alert stok menipis
        $table->string('kategori')->nullable();
        $table->string('foto')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bahan_baku');
    }
};
