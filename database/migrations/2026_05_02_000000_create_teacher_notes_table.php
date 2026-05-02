<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('teacher_notes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('teacher_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->unsignedBigInteger('module_id')->nullable();
            $table->text('note');
            $table->timestamps();

            $table->index('teacher_id');
            $table->index('user_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('teacher_notes');
    }
};
