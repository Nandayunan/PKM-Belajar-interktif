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
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Nama Mata Pelajaran');
            $table->text('description')->nullable()->comment('Deskripsi Mata Pelajaran');
            $table->string('icon')->nullable()->comment('Icon atau emoji');
            $table->string('color')->default('#4A90E2')->comment('Warna untuk card');
            $table->unsignedBigInteger('created_by')->comment('Guru yang membuat');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
