<?php

namespace Database\Seeders;

use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teacher1 = User::where('email', 'guru@smp40.com')->first();
        $teacher2 = User::where('email', 'pak.ahmad@smp40.com')->first();

        // Ensure teachers exist (if seeder run independently)
        if (!$teacher1) {
            $teacher1 = User::firstOrCreate(
                ['email' => 'guru@smp40.com'],
                ['name' => 'Ibu Siti Nurhaliza', 'password' => Hash::make('password'), 'role' => 1]
            );
        }

        if (!$teacher2) {
            $teacher2 = User::firstOrCreate(
                ['email' => 'pak.ahmad@smp40.com'],
                ['name' => 'Pak Ahmad Wijaya', 'password' => Hash::make('password'), 'role' => 1]
            );
        }

        // Matematika
        Subject::firstOrCreate([
            'name' => 'Matematika'
        ], [
            'description' => 'Pelajaran Matematika untuk kelas 7, 8, dan 9',
            'icon' => '🔢',
            'color' => '#FF6B6B',
            'created_by' => $teacher1->id,
        ]);

        // Bahasa Indonesia
        Subject::firstOrCreate([
            'name' => 'Bahasa Indonesia'
        ], [
            'description' => 'Pelajaran Bahasa Indonesia dan Sastra',
            'icon' => '📚',
            'color' => '#4ECDC4',
            'created_by' => $teacher1->id,
        ]);

        // IPA (Sains)
        Subject::firstOrCreate([
            'name' => 'IPA (Sains)'
        ], [
            'description' => 'Ilmu Pengetahuan Alam - Fisika, Kimia, Biologi',
            'icon' => '🔬',
            'color' => '#45B7D1',
            'created_by' => $teacher2->id,
        ]);

        // IPS (Sosial)
        Subject::firstOrCreate([
            'name' => 'IPS (Sosial)'
        ], [
            'description' => 'Ilmu Pengetahuan Sosial - Sejarah, Geografi, Ekonomi',
            'icon' => '🌍',
            'color' => '#F7B731',
            'created_by' => $teacher2->id,
        ]);

        // Bahasa Inggris
        Subject::firstOrCreate([
            'name' => 'Bahasa Inggris'
        ], [
            'description' => 'Pelajaran Bahasa Inggris',
            'icon' => '🇬🇧',
            'color' => '#5F27CD',
            'created_by' => $teacher1->id,
        ]);

        // Pendidikan Jasmani & Olahraga
        Subject::firstOrCreate([
            'name' => 'Penjas & Olahraga'
        ], [
            'description' => 'Pendidikan Jasmani dan Kesehatan Olahraga',
            'icon' => '⚽',
            'color' => '#00D2D3',
            'created_by' => $teacher2->id,
        ]);
    }
}
