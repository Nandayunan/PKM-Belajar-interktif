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
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subject_id')->comment('Referensi ke Mata Pelajaran');
            $table->string('name')->comment('Nama Modul');
            $table->integer('module_number')->comment('Nomor Modul (1, 2, 3, dst)');
            $table->text('description')->nullable()->comment('Deskripsi Modul');
            $table->text('content')->nullable()->comment('Konten/Materi');
            $table->string('video_url')->nullable()->comment('URL Video YouTube');
            $table->unsignedBigInteger('created_by')->comment('Guru yang membuat');
            $table->boolean('published')->default(true)->comment('Status publikasi');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};
