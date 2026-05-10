<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('nama_customer');
            $table->string('nama_material');
            $table->string('kode_part');
            $table->integer('jumlah')->default(0);
            $table->integer('aktual_stok')->default(0);
            $table->integer('qty_per_hanger')->default(0);
            $table->integer('qty_per_box')->default(0);
            $table->string('satuan')->default('Pcs');
            $table->string('gambar')->nullable();
            $table->date('tanggal_masuk');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
