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
        Schema::create('student_progress', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('Siswa');
            $table->unsignedBigInteger('subject_id')->comment('Mata Pelajaran');
            $table->unsignedBigInteger('module_id')->nullable()->comment('Modul (optional untuk progress keseluruhan)');
            $table->integer('total_questions')->default(0)->comment('Total soal');
            $table->integer('answered_questions')->default(0)->comment('Soal yang sudah dijawab');
            $table->integer('correct_answers')->default(0)->comment('Jawaban benar');
            $table->integer('total_points')->default(0)->comment('Total poin');
            $table->integer('earned_points')->default(0)->comment('Poin yang diraih');
            $table->decimal('percentage', 5, 2)->default(0)->comment('Persentase (0-100)');
            $table->enum('status', ['not_started', 'in_progress', 'completed'])->default('not_started')->comment('Status pembelajaran');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->foreign('module_id')->references('id')->on('modules')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_progress');
    }
};
