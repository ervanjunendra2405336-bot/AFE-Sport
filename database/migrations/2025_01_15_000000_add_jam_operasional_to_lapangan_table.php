<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('lapangan', function (Blueprint $table) {
            $table->time('jam_buka')->default('06:00:00')->after('jumlah_lapangan');
            $table->time('jam_tutup')->default('23:00:00')->after('jam_buka');
        });
    }

    public function down()
    {
        Schema::table('lapangan', function (Blueprint $table) {
            $table->dropColumn(['jam_buka', 'jam_tutup']);
        });
    }
};
