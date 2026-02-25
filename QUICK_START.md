# QUICK START GUIDE - Platform Pembelajaran SMP 40 Bandung

Panduan cepat untuk memulai dan menjalankan project Laravel pembelajaran interaktif.

## 📋 Checklist Setup Awal

Sebelum memulai, pastikan sudah tersedia:
- [ ] XAMPP terinstall
- [ ] PHP 8.3+ (di XAMPP)
- [ ] MySQL (di XAMPP)
- [ ] Composer terinstall
- [ ] Git (optional)
- [ ] VS Code atau text editor

---

## ⚙️ STEP 1: Persiapan XAMPP (5 menit)

### 1.1 Buka XAMPP Control Panel
```
Lokasi: C:\xampp\xampp-control.exe
(atau cari di Windows Start Menu)
```

### 1.2 Jalankan Services yang Diperlukan
```
Klik "START" untuk:
- Apache (opsional untuk project ini)
- MySQL (WAJIB)
```

✓ MySQL harus berjalan dengan status "Running" dan warna hijau

### 1.3 Buka phpMyAdmin
```
Buka browser: http://localhost/phpmyadmin
Username: root
Password: (kosong, tekan Enter)
```

### 1.4 Create Database
```sql
-- Copy-paste di tab SQL di phpMyAdmin:

CREATE DATABASE smp40_bandung 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;
```

✓ Klik "Go" atau tekan Enter
✓ Database `smp40_bandung` berhasil dibuat

---

## 🚀 STEP 2: Setup Project Laravel (10 menit)

### 2.1 Buka Terminal/PowerShell
```
Tekan: Windows + R
Ketik: powershell
Tekan: Enter
```

### 2.2 Navigasi ke Folder Project
```powershell
cd D:\PKM-Project\smp40_bandung
```

### 2.3 Verifikasi File .env
```powershell
# Lihat isi file .env
cat .env | grep DB_
```

✓ Pastikan terlihat:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=smp40_bandung
DB_USERNAME=root
DB_PASSWORD=
```

### 2.4 Verifikasi Composer
```powershell
composer --version
```

✓ Seharusnya menampilkan versi Composer

---

## 💾 STEP 3: Database Migration (5 menit)

### 3.1 Jalankan Migration
```powershell
php artisan migrate
```

✓ Akan membuat semua tabel di database

Output akan terlihat seperti:
```
Running migrations.

