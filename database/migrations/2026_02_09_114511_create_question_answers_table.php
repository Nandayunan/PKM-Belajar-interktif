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
        if (!Schema::hasTable('question_answers')) {
            Schema::create('question_answers', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->comment('Siswa yang menjawab');
                $table->unsignedBigInteger('question_id')->comment('Soal yang dijawab');
                $table->text('answer')->comment('Jawaban siswa');
                $table->boolean('is_correct')->default(false)->comment('Apakah jawaban benar');
                $table->integer('points_earned')->default(0)->comment('Poin yang didapat');
                $table->timestamps();
            });

            // Add foreign keys only if referenced tables exist
            if (Schema::hasTable('users') && Schema::hasTable('questions')) {
                Schema::table('question_answers', function (Blueprint $table) {
                    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                    $table->foreign('question_id')->references('id')->on('questions')->onDelete('cascade');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_answers');
    }
};
