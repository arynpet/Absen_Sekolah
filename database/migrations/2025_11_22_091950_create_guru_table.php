<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guru', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke Mata Pelajaran
            $table->unsignedBigInteger('mata_pelajaran_id')->nullable();
            
            $table->string('nama_guru', 100);
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->text('foto_profil')->nullable();
            
            // Kolom untuk menyimpan data wajah (array float32 dari face-api.js)
            $table->text('face_descriptor')->nullable();
            
            $table->timestamp('created_at')->useCurrent();

            // Constraint Foreign Key
            $table->foreign('mata_pelajaran_id')
                  ->references('id')
                  ->on('mata_pelajaran')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guru');
    }
};