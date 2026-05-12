<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penggajian', function (Blueprint $table) {

            $table->id('id_penggajian');

            $table->unsignedBigInteger('id_karyawan');

            $table->integer('bulan');

            $table->year('tahun');

            $table->integer('jumlah_hadir')->default(0);

            $table->integer('jumlah_izin')->default(0);

            $table->integer('jumlah_sakit')->default(0);

            $table->integer('jumlah_alpa')->default(0);

            $table->double('gaji_per_hari');

            $table->double('total_gaji')->default(0);

            $table->timestamps();

            $table->foreign('id_karyawan')
                ->references('id_karyawan')
                ->on('karyawan')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penggajian');
    }
};