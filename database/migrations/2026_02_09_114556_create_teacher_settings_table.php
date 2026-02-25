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
        Schema::create('teacher_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('teacher_id')->unique()->comment('Guru');
            $table->boolean('show_correct_answers')->default(false)->comment('Boleh siswa lihat jawaban benar');
            $table->boolean('show_wrong_answers')->default(false)->comment('Boleh siswa lihat jawaban salah');
            $table->boolean('show_score')->default(true)->comment('Boleh siswa lihat skor');
            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_settings');
    }
};
