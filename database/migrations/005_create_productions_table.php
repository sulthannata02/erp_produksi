<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productions', function (Blueprint $table) {
            $table->id();
            $table->string('kode_produksi')->nullable();
            $table->foreignId('material_id')->constrained()->onDelete('cascade');
            $table->integer('jumlah_hanger')->default(0);
            $table->integer('jumlah_produksi');
            $table->string('operator')->nullable();
            $table->date('tanggal_produksi');
            $table->string('status')->default('proses');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productions');
    }
};
