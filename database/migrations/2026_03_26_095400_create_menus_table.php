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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('nama_menu'); // Nama
            $table->enum('jenis_menu', ['Menu_Utama', 'Menu_Tambahan','Minuman']); 
            $table->integer('harga'); 
            $table->boolean('is_admin')->default(false); // Admin status (0 = bukan admin, 1 = admin)
            $table->text('content'); //Content 
            $table->timestamps();
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
