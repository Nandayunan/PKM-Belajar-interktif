# Setup Guide - SMP 40 Bandung Learning Platform

Platform pembelajaran interaktif dengan UI/UX yang menarik untuk siswa SMP 40 Bandung.

## Persyaratan Sistem

- PHP 8.3 atau lebih baru
- MySQL 5.7 atau lebih baru (via XAMPP)
- Composer
- Node.js (untuk asset compilation)

## Langkah Setup

### 1. Database Setup (XAMPP MySQL)

Ikuti langkah-langkah berikut untuk membuat database:

1. **Buka XAMPP Control Panel dan jalankan MySQL**
   - Klik tombol "Start" pada baris MySQL di XAMPP Control Panel

2. **Akses phpMyAdmin**
   - Buka browser dan akses: `http://localhost/phpmyadmin`
   - Login dengan username `root` (tanpa password)

3. **Buat Database Baru**
   ```sql
   CREATE DATABASE smp40_bandung CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

### 2. Jalankan Migrations

Setelah database dibuat dan MySQL berjalan, jalankan perintah:

```bash
cd D:\PKM-Project\smp40_bandung
php artisan migrate
```

Atau jika ingin membuat database baru dari awal dengan seed data:

```bash
php artisan migrate:fresh --seed
```

### 3. Jalankan Development Server

```bash
php artisan serve
```

Server akan berjalan di: `http://localhost:8000`

### 4. Compile Assets (Optional - untuk CSS/JS enhancement)

```bash
npm install
npm run dev
```

## Struktur Project

```
smp40_bandung/
├── app/
│   ├── Models/              # Model database
│   │   ├── User.php
│   │   ├── Subject.php
│   │   ├── Module.php
│   │   ├── Question.php
│   │   ├── QuestionAnswer.php
│   │   └── StudentProgress.php
│   └── Http/Controllers/    # Controllers
│       ├── Auth/            # Authentication controllers
│       ├── Teacher/         # Teacher dashboard & management
│       └── Student/         # Student dashboard & learning
├── database/
│   └── migrations/          # Database schemas
├── resources/
│   └── views/               # Blade templates
├── routes/
│   ├── api.php
│   └── web.php
└── public/                  # Assets (CSS, JS, Images)
```

## Database Schema

### Users Table
- id (Primary Key)
- name (Nama lengkap)
- email (Email unik)
- password (Hashed password)
- role (0 = Siswa, 1 = Guru)
- timestamps

### Subjects Table
- id (Primary Key)
- name (Nama mata pelajaran)
- description
- icon
- color (Warna card)
- created_by (Foreign Key ke Users - Guru)
- timestamps

### Modules Table
- id (Primary Key)
- subject_id (Foreign Key)
- name (Nama modul)
- module_number (1, 2, 3, dst)
- description
- content (Materi pembelajaran)
- video_url (Link YouTube)
- created_by (Foreign Key ke Users - Guru)
- published (Boolean)
- timestamps

### Questions Table
- id (Primary Key)
- module_id (Foreign Key)
- question (Teks soal)
- type (multiple_choice, essay, true_false)
- options (JSON untuk opsi pilihan ganda)
- correct_answer (Jawaban yang benar)
- points (Poin soal)
- created_by (Foreign Key)
- published (Boolean)
- timestamps

### QuestionAnswers Table
- id (Primary Key)
- user_id (Foreign Key - Siswa)
- question_id (Foreign Key)
- answer (Jawaban siswa)
- is_correct (Boolean)
- points_earned (Poin yang didapat)
- timestamps

### StudentProgress Table
- id (Primary Key)
- user_id (Foreign Key)
- subject_id (Foreign Key)
- module_id (Foreign Key, nullable)
- total_questions
- answered_questions
- correct_answers
- total_points
- earned_points
- percentage (0-100)
- status (not_started, in_progress, completed)
- timestamps

### TeacherSettings Table
- id (Primary Key)
- teacher_id (Foreign Key - Guru)
- show_correct_answers (Boolean)
- show_wrong_answers (Boolean)
- show_score (Boolean)
- timestamps

