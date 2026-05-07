<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
 public function up()
{
    Schema::table('qcs', function (Blueprint $table) {
        $table->integer('approve')->nullable();
        $table->integer('reject')->nullable();
    });
}

public function down()
{
    Schema::table('qcs', function (Blueprint $table) {
        $table->dropColumn(['approve', 'reject']);
    });
}
};
