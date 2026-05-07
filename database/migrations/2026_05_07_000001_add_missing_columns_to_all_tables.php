<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // === materials: tambah satuan & gambar ===
        Schema::table('materials', function (Blueprint $table) {
            if (!Schema::hasColumn('materials', 'satuan')) {
                $table->string('satuan')->default('Pcs')->after('jumlah');
            }
            if (!Schema::hasColumn('materials', 'gambar')) {
                $table->string('gambar')->nullable()->after('satuan');
            }
        });

        // === productions: tambah kode_produksi & operator ===
        Schema::table('productions', function (Blueprint $table) {
            if (!Schema::hasColumn('productions', 'kode_produksi')) {
                $table->string('kode_produksi')->nullable()->after('id');
            }
            if (!Schema::hasColumn('productions', 'operator')) {
                $table->string('operator')->nullable()->after('jumlah_produksi');
            }
        });

        // === qcs: tambah qty_qc & keterangan, pastikan hasil & status konsisten ===
        Schema::table('qcs', function (Blueprint $table) {
            if (!Schema::hasColumn('qcs', 'qty_qc')) {
                $table->integer('qty_qc')->nullable()->after('production_id');
            }
            if (!Schema::hasColumn('qcs', 'keterangan')) {
                $table->text('keterangan')->nullable()->after('hasil');
            }
        });

        // === packings: tambah kode_packing, operator, status, keterangan ===
        Schema::table('packings', function (Blueprint $table) {
            if (!Schema::hasColumn('packings', 'kode_packing')) {
                $table->string('kode_packing')->nullable()->after('id');
            }
            if (!Schema::hasColumn('packings', 'keterangan')) {
                $table->text('keterangan')->nullable()->after('jumlah_ng');
            }
            if (!Schema::hasColumn('packings', 'operator')) {
                $table->string('operator')->nullable()->after('keterangan');
            }
            if (!Schema::hasColumn('packings', 'status')) {
                $table->string('status')->default('proses')->after('operator');
            }
        });
    }

    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->dropColumn(['satuan', 'gambar']);
        });

        Schema::table('productions', function (Blueprint $table) {
            $table->dropColumn(['kode_produksi', 'operator']);
        });

        Schema::table('qcs', function (Blueprint $table) {
            $table->dropColumn(['qty_qc', 'keterangan']);
        });

        Schema::table('packings', function (Blueprint $table) {
            $table->dropColumn(['kode_packing', 'keterangan', 'operator', 'status']);
        });
    }
};
