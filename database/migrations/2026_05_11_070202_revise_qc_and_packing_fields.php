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
        Schema::table('qcs', function (Blueprint $table) {
            $table->dropColumn(['jumlah_fg', 'jumlah_ng']);
            $table->string('thickness_atas')->nullable()->after('qty_qc');
            $table->string('thickness_bawah')->nullable()->after('thickness_atas');
        });
    }

    public function down(): void
    {
        Schema::table('qcs', function (Blueprint $table) {
            $table->integer('jumlah_fg')->default(0);
            $table->integer('jumlah_ng')->default(0);
            $table->dropColumn(['thickness_atas', 'thickness_bawah']);
        });
    }
};
