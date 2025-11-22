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
            Schema::create('gurus', function (Blueprint $table) {
                $table->id();
                $table->string('nama');
                $table->string('nip')->unique();
                $table->string('mapel');
                $table->string('status')->default('Hadir'); // Hadir / Izin / Sakit
                $table->date('tanggal')->nullable();
                $table->text('bio')->nullable();
                $table->string('foto')->nullable();
                $table->timestamps();
            });
        }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gurus');
    }
};