0001_01_01_000000_create_users_table ........................... DONE
2026_02_09_114501_create_subjects_table ...................... DONE
2026_02_09_114510_create_modules_table ....................... DONE
2026_02_09_114511_create_questions_table ..................... DONE
2026_02_09_114511_create_question_answers_table .............. DONE
2026_02_09_114512_create_student_progress_table .............. DONE
2026_02_09_114556_create_teacher_settings_table .............. DONE
```

### 3.2 (Optional) Seed Sample Data
```powershell
php artisan migrate:fresh --seed
```

✓ Akan membuat ulang database dan mengisi data sample:
- 2 Guru, 5 Siswa
- 6 Mata Pelajaran
- 9 Modul Pembelajaran

---

## 🎮 STEP 4: Jalankan Application (3 menit)

### 4.1 Start Laravel Development Server
```powershell
php artisan serve
```

✓ Akan menampilkan:
```
   INFO  Server running on [http://127.0.0.1:8000].

  Press Ctrl+C to quit
```

**JANGAN TUTUP TERMINAL INI!**

### 4.2 Buka Browser
```
Ketik di address bar: http://localhost:8000
Tekan: Enter
```

✓ Halaman aplikasi akan muncul

---

## 🧪 STEP 5: Testing Aplikasi

### 5.1 Test Database Connection
Buka terminal baru (PowerShell lain):
```powershell
cd D:\PKM-Project\smp40_bandung
php artisan tinker
```

Ketik:
```php
DB::connection()->getPdo()
```

✓ Jika keluar hasil (PDO object), database terkoneksi dengan baik

### 5.2 Cek Data Sample
```php
User::count()  # Harus menampilkan 7 jika sudah seed
Subject::count()  # Harus menampilkan 6
Module::count()  # Harus menampilkan 9
```

Keluar dari Tinker:
```php
exit()
```

---

## 📚 INFORMASI LOGIN (jika sudah di-seed)

### Guru Account:
```
Email: guru@smp40.com
Password: password
```

### Siswa Account:
```
Email: siswa@smp40.com
Password: password
```

---

## 📁 File-File Penting

```
D:\PKM-Project\smp40_bandung\

📄 README.md
   → Penjelasan project dan fitur utama

📄 SETUP_GUIDE.md
   → Panduan setup terperinci & troubleshooting

📄 DATABASE_ARCHITECTURE.md
   → Dokumentasi lengkap database dan schema

📄 IMPLEMENTATION_CHECKLIST.md
   → Daftar task untuk development selanjutnya

📄 PROJECT_STATUS.md
   → Status project dan progress development

📄 .env
   → Konfigurasi database (sudah dikonfigurasi)

📁 app/Models/
   → 7 Eloquent Models dengan relationships

📁 database/migrations/
   → 9 Migration files untuk database schema

📁 database/seeders/
   → Seeder untuk sample data
```

---

## 🎯 Apa Yang Sudah Siap?

✅ **Database:**
- 7 tabel dengan proper relationships
- Indexes untuk performance
- Foreign key constraints

✅ **Models:**
- 7 Eloquent models (User, Subject, Module, Question, QuestionAnswer, StudentProgress, TeacherSettings)
- Relationships semua terconnect
- Helper methods untuk role checking

✅ **Seeders:**
- Sample users (guru & siswa)
- Sample subjects dengan colors & emojis
- Sample modules dengan content & video URL

✅ **Documentation:**
- Setup guide
- Database architecture
- Implementation checklist
- Project status

---

## ❓ Troubleshooting Cepat

### Error: "No connection could be made because the target machine actively refused it"
**Solusi:** Start MySQL di XAMPP Control Panel dulu

### Error: "SQLSTATE[HY000] [2002]"
**Solusi:** Database `smp40_bandung` belum dibuat. Lihat Step 1.4

### Error: "PHP Warning: Module 'ldap' is already loaded"
**Solusi:** Bisa diabaikan, tidak mempengaruhi aplikasi

### Database tidak ada struktur tabel
**Solusi:** Jalankan `php artisan migrate` (Step 3.1)

### Port 8000 sudah digunakan
**Solusi:** Jalankan dengan port lain:
```powershell
php artisan serve --port=8001
```

---

## 🚀 Langkah Selanjutnya (Phase 2+)

Sekarang database dan models sudah siap, development berikutnya:

1. **Authentication** (Login/Register)
2. **Teacher Dashboard** (CRUD functionality)
3. **Student Dashboard** (Learning interface)
4. **Views & UI/UX** (Frontend design)
5. **Testing & Deployment**

Lihat **IMPLEMENTATION_CHECKLIST.md** untuk detail lengkap.

---

## 📞 Helpful Links

- Laravel Documentation: https://laravel.com/docs
- MySQL Documentation: https://dev.mysql.com/doc/
- XAMPP Documentation: https://www.apachefriends.org/
- PHP Official: https://www.php.net/

---

## ✨ Catatan Penting

### Development Tips:
1. Selalu jalankan `php artisan serve` untuk development
2. Buka phpMyAdmin jika perlu check data langsung
3. Gunakan `php artisan tinker` untuk query testing
4. Jangan ubah `.env` untuk production settings

### Security Notes:
1. File `.env` JANGAN dishare/upload ke repository
2. Password harus di-hash dengan Bcrypt (Laravel otomatis)
3. Email harus unique per user
4. Role harus 0 (Siswa) atau 1 (Guru)

### Best Practices:
1. Selalu use Migrations untuk schema changes
2. Gunakan Models untuk database queries
3. Validate semua input di controller
4. Comment code yang kompleks

---

## 🎓 Learning Path

### Untuk beginners:
1. Baca **SETUP_GUIDE.md**
2. Baca **DATABASE_ARCHITECTURE.md**
3. Explore folder `app/Models/` untuk lihat relationships
4. Buka **IMPLEMENTATION_CHECKLIST.md** untuk next steps

### Untuk intermediate:
1. Lihat Laravel docs untuk authentication
2. Implement login/register (Phase 2)
3. Implement CRUD controllers

### Untuk advanced:
1. Add tests menggunakan PHPUnit
2. Optimize queries
3. Implement caching
4. Setup deployment

---

## 📋 Daily Workflow

Setiap kali ingin mengerjakan project:

```powershell
# 1. Open Terminal
Windows + R → powershell → Enter

# 2. Navigate to project
cd D:\PKM-Project\smp40_bandung

# 3. Start MySQL (XAMPP Control Panel)
# - Click START for MySQL

# 4. Run development server
php artisan serve

# 5. Open browser
http://localhost:8000

# 6. Done! Bisa mulai development
```

---

## 🎉 Selesai!

Aplikasi sudah siap untuk development. Database dan model structure sudah complete. Sekarang tinggal implement fitur-fitur sesuai IMPLEMENTATION_CHECKLIST.md.

**Happy Coding! 🚀**

---

**Last Updated:** February 9, 2026  
**For:** SMP 40 Bandung Learning Platform  
**Status:** Ready for Development Phase 2
