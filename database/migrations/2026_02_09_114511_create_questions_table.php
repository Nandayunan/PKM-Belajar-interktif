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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('module_id')->comment('Referensi ke Modul');
            $table->text('question')->comment('Soal/Pertanyaan');
            $table->enum('type', ['multiple_choice', 'essay', 'true_false'])->default('multiple_choice')->comment('Tipe Soal');
            $table->json('options')->nullable()->comment('Opsi jawaban (untuk multiple choice)');
            $table->string('correct_answer')->comment('Jawaban yang benar');
            $table->integer('points')->default(10)->comment('Poin untuk soal ini');
            $table->unsignedBigInteger('created_by')->comment('Guru yang membuat soal');
            $table->boolean('published')->default(true)->comment('Status publikasi');
            $table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
