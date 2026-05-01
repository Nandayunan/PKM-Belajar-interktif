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
        Schema::table('student_progress', function (Blueprint $table) {
            $table->text('student_feedback')->nullable()->after('status');
            $table->text('student_feelings')->nullable()->after('student_feedback');
            $table->timestamp('feedback_submitted_at')->nullable()->after('student_feelings');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_progress', function (Blueprint $table) {
            $table->dropColumn(['student_feedback', 'student_feelings', 'feedback_submitted_at']);
        });
    }
};
