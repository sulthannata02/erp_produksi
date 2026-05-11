<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('packings', function (Blueprint $table) {
            $table->id();
            $table->string('kode_packing')->nullable();
            $table->foreignId('qc_id')->constrained()->onDelete('cascade');
            $table->integer('jumlah_fg')->default(0);
            $table->integer('jumlah_ng')->default(0);
            $table->integer('jumlah_box')->default(0);
            $table->text('keterangan')->nullable();
            $table->string('operator')->nullable();
            $table->string('status')->default('proses');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packings');
    }
};
