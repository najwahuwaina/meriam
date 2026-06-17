<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jurnal_details', function (Blueprint $table) {
            $table->foreign('akun')
                ->references('kode_akun')
                ->on('akun')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('jurnal_details', function (Blueprint $table) {
            $table->dropForeign(['akun']);
        });
    }
};