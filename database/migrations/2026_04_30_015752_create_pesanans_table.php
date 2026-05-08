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
       Schema::create('pesanan', function (Blueprint $table) {
    $table->id('id_pesanan');

    $table->unsignedBigInteger('id_pelanggan');
    $table->unsignedBigInteger('id_karyawan');

    $table->date('tgl_pesanan');
    $table->integer('total_harga')->default(0);

    $table->timestamps();

    $table->foreign('id_pelanggan')
        ->references('id_pelanggan')
        ->on('pelanggan')
        ->onDelete('cascade');

    $table->foreign('id_karyawan')
        ->references('id_karyawan')
        ->on('karyawan')
        ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
