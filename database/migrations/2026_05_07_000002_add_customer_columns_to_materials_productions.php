<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // === materials: tambah nama_customer dan kode_part ===
        Schema::table('materials', function (Blueprint $table) {
            if (!Schema::hasColumn('materials', 'nama_customer')) {
                $table->string('nama_customer')->nullable()->after('nama_material');
            }
            if (!Schema::hasColumn('materials', 'kode_part')) {
                $table->string('kode_part')->nullable()->after('nama_customer');
            }
            if (!Schema::hasColumn('materials', 'tanggal_masuk')) {
                $table->date('tanggal_masuk')->nullable()->after('kode_part');
            }
        });

        // === productions: tambah tanggal_produksi ===
        Schema::table('productions', function (Blueprint $table) {
            if (!Schema::hasColumn('productions', 'tanggal_produksi')) {
                $table->date('tanggal_produksi')->nullable()->after('operator');
            }
        });
    }

    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            $cols = [];
            if (Schema::hasColumn('materials', 'nama_customer')) $cols[] = 'nama_customer';
            if (Schema::hasColumn('materials', 'kode_part')) $cols[] = 'kode_part';
            if ($cols) $table->dropColumn($cols);
        });

        Schema::table('productions', function (Blueprint $table) {
            if (Schema::hasColumn('productions', 'tanggal_produksi')) {
                $table->dropColumn('tanggal_produksi');
            }
        });
    }
};
