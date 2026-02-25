<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add 'mixed' to enum for 'type' if it exists in the DB (MySQL compatible statement)
        // If your DB is different (pgsql) you may need a different statement.
        DB::statement("ALTER TABLE `questions` MODIFY `type` ENUM('multiple_choice','essay','true_false','mixed') NOT NULL DEFAULT 'multiple_choice'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE `questions` MODIFY `type` ENUM('multiple_choice','essay','true_false') NOT NULL DEFAULT 'multiple_choice'");
    }
};
