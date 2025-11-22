<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kehadiran', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('guru_id');
            $table->unsignedBigInteger('mata_pelajaran_id')->nullable();
            
            $table->enum('status', ['Hadir', 'Sakit', 'Izin', 'Alpa']);
            $table->time('jam_datang')->nullable();
            $table->time('jam_pulang')->nullable();
            $table->time('waktu')->nullable();
            $table->text('bukti_kehadiran')->nullable();
            $table->date('tanggal')->nullable();
            
            $table->timestamps();

            // Constraint Foreign Key
            $table->foreign('guru_id')
                  ->references('id')
                  ->on('guru')
                  ->onDelete('cascade');

            $table->foreign('mata_pelajaran_id')
                  ->references('id')
                  ->on('mata_pelajaran')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kehadiran');
    }
};