<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'academic_year')) {
                $table->string('academic_year')->nullable()->after('class');
                $table->index('academic_year');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'academic_year')) {
                $table->dropIndex(['academic_year']);
                $table->dropColumn('academic_year');
            }
        });
    }
};
