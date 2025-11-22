<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('info_sekolah', function (Blueprint $table) {
            $table->id();
            $table->string('judul_kegiatan');
            $table->string('waktu_kegiatan', 100);
            $table->date('tanggal_kegiatan');
            $table->text('deskripsi')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('info_sekolah');
    }
};