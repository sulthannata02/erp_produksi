<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Kolom fg dan ng adalah kolom lama yang redundant.
        // Kasih default value 0 agar tidak error saat insert.
        Schema::table('packings', function (Blueprint $table) {
            $table->integer('fg')->default(0)->change();
            $table->integer('ng')->default(0)->change();
        });
    }

    public function down(): void
    {
        Schema::table('packings', function (Blueprint $table) {
            $table->integer('fg')->default(null)->change();
            $table->integer('ng')->default(null)->change();
        });
    }
};
