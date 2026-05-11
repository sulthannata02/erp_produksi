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
        Schema::table('productions', function (Blueprint $table) {
            $table->integer('target_hanger')->after('material_id')->nullable()->comment('Input by Admin as Blueprint');
            // Change default status to 'rencana'
            $table->string('status')->default('rencana')->change();
        });
    }

    public function down(): void
    {
        Schema::table('productions', function (Blueprint $table) {
            $table->dropColumn('target_hanger');
        });
    }
};
