<?php
//coba
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('karyawan', function (Blueprint $table) {
            $table->id('id_karyawan');              // Primary key
            $table->string('nama_karyawan');        // Nama pegawai
            $table->string('no_telp')->nullable();  // Nomor telepon
            $table->text('alamat')->nullable();     // Alamat
            $table->string('jabatan');              // Jabatan
            $table->date('tanggal_lahir')->nullable(); // ✅ Tambahan field tanggal lahir
            $table->string('foto_ektp')->nullable();   // ✅ Tambahan field upload foto e-KTP
            $table->timestamps();                   // created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('karyawan');
    }
};
