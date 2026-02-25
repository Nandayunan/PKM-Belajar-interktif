# SMP 40 Bandung - Platform Pembelajaran Interaktif

Aplikasi pembelajaran interaktif berbasis Laravel 12 dengan MySQL XAMPP untuk siswa dan guru SMP 40 Bandung.

## 🎯 Fitur Utama

### ✅ Sudah Diimplementasikan

- **Project Setup**
  - ✓ Laravel 12 dengan Composer
  - ✓ Konfigurasi MySQL XAMPP
  - ✓ Database schema lengkap dengan migrations
  - ✓ Eloquent Models dengan relationships
  - ✓ User role system (Guru=1, Siswa=0)

- **Database**
  - ✓ Users Table (dengan role)
  - ✓ Subjects Table (Mata Pelajaran)
  - ✓ Modules Table (Modul per Mata Pelajaran)
  - ✓ Questions Table (Soal dengan type: multiple_choice, essay, true_false)
  - ✓ QuestionAnswers Table (Jawaban siswa)
  - ✓ StudentProgress Table (Tracking progress dengan persentase)
  - ✓ TeacherSettings Table (Toggle show/hide jawaban)

### 🔧 Fitur Yang Akan Diimplementasikan

- [ ] Authentication System (Login/Register)
- [ ] Teacher Dashboard dengan CRUD
- [ ] Student Dashboard dengan gamification
- [ ] Interactive Quiz Interface
- [ ] YouTube Video Integration
- [ ] Progress Tracking
- [ ] Teacher Settings Management
- [ ] Responsive UI/UX Design

## 📁 Struktur Project

```
smp40_bandung/
├── app/Models/
│   ├── User.php
│   ├── Subject.php
│   ├── Module.php
│   ├── Question.php
│   ├── QuestionAnswer.php
│   ├── StudentProgress.php
│   └── TeacherSettings.php
├── app/Http/Controllers/
│   ├── Auth/
│   ├── Teacher/
│   └── Student/
├── database/migrations/
├── database/seeders/
├── resources/views/
├── routes/
└── SETUP_GUIDE.md
```

## 🚀 Cara Menggunakan

### 1. Setup Database
Lihat [SETUP_GUIDE.md](SETUP_GUIDE.md) untuk langkah-langkah lengkap.

### 2. Jalankan Migrations
```bash
php artisan migrate
```

### 3. Jalankan Development Server
```bash
php artisan serve
```

Akses di: `http://localhost:8000`

## 📊 Database Relationships

```
User (1) --< Subject, Module, Question, StudentProgress
Subject (1) --< Module, StudentProgress
Module (1) --< Question, StudentProgress
Question (1) --< QuestionAnswer
```

## 🎨 UI/UX Design

- Dashboard dengan card-based navigation
- Gamification elements (points, progress bars)
- YouTube video embedded
- Responsive mobile design
- Color-coded subjects

## 📝 User Roles

- **Guru (role=1):** CRUD subjects, modules, questions, settings
- **Siswa (role=0):** View subjects, answer questions, track progress

## 🔐 Security

- CSRF Protection
- Password hashing (bcrypt)
- SQL Injection prevention (Eloquent ORM)
- Role-based middleware

## 💻 Technical Stack

- Laravel 12
- MySQL 5.7+
- PHP 8.3+
- Bootstrap 5 / Tailwind CSS

## 📞 Support

Lihat [SETUP_GUIDE.md](SETUP_GUIDE.md) untuk troubleshooting

---

**Project untuk SMP 40 Bandung** | February 2026


Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
