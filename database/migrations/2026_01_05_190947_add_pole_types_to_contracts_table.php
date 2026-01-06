<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('contracts', function (Blueprint $table) {
            // Kolom boolean untuk menandai tipe tiang yang aktif
            $table->boolean('has_9m')->default(false)->after('contract_date');
            $table->boolean('has_12m')->default(false)->after('has_9m');
        });
    }

    public function down()
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn(['has_9m', 'has_12m']);
        });
    }
};