<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Ubah tabel Contracts
        Schema::table('contracts', function (Blueprint $table) {
            // Hapus kolom pole_size karena kontrak sekarang mencakup keduanya
            $table->dropColumn('pole_size');
            
            // Ubah nama kolom stock lama jadi stock_9m (asumsi data lama adalah default/mixed)
            // Atau drop dan buat baru jika data lama tidak penting.
            // Di sini kita rename agar data lama aman, lalu tambah kolom 12m.
            $table->renameColumn('stock', 'stock_9m'); 
            $table->integer('stock_12m')->default(0)->after('stock'); 
        });

        // Ubah tabel Recaps
        Schema::table('recaps', function (Blueprint $table) {
            // Pecah kolom request
            $table->renameColumn('request', 'request_9m');
            $table->integer('request_12m')->default(0)->after('request');

            // Pecah kolom planted
            $table->renameColumn('planted', 'planted_9m');
            $table->integer('planted_12m')->default(0)->after('planted');
        });
    }

    public function down()
    {
        // Logic untuk rollback (opsional tapi disarankan)
        Schema::table('contracts', function (Blueprint $table) {
            $table->enum('pole_size', ['9 meter', '12 meter'])->default('9 meter');
            $table->renameColumn('stock_9m', 'stock');
            $table->dropColumn('stock_12m');
        });

        Schema::table('recaps', function (Blueprint $table) {
            $table->renameColumn('request_9m', 'request');
            $table->dropColumn('request_12m');
            $table->renameColumn('planted_9m', 'planted');
            $table->dropColumn('planted_12m');
        });
    }
};