## Routes dan Fitur

### Authentication Routes
- GET/POST `/register` - Registrasi pengguna baru
- GET/POST `/login` - Login pengguna
- POST `/logout` - Logout

### Guru Dashboard Routes
- GET `/teacher/dashboard` - Dashboard utama guru
- GET/POST `/teacher/subjects` - CRUD Mata Pelajaran
- GET/POST `/teacher/modules` - CRUD Modul
- GET/POST `/teacher/questions` - CRUD Soal
- GET `/teacher/students` - Lihat progress siswa
- GET/POST `/teacher/settings` - Pengaturan toggle jawaban

### Siswa Dashboard Routes
- GET `/student/dashboard` - Dashboard utama siswa
- GET `/student/subjects` - Daftar mata pelajaran
- GET `/student/modules/:subject_id` - Daftar modul per mata pelajaran
- GET `/student/module/:module_id` - Lihat modul dan video
- GET `/student/questions/:module_id` - Soal-soal modul
- POST `/student/answer` - Submit jawaban
- GET `/student/progress` - Lihat progress

## User Credentials (Setelah Migration)

Setelah menjalankan `migrate:fresh --seed`, user default akan dibuat:

### Guru
- Email: `guru@smp40.com`
- Password: `password`
- Role: 1 (Guru)

### Siswa
- Email: `siswa@smp40.com`
- Password: `password`
- Role: 0 (Siswa)

## Features

✅ **Authentication System**
- Register dengan pilihan role
- Login dengan validasi role
- Protected routes berdasarkan role

✅ **Guru Dashboard**
- CRUD Mata Pelajaran (dengan card design)
- CRUD Modul per Mata Pelajaran
- CRUD Soal (multiple choice, essay, true/false)
- Input video YouTube (embed player)
- Toggle pengaturan: show/hide jawaban benar/salah
- Lihat progress semua siswa

✅ **Siswa Dashboard**
- Lihat daftar Mata Pelajaran (card design)
- Pilih modul untuk belajar
- Lihat video YouTube langsung di halaman
- Mengerjakan soal interaktif
- Lihat hasil jawaban (benar/salah) sesuai setting guru
- Tracking progress dengan persentase

✅ **Gamification Elements**
- Point system untuk setiap soal
- Progress bar dengan persentase
- Status pembelajaran (not started, in progress, completed)
- Color-coded subjects untuk visual appeal

## Troubleshooting

### MySQL Connection Error
```
SQLSTATE[HY000] [2002] No connection could be made...
```
**Solusi:** 
- Pastikan MySQL di XAMPP sudah dijalankan (Start button di XAMPP Control Panel)
- Pastikan database `smp40_bandung` sudah dibuat di phpMyAdmin

### PHP Artisan Command Error
```
PHP Warning: Module "ldap" is already loaded
```
**Solusi:** 
- Warning ini bisa diabaikan, tidak mempengaruhi performa aplikasi
- Jika ingin menghilangkan, edit `php.ini` di XAMPP dan remove duplicate ldap extension loading

### Assets Not Loading
**Solusi:**
```bash
php artisan storage:link
php artisan optimize:clear
```

## Development Tips

### Membuat User Baru Melalui Terminal
```bash
php artisan tinker
User::create(['name' => 'Nama', 'email' => 'email@domain.com', 'password' => Hash::make('password'), 'role' => 0])
```

### Testing Database Connection
```bash
php artisan tinker
DB::connection()->getPdo()
# Jika berhasil, akan menampilkan PDO object
```

### Clear Cache dan Optimize
```bash
php artisan cache:clear
php artisan route:cache
php artisan config:cache
php artisan view:cache
```

## Support dan Informasi Lebih Lanjut

- Laravel Documentation: https://laravel.com/docs
- XAMPP Documentation: https://www.apachefriends.org/
- PHP Documentation: https://www.php.net/docs.php

---

**Sekolah:** SMP 40 Bandung
**Project:** Platform Pembelajaran Interaktif
**Created:** February 2026
