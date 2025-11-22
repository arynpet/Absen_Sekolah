<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kehadiran', function (Blueprint $table) {
            // tambahkan kolom jam_datang dan jam_pulang
            $table->time('jam_datang')->nullable()->after('tanggal');
            $table->time('jam_pulang')->nullable()->after('jam_datang');
        });
    }

    public function down(): void
    {
        Schema::table('kehadiran', function (Blueprint $table) {
            $table->dropColumn(['jam_datang', 'jam_pulang']);
        });
    }
};
