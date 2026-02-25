# Database & Architecture Documentation

## Database Schema Overview

### Users Table
Menyimpan informasi pengguna (guru dan siswa)

```sql
CREATE TABLE users (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    role TINYINT DEFAULT 0 COMMENT '0 = Siswa, 1 = Guru',
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

**Fields:**
- `id`: Primary key
- `name`: Nama lengkap pengguna
- `email`: Email unik untuk login
- `password`: Password ter-hash (bcrypt)
- `role`: Tipe user (0=Siswa, 1=Guru)

---

### Subjects Table
Menyimpan daftar mata pelajaran

```sql
CREATE TABLE subjects (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL COMMENT 'Nama Mata Pelajaran',
    description TEXT COMMENT 'Deskripsi Mata Pelajaran',
    icon VARCHAR(255) COMMENT 'Icon atau emoji',
    color VARCHAR(255) DEFAULT '#4A90E2' COMMENT 'Warna untuk card',
    created_by BIGINT UNSIGNED NOT NULL COMMENT 'Guru yang membuat',
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
);
```

**Fields:**
- `id`: Primary key
- `name`: Nama mata pelajaran (Matematika, IPA, Bahasa Indonesia, dll)
- `description`: Penjelasan singkat
- `icon`: Emoji atau icon (🔢, 📚, 🔬, 🌍, dll)
- `color`: Hex color untuk card display (#FF6B6B, #4ECDC4, dll)
- `created_by`: FK ke Users (guru yang membuat)

**Relationships:**
- Belongs to: User (teacher)
- Has many: Modules, StudentProgress

---

### Modules Table
Menyimpan modul pembelajaran per mata pelajaran

```sql
CREATE TABLE modules (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    subject_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL COMMENT 'Nama Modul',
    module_number INT NOT NULL COMMENT 'Nomor Modul (1, 2, 3, dst)',
    description TEXT COMMENT 'Deskripsi Modul',
    content TEXT COMMENT 'Konten/Materi',
    video_url VARCHAR(255) COMMENT 'URL Video YouTube',
    created_by BIGINT UNSIGNED NOT NULL,
    published BOOLEAN DEFAULT true,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
);
```

**Fields:**
- `subject_id`: FK ke Subjects
- `name`: Nama modul (Bilangan Bulat, Pecahan, Aljabar, dll)
- `module_number`: Nomor urut modul (1, 2, 3, dst)
- `description`: Penjelasan modul
- `content`: Materi pembelajaran (text/HTML)
- `video_url`: Link YouTube (contoh: https://www.youtube.com/embed/...)
- `created_by`: FK ke Users (guru)
- `published`: Boolean untuk kontrol publikasi

**Relationships:**
- Belongs to: Subject, User (teacher)
- Has many: Questions, StudentProgress

---

### Questions Table
Menyimpan soal-soal pembelajaran

```sql
CREATE TABLE questions (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    module_id BIGINT UNSIGNED NOT NULL,
    question TEXT NOT NULL COMMENT 'Soal/Pertanyaan',
    type ENUM('multiple_choice', 'essay', 'true_false') DEFAULT 'multiple_choice',
    options JSON COMMENT 'Opsi jawaban (untuk multiple choice)',
    correct_answer VARCHAR(255) NOT NULL,
    points INT DEFAULT 10,
    created_by BIGINT UNSIGNED NOT NULL,
    published BOOLEAN DEFAULT true,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (module_id) REFERENCES modules(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
);
```

**Fields:**
- `module_id`: FK ke Modules
- `question`: Teks soal
- `type`: Tipe soal
  - `multiple_choice`: Pilihan ganda
  - `essay`: Essay/uraian
  - `true_false`: Benar/Salah
- `options`: JSON array untuk opsi pilihan ganda
  ```json
  {
    "A": "Opsi A",
    "B": "Opsi B",
    "C": "Opsi C",
    "D": "Opsi D"
  }
  ```
- `correct_answer`: Jawaban yang benar (A, B, C, D untuk MC; true/false untuk TF; text untuk essay)
- `points`: Poin soal
- `created_by`: FK ke Users (guru)
- `published`: Boolean untuk publikasi

**Relationships:**
- Belongs to: Module, User (teacher)
- Has many: QuestionAnswers

---

### QuestionAnswers Table
Menyimpan jawaban siswa terhadap soal

```sql
CREATE TABLE question_answers (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NOT NULL COMMENT 'Siswa yang menjawab',
    question_id BIGINT UNSIGNED NOT NULL,
    answer TEXT NOT NULL,
    is_correct BOOLEAN DEFAULT false,
    points_earned INT DEFAULT 0,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE
);
```

**Fields:**
- `user_id`: FK ke Users (siswa)
- `question_id`: FK ke Questions
- `answer`: Jawaban siswa
- `is_correct`: Boolean (benar/salah)
- `points_earned`: Poin yang didapat (0 atau points dari question)
- `created_at`: Waktu submit jawaban

**Relationships:**
- Belongs to: User (student), Question

---

### StudentProgress Table
Menyimpan progress pembelajaran siswa

```sql
CREATE TABLE student_progress (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NOT NULL,
    subject_id BIGINT UNSIGNED NOT NULL,
    module_id BIGINT UNSIGNED NULL,
    total_questions INT DEFAULT 0,
    answered_questions INT DEFAULT 0,
    correct_answers INT DEFAULT 0,
    total_points INT DEFAULT 0,
    earned_points INT DEFAULT 0,
    percentage DECIMAL(5,2) DEFAULT 0 COMMENT 'Persentase 0-100',
    status ENUM('not_started', 'in_progress', 'completed') DEFAULT 'not_started',
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE,
    FOREIGN KEY (module_id) REFERENCES modules(id) ON DELETE SET NULL
);
```

**Fields:**
- `user_id`: FK ke Users (siswa)
- `subject_id`: FK ke Subjects
- `module_id`: FK ke Modules (nullable untuk progress overall)
- `total_questions`: Total soal dalam modul/subject
- `answered_questions`: Jumlah soal yang sudah dijawab
- `correct_answers`: Jumlah jawaban benar
- `total_points`: Total poin maksimal
- `earned_points`: Poin yang diraih siswa
- `percentage`: Persentase (calculated: earned_points / total_points * 100)
- `status`: Status pembelajaran
  - `not_started`: Belum dimulai
  - `in_progress`: Sedang belajar
  - `completed`: Selesai

**Relationships:**
- Belongs to: User (student), Subject, Module

---

### TeacherSettings Table
Menyimpan pengaturan guru

```sql
CREATE TABLE teacher_settings (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    teacher_id BIGINT UNSIGNED UNIQUE NOT NULL,
    show_correct_answers BOOLEAN DEFAULT false COMMENT 'Boleh siswa lihat jawaban benar',
    show_wrong_answers BOOLEAN DEFAULT false COMMENT 'Boleh siswa lihat jawaban salah',
    show_score BOOLEAN DEFAULT true COMMENT 'Boleh siswa lihat skor',
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (teacher_id) REFERENCES users(id) ON DELETE CASCADE
);
```

**Fields:**
- `teacher_id`: FK ke Users (guru) - UNIQUE karena 1 guru 1 settings
- `show_correct_answers`: Toggle untuk menampilkan jawaban benar
- `show_wrong_answers`: Toggle untuk menampilkan jawaban salah
- `show_score`: Toggle untuk menampilkan skor

**Relationships:**
- Belongs to: User (teacher)

---

## Entity Relationship Diagram (ERD)

```
┌─────────────┐
│   Users     │
├─────────────┤
│ id (PK)     │
│ name        │
│ email (UQ)  │
│ password    │
│ role        │
└──────┬──────┘
       │ 1
       ├─────────────────────────┐
       │                         │
       ▼ M                    ▼ 1
┌─────────────┐          ┌──────────────┐
│  Subjects   │          │TeacherSetings│
├─────────────┤          ├──────────────┤
│ id (PK)     │          │ id (PK)      │
│ name        │          │ teacher_id   │
│ icon        │          │ show_*       │
│ color       │          └──────────────┘
│ created_by  │
└──────┬──────┘
       │ 1
       ▼ M
┌─────────────┐
│  Modules    │
├─────────────┤
│ id (PK)     │
│ subject_id  │
│ name        │
│ module_num  │
│ video_url   │
│ created_by  │
└──────┬──────┘
       │ 1
       ▼ M
┌──────────────┐
│  Questions   │
├──────────────┤
│ id (PK)      │
│ module_id    │
│ question     │
│ type         │
│ options      │
│ correct_ans  │
│ points       │
│ created_by   │
└──────┬───────┘
       │ 1
       ▼ M
┌─────────────────────┐
│  QuestionAnswers    │
├─────────────────────┤
│ id (PK)             │
│ user_id (FK->Users) │
│ question_id         │
│ answer              │
│ is_correct          │
│ points_earned       │
└─────────────────────┘

┌──────────────────────┐
│ StudentProgress      │
├──────────────────────┤
│ id (PK)              │
│ user_id (FK->Users)  │
│ subject_id (FK)      │
│ module_id (FK)       │
│ answered_questions   │
│ correct_answers      │
│ percentage           │
│ status               │
└──────────────────────┘
```

---

## Data Flow

### Learning Flow (Siswa)

1. Siswa login → Lihat dashboard dengan subjects
2. Siswa pilih subject → Lihat list modules
3. Siswa pilih module → Lihat content & video YouTube
4. Siswa answer questions → Submit answers
5. System check answers → Update StudentProgress
6. Siswa lihat hasil sesuai teacher settings

### Teaching Flow (Guru)

1. Guru login → Lihat dashboard
2. Guru create/edit subjects → Tambah/ubah mata pelajaran
3. Guru create/edit modules → Tambah/ubah modul + video
4. Guru create/edit questions → Tambah/ubah soal
5. Guru set settings → Toggle show/hide answers
6. Guru monitor students → Lihat progress siswa

---

## Progress Calculation

**Formula untuk percentage:**
```
percentage = (earned_points / total_points) * 100
```

**Setiap kali siswa submit answer:**
1. Check apakah jawaban benar
2. Set `is_correct` dan `points_earned` di QuestionAnswers
3. Update StudentProgress:
   - `answered_questions` += 1
   - `correct_answers` += 1 (jika benar)
   - `earned_points` += points_earned
   - Recalculate `percentage`
   - Update `status` jika semua soal dijawab

---

## Indices untuk Performance

```sql
-- User-related queries
CREATE INDEX idx_users_role ON users(role);
CREATE INDEX idx_users_email ON users(email);

-- Subject-related queries
CREATE INDEX idx_subjects_created_by ON subjects(created_by);

-- Module-related queries
CREATE INDEX idx_modules_subject_id ON modules(subject_id);
CREATE INDEX idx_modules_created_by ON modules(created_by);

-- Question-related queries
CREATE INDEX idx_questions_module_id ON questions(module_id);
CREATE INDEX idx_questions_created_by ON questions(created_by);

-- Answer-related queries
CREATE INDEX idx_question_answers_user_id ON question_answers(user_id);
CREATE INDEX idx_question_answers_question_id ON question_answers(question_id);
CREATE INDEX idx_question_answers_user_question ON question_answers(user_id, question_id);

-- Progress-related queries
CREATE INDEX idx_student_progress_user_id ON student_progress(user_id);
CREATE INDEX idx_student_progress_subject_id ON student_progress(subject_id);
CREATE INDEX idx_student_progress_user_subject ON student_progress(user_id, subject_id);
```

---

## Seeding Data

### Sample Users
```
Teacher:
- Name: Ibu Siti Nurhaliza
- Email: guru@smp40.com
- Password: password
- Role: 1

Student:
- Name: Budi Santoso
- Email: siswa@smp40.com
- Password: password
- Role: 0
```

### Sample Subjects
```
1. Matematika (🔢, #FF6B6B)
2. Bahasa Indonesia (📚, #4ECDC4)
3. IPA - Sains (🔬, #45B7D1)
4. IPS - Sosial (🌍, #F7B731)
5. Bahasa Inggris (🇬🇧, #5F27CD)
6. Penjas & Olahraga (⚽, #00D2D3)
```

### Sample Modules
```
Matematika:
- Modul 1: Bilangan Bulat
- Modul 2: Pecahan
- Modul 3: Aljabar Dasar

Bahasa Indonesia:
- Modul 1: Teks Narasi
- Modul 2: Puisi

IPA:
- Modul 1: Struktur Atom
- Modul 2: Sistem Pencernaan
```

---

**Last Updated:** February 9, 2026
