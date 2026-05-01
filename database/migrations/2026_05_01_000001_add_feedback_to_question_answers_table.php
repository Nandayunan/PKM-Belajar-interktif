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
        Schema::table('question_answers', function (Blueprint $table) {
            $table->text('teacher_feedback')->nullable()->after('points_earned');
            $table->integer('teacher_score')->nullable()->after('teacher_feedback');
            $table->timestamp('graded_at')->nullable()->after('teacher_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('question_answers', function (Blueprint $table) {
            $table->dropColumn(['teacher_feedback', 'teacher_score', 'graded_at']);
        });
    }
};
