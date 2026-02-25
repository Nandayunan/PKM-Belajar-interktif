<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teacher1 = User::where('email', 'guru@smp40.com')->first();
        if (!$teacher1) {
            $teacher1 = User::firstOrCreate([
                'email' => 'guru@smp40.com'
            ], [
                'name' => 'Ibu Siti Nurhaliza',
                'password' => Hash::make('password'),
                'role' => 1,
            ]);
        }

        // Matematika Modules
        $matematika = Subject::firstWhere('name', 'Matematika');
        if ($matematika) {
            Module::firstOrCreate([
                'subject_id' => $matematika->id,
                'name' => 'Bilangan Bulat'
            ], [
                'module_number' => 1,
                'description' => 'Memahami konsep bilangan bulat, operasi hitung, dan aplikasinya',
                'content' => 'Bilangan bulat adalah himpunan bilangan yang terdiri dari bilangan positif, nol, dan bilangan negatif.',
                'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'created_by' => $teacher1->id,
                'published' => true,
            ]);

            Module::firstOrCreate([
                'subject_id' => $matematika->id,
                'name' => 'Pecahan'
            ], [
                'module_number' => 2,
                'description' => 'Mempelajari pecahan, operasi pecahan, dan perubahan bentuk pecahan',
                'content' => 'Pecahan adalah bagian dari keseluruhan yang dinyatakan dengan bentuk a/b.',
                'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'created_by' => $teacher1->id,
                'published' => true,
            ]);

            Module::firstOrCreate([
                'subject_id' => $matematika->id,
                'name' => 'Aljabar Dasar'
            ], [
                'module_number' => 3,
                'description' => 'Pengenalan aljabar, variabel, dan persamaan linear',
                'content' => 'Aljabar adalah cabang matematika yang menggunakan simbol dan huruf untuk mewakili angka dan besaran.',
                'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'created_by' => $teacher1->id,
                'published' => true,
            ]);
        }

        // Bahasa Indonesia Modules
        $bahasaIndo = Subject::firstWhere('name', 'Bahasa Indonesia');
        if ($bahasaIndo) {
            Module::firstOrCreate([
                'subject_id' => $bahasaIndo->id,
                'name' => 'Teks Narasi'
            ], [
                'module_number' => 1,
                'description' => 'Memahami struktur dan cara menulis teks narasi',
                'content' => 'Teks narasi adalah teks yang menceritakan suatu peristiwa atau rangkaian peristiwa yang dihubungkan dengan urutan waktu.',
                'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'created_by' => $teacher1->id,
                'published' => true,
            ]);

            Module::firstOrCreate([
                'subject_id' => $bahasaIndo->id,
                'name' => 'Puisi'
            ], [
                'module_number' => 2,
                'description' => 'Mempelajari puisi, jenis puisi, dan cara menganalisis puisi',
                'content' => 'Puisi adalah karya sastra yang menggunakan bahasa yang indah dan penuh makna untuk mengekspresikan perasaan dan ide.',
                'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'created_by' => $teacher1->id,
                'published' => true,
            ]);
        }

        // IPA Modules
        $ipa = Subject::firstWhere('name', 'IPA (Sains)');
        $teacher2 = User::where('email', 'pak.ahmad@smp40.com')->first();
        if (!$teacher2) {
            $teacher2 = User::firstOrCreate([
                'email' => 'pak.ahmad@smp40.com'
            ], [
                'name' => 'Pak Ahmad Wijaya',
                'password' => Hash::make('password'),
                'role' => 1,
            ]);
        }

        if ($ipa) {
            Module::firstOrCreate([
                'subject_id' => $ipa->id,
                'name' => 'Struktur Atom'
            ], [
                'module_number' => 1,
                'description' => 'Memahami struktur atom, elektron, proton, dan neutron',
                'content' => 'Atom adalah satuan terkecil dari suatu unsur yang masih mempertahankan sifat unsur tersebut.',
                'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'created_by' => $teacher2->id,
                'published' => true,
            ]);

            Module::firstOrCreate([
                'subject_id' => $ipa->id,
                'name' => 'Sistem Pencernaan'
            ], [
                'module_number' => 2,
                'description' => 'Mempelajari sistem pencernaan manusia dan proses pencernaan',
                'content' => 'Sistem pencernaan adalah serangkaian organ yang bekerja bersama untuk mengubah makanan menjadi energi dan nutrisi.',
                'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'created_by' => $teacher2->id,
                'published' => true,
            ]);
        }
    }
}
