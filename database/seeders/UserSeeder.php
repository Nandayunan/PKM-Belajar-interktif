<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample Guru (Teacher)
        User::firstOrCreate(
            ['email' => 'guru@smp40.com'],
            [
                'name' => 'Ibu Siti Nurhaliza',
                'password' => Hash::make('password'),
                'role' => 1, // Guru
            ]
        );

        // Create sample Siswa (Student)
        User::firstOrCreate(
            ['email' => 'siswa@smp40.com'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password'),
                'role' => 0, // Siswa
            ]
        );

        // Create additional teachers
        User::firstOrCreate(
            ['email' => 'pak.ahmad@smp40.com'],
            [
                'name' => 'Pak Ahmad Wijaya',
                'password' => Hash::make('password'),
                'role' => 1,
            ]
        );

        // Create additional students
        for ($i = 2; $i <= 5; $i++) {
            User::firstOrCreate(
                ['email' => 'siswa' . $i . '@smp40.com'],
                [
                    'name' => 'Siswa ' . $i,
                    'password' => Hash::make('password'),
                    'role' => 0,
                ]
            );
        }
    }
}
