@extends('layouts.app')

@section('title', 'Dashboard Guru')

@section('extra-css')
    <style>
        .guru-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 3rem 2rem;
            border-radius: 20px;
            margin-bottom: 3rem;
            box-shadow: 0 15px 40px rgba(99, 102, 241, 0.3);
        }

        .guru-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .guru-header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: var(--card-shadow);
            text-align: center;
            border-top: 4px solid var(--primary-color);
            transition: all 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(99, 102, 241, 0.2);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .stat-label {
            color: #666;
            font-weight: 600;
            margin-top: 0.5rem;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .section-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #2d3748;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .section-title i {
            color: var(--primary-color);
            font-size: 2rem;
        }

        .btn-add {
            background: linear-gradient(135deg, var(--success-color), #059669);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
            color: white;
        }

        .content-section {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 3rem;
            box-shadow: var(--card-shadow);
        }

        .tab-buttons {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .tab-btn {
            padding: 0.75rem 1.5rem;
            border: 2px solid #e5e7eb;
            background: white;
            color: #666;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .tab-btn.active {
            border-color: var(--primary-color);
            background: var(--primary-color);
            color: white;
        }

        .tab-btn:hover {
            border-color: var(--primary-color);
        }

        .table-responsive-custom {
            overflow-x: auto;
            border-radius: 10px;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead {
            background: #f8f9ff;
            border-bottom: 2px solid #e5e7eb;
        }

        .table th {
            color: #2d3748;
            font-weight: 700;
            padding: 1rem;
            border: none;
        }

        .table td {
            padding: 1rem;
            vertical-align: middle;
            border-color: #e5e7eb;
        }

        .table tbody tr {
            transition: all 0.2s;
        }

        .table tbody tr:hover {
            background: #f8f9ff;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .btn-sm {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 0.3rem;
            text-decoration: none;
        }

        .btn-edit {
            background: #dbeafe;
            color: var(--info-color);
        }

        .btn-edit:hover {
            background: var(--info-color);
            color: white;
        }

        .btn-delete {
            background: #fee2e2;
            color: var(--danger-color);
        }

        .btn-delete:hover {
            background: var(--danger-color);
            color: white;
        }

        .btn-view {
            background: #f3f4f6;
            color: #666;
        }

        .btn-view:hover {
            background: #e5e7eb;
        }

        .badge-status {
            display: inline-block;
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .badge-published {
            background: #dcfce7;
            color: #166534;
        }

        .badge-draft {
            background: #f3f4f6;
            color: #6b7280;
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #999;
        }

        .empty-state i {
            font-size: 3rem;
            color: #ccc;
            margin-bottom: 1rem;
            display: block;
        }

        .empty-state p {
            margin-bottom: 1rem;
        }

        .progress-bar-thin {
            height: 8px;
            border-radius: 10px;
            background: #e5e7eb;
            overflow: hidden;
        }

        .progress-fill-thin {
            height: 100%;
            background: linear-gradient(90deg, var(--primary-color), var(--primary-dark));
            border-radius: 10px;
        }

        @media (max-width: 768px) {
            .section-header {
                flex-direction: column;
                gap: 1rem;
            }

            .btn-add {
                width: 100%;
                justify-content: center;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn-sm {
                width: 100%;
                justify-content: center;
            }

            .table {
                font-size: 0.9rem;
            }

            .table th,
            .table td {
                padding: 0.75rem 0.5rem;
            }
        }
    </style>
@endsection

@section('content')
    <!-- Header -->
    <div class="guru-header">
        <h1><i class="fas fa-chalkboard-user"></i> Selamat Datang, {{ auth()->user()->name }}! 👨‍🏫</h1>
        <p>Kelola mata pelajaran, modul, dan soal Anda dengan mudah</p>
    </div>

    <!-- Statistics -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">{{ $totalSubjects }}</div>
            <div class="stat-label">Mata Pelajaran</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $totalModules }}</div>
            <div class="stat-label">Modul</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $totalQuestions }}</div>
            <div class="stat-label">Soal</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $totalStudents }}</div>
            <div class="stat-label">Siswa Terdaftar</div>
        </div>
    </div>

    <!-- Management Sections -->
    <div class="content-section">
        <!-- Tab Buttons -->
        <div class="tab-buttons">
            <button class="tab-btn active" onclick="switchTab('subjects')">
                <i class="fas fa-book"></i> Mata Pelajaran
            </button>
            <button class="tab-btn" onclick="switchTab('modules')">
                <i class="fas fa-layer-group"></i> Modul
            </button>
            <button class="tab-btn" onclick="switchTab('questions')">
                <i class="fas fa-question-circle"></i> Soal
            </button>
            <button class="tab-btn" onclick="switchTab('students')">
                <i class="fas fa-users"></i> Manajemen Siswa
            </button>
            <button class="tab-btn" onclick="switchTab('student-progress')">
                <i class="fas fa-chart-bar"></i> Progress Siswa
            </button>
        </div>

        <!-- SUBJECTS TAB -->
        <div id="subjects-tab" class="tab-content">
            <div class="section-header">
                <div class="section-title">
                    <i class="fas fa-book"></i> Manajemen Mata Pelajaran
                </div>
                <a href="{{ route('guru.subjects.create') }}" class="btn-add">
                    <i class="fas fa-plus"></i> Buat Mata Pelajaran
                </a>
            </div>

            @if ($subjects->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <p>Belum ada mata pelajaran yang dibuat</p>
                    <a href="{{ route('guru.subjects.create') }}" class="btn-add">
                        <i class="fas fa-plus"></i> Buat Mata Pelajaran Pertama
                    </a>
                </div>
            @else
                <div class="table-responsive-custom">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nama Mata Pelajaran</th>
                                <th>Deskripsi</th>
                                <th>Modul</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($subjects as $subject)
                                <tr>
                                    <td>
                                        <strong>{{ $subject->icon }} {{ $subject->name }}</strong>
                                    </td>
                                    <td>{{ Str::limit($subject->description, 50) }}</td>
                                    <td>
                                        <strong>{{ $subject->modules()->count() }}</strong> modul
                                    </td>
                                    <td>
                                        <span class="badge-status badge-published">
                                            <i class="fas fa-check"></i> Aktif
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('guru.subjects.show', $subject->id) }}"
                                                class="btn-sm btn-view">
                                                <i class="fas fa-eye"></i> Lihat
                                            </a>
                                            <a href="{{ route('guru.subjects.edit', $subject->id) }}"
                                                class="btn-sm btn-edit">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('guru.subjects.destroy', $subject->id) }}"
                                                method="POST" style="display: inline;"
                                                onsubmit="return confirm('Hapus mata pelajaran ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-sm btn-delete">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- MODULES TAB -->
        <div id="modules-tab" class="tab-content" style="display: none;">
            <div class="section-header">
                <div class="section-title">
                    <i class="fas fa-layer-group"></i> Manajemen Modul
                </div>
                <a href="{{ route('guru.modules.create') }}" class="btn-add">
                    <i class="fas fa-plus"></i> Buat Modul
                </a>
            </div>

            @if ($modules->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <p>Belum ada modul yang dibuat</p>
                    <a href="{{ route('guru.modules.create') }}" class="btn-add">
                        <i class="fas fa-plus"></i> Buat Modul Pertama
                    </a>
                </div>
            @else
                <div class="table-responsive-custom">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Modul</th>
                                <th>Mata Pelajaran</th>
                                <th>Soal</th>
                                <th>Video</th>
                                <th>PDF</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($modules as $module)
                                <tr>
                                    <td>
                                        <strong>{{ $module->name }}</strong><br>
                                        <small style="color: #999;">Modul {{ $module->module_number }}</small>
                                    </td>
                                    <td>{{ $module->subject->icon }} {{ $module->subject->name }}</td>
                                    <td>{{ $module->questions()->count() }} soal</td>
                                    <td>
                                        @if ($module->video_url)
                                            <span style="color: var(--success-color);">
                                                <i class="fas fa-check"></i> Ada
                                            </span>
                                        @else
                                            <span style="color: #999;">
                                                <i class="fas fa-times"></i> Tidak
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($module->pdf_path)
                                            <a href="{{ asset('storage/' . $module->pdf_path) }}" target="_blank" class="btn-sm btn-view">
                                                <i class="fas fa-file-pdf"></i> Lihat
                                            </a>
                                        @else
                                            <span style="color: #999;"><i class="fas fa-times"></i> Tidak</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span
                                            class="badge-status {{ $module->published ? 'badge-published' : 'badge-draft' }}">
                                            <i class="fas fa-{{ $module->published ? 'check' : 'clock' }}"></i>
                                            {{ $module->published ? 'Dipublikasi' : 'Draft' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('guru.modules.edit', [$module->subject_id, $module->id]) }}"
                                                class="btn-sm btn-edit">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form
                                                action="{{ route('guru.modules.destroy', [$module->subject_id, $module->id]) }}"
                                                method="POST" style="display: inline;"
                                                onsubmit="return confirm('Hapus modul ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-sm btn-delete">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- QUESTIONS TAB -->
        <div id="questions-tab" class="tab-content" style="display: none;">
            <div class="section-header">
                <div class="section-title">
                    <i class="fas fa-question-circle"></i> Manajemen Soal
                </div>
                <a href="{{ route('guru.questions.create') }}" class="btn-add">
                    <i class="fas fa-plus"></i> Buat Soal
                </a>
            </div>

            @if ($questions->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <p>Belum ada soal yang dibuat</p>
                    <a href="{{ route('guru.questions.create') }}" class="btn-add">
                        <i class="fas fa-plus"></i> Buat Soal Pertama
                    </a>
                </div>
            @else
                <div class="table-responsive-custom">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Soal</th>
                                <th>Tipe</th>
                                <th>Modul</th>
                                <th>Poin</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($questions as $question)
                                <tr>
                                    <td><strong>{{ Str::limit($question->question, 50) }}</strong></td>
                                    <td>
                                        <span class="badge-status badge-published"
                                            style="background: #dbeafe; color: var(--info-color);">
                                            {{ ucfirst(str_replace('_', ' ', $question->type)) }}
                                        </span>
                                    </td>
                                    <td>{{ $question->module->name }}</td>
                                    <td>
                                        <span style="color: var(--warning-color); font-weight: 700;">
                                            +{{ $question->points }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('guru.questions.edit', $question->id) }}"
                                                class="btn-sm btn-edit">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('guru.questions.destroy', $question->id) }}"
                                                method="POST" style="display: inline;"
                                                onsubmit="return confirm('Hapus soal ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-sm btn-delete">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- STUDENTS MANAGEMENT TAB -->
        <div id="students-tab" class="tab-content" style="display: none;">
            <div class="section-header">
                <div class="section-title">
                    <i class="fas fa-users"></i> Manajemen Siswa
                </div>
                <a href="{{ route('guru.students.create') }}" class="btn-add">
                    <i class="fas fa-user-plus"></i> Tambah Siswa
                </a>
            </div>

            @if ($students->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <p>Belum ada siswa yang terdaftar</p>
                    <a href="{{ route('guru.students.create') }}" class="btn-add">
                        <i class="fas fa-user-plus"></i> Tambah Siswa Pertama
                    </a>
                </div>
            @else
                <div class="table-responsive-custom">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nama Siswa</th>
                                <th>Email</th>
                                <th>No. HP</th>
                                <th>Kelas</th>
                                <th>Wali Kelas</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($students as $student)
                                <tr>
                                    <td><strong>{{ $student->name }}</strong></td>
                                    <td>{{ $student->email }}</td>
                                    <td>{{ $student->phone ?? '-' }}</td>
                                    <td>{{ $student->class ?? '-' }}</td>
                                    <td>{{ $student->homeroom_teacher ?? '-' }}</td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('guru.students.edit', $student->id) }}"
                                                class="btn-sm btn-edit">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('guru.students.destroy', $student->id) }}"
                                                method="POST" style="display: inline;"
                                                onsubmit="return confirm('Hapus siswa ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-sm btn-delete">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" style="text-align: center; color: #999;">Tidak ada data siswa</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($students->hasPages())
                    <div style="margin-top: 2rem;">
                        {{ $students->links() }}
                    </div>
                @endif
            @endif
        </div>

        <!-- STUDENT PROGRESS TAB -->
        <div id="student-progress-tab" class="tab-content" style="display: none;">
            <div class="section-header">
                <div class="section-title">
                    <i class="fas fa-chart-bar"></i> Progress Siswa
                </div>
                <a href="{{ route('guru.settings') }}" class="btn-add">
                    <i class="fas fa-cog"></i> Pengaturan
                </a>
            </div>

            @if ($studentProgress->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <p>Belum ada siswa yang mengerjakan soal</p>
                </div>
            @else
                <div class="table-responsive-custom">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Siswa</th>
                                <th>Mata Pelajaran</th>
                                <th>Status</th>
                                <th>Progress</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($studentProgress as $progress)
                                <tr>
                                    <td>
                                        <strong>{{ $progress->user->name }}</strong><br>
                                        <small style="color: #999;">{{ $progress->user->email }}</small>
                                    </td>
                                    <td>{{ $progress->subject->icon }} {{ $progress->subject->name }}</td>
                                    <td>
                                        <span
                                            class="badge-status {{ match ($progress->status) {
                                                'completed' => 'badge-published',
                                                'in_progress' => 'badge-draft',
                                                default => 'badge-draft',
                                            } }}">
                                            {{ ucfirst(str_replace('_', ' ', $progress->status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div style="min-width: 200px;">
                                            <div
                                                style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; font-size: 0.9rem;">
                                                <span>{{ round($progress->percentage) }}%</span>
                                                <span
                                                    style="color: #999;">{{ $progress->correct_answers }}/{{ $progress->total_questions }}</span>
                                            </div>
                                            <div class="progress-bar-thin">
                                                <div class="progress-fill-thin"
                                                    style="width: {{ $progress->percentage }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('guru.student-progress.show', [$progress->user_id, $progress->subject_id]) }}"
                                            class="btn-sm btn-view">
                                            <i class="fas fa-eye"></i> Lihat Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('extra-js')
    <script>
        function switchTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.style.display = 'none';
            });

            // Remove active class from all buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });

            // Show selected tab
            const tabId = tabName + '-tab';
            const tabElement = document.getElementById(tabId);
            if (tabElement) {
                tabElement.style.display = 'block';
            }

            // Add active class to clicked button
            event.target.classList.add('active');
        }
    </script>
@endsection
