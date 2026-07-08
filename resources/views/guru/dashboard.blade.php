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
            box-shadow: 0 15px 40px rgba(17, 24, 68, 0.3);
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
            box-shadow: 0 15px 40px rgba(17, 24, 68, 0.2);
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
            color: #f8f8f9;
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

        .subject-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .subject-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border: 1px solid #e5e7eb;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .subject-card:hover {
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
            border-color: #d1d5db;
        }

        .subject-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
            padding: 1.5rem;
            background: linear-gradient(135deg, #f8fafc 0%, #f3f4f6 100%);
            cursor: pointer;
            border-bottom: 1px solid #e5e7eb;
        }

        .subject-card-header h2 {
            margin: 0;
            font-size: 1.15rem;
            font-weight: 700;
            color: #1f2937;
        }

        .subject-card-header p {
            margin: 0.4rem 0 0 0;
            font-size: 0.9rem;
            color: #6b7280;
            line-height: 1.4;
        }

        .subject-card-body {
            padding: 0.9rem;
            display: none;
            background: white;
            max-height: 800px;
            overflow-y: auto;
        }

        .subject-card-body.active {
            display: block;
        }

        .subject-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.45rem 0.85rem;
            border-radius: 999px;
            font-size: 0.85rem;
            font-weight: 700;
            color: #334155;
            background: #f8fafc;
        }

        .module-box {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 0.75rem 0.9rem;
            display: grid;
            gap: 0.6rem;
            transition: all 0.3s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            margin-left: 0;
        }

        .module-box:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            border-color: #d1d5db;
        }

        .module-box-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
            flex-wrap: wrap;
            border-bottom: 1px solid #f3f4f6;
            padding-bottom: 0.6rem;
        }

        .module-box-header > div:first-child {
            flex: 1;
            min-width: 250px;
        }

        .module-box-header > div:first-child > div:first-child {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.5rem;
        }

        .module-box-header > div:first-child > div:first-child > div:first-child {
            font-weight: 700;
            font-size: 1.1rem;
            color: #1f2937;
        }

        .module-box-header > div:first-child > div:first-child > div:nth-child(2) {
            font-size: 0.8rem;
            background: #dbeafe;
            color: #0369a1;
            padding: 0.25rem 0.6rem;
            border-radius: 6px;
            font-weight: 600;
        }

        .module-box-header > div:first-child > div:nth-child(2) {
            color: #6b7280;
            font-size: 0.9rem;
            margin-top: 0.25rem;
        }

        .module-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            justify-content: flex-end;
        }

        .question-card-row {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 1rem;
            display: block;
            transition: all 0.2s ease;
        }

        .question-card-row:hover {
            background: white;
            border-color: #d1d5db;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .question-card-row .question-row-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
            user-select: none;
        }

        .question-card-row .question-row-header > div:first-child {
            flex: 1;
            min-width: 0;
        }

        .question-card-row .question-detail {
            display: none;
            margin-top: 0.75rem;
            padding-top: 0.75rem;
            border-top: 1px solid #e5e7eb;
            color: #374151;
            line-height: 1.6;
        }

        .question-card-row.expanded .question-detail {
            display: block;
        }

        .question-card-row strong {
            color: #1f2937;
            display: block;
            font-size: 0.95rem;
            margin-bottom: 0.4rem;
        }

        .question-card-row .question-meta {
            color: #6b7280;
            font-size: 0.85rem;
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .module-summary {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-top: 0.85rem;
        }

        .module-summary span {
            background: #eef2ff;
            color: #1d4ed8;
            padding: 0.45rem 0.8rem;
            border-radius: 999px;
            font-weight: 700;
            font-size: 0.82rem;
        }

        .task-questions {
            display: grid;
            gap: 0.75rem;
            background: #fafbfc;
            padding: 0.75rem;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
        }

        .task-questions .question-card-row {
            margin-bottom: 0;
            background: white;
        }

        .subject-class-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 0.85rem;
        }

        .subject-class-chips .class-chip,
        .subject-class-chips .class-chip-disabled {
            border: 1px solid #d1d5db;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.2s ease;
            background: white;
            color: #374151;
        }

        .subject-class-chips .class-chip:hover {
            background: #eff6ff;
            border-color: #0369a1;
            color: #0369a1;
        }

        .subject-class-chips .class-chip.selected {
            background: linear-gradient(135deg, #0369a1 0%, #0284c7 100%);
            color: white;
            border-color: #0369a1;
            box-shadow: 0 4px 12px rgba(3, 105, 161, 0.3);
        }

        .subject-class-chips .class-chip-disabled {
            background: #f9fafb;
            color: #9ca3af;
            cursor: default;
        }

        .subject-class-chips .class-chip-disabled {
            background: #f3f4f6;
            color: #64748b;
            cursor: default;
        }

        .btn-secondary-outline {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.65rem 1.1rem;
            border-radius: 8px;
            border: 1.5px solid #d1d5db;
            background: white;
            color: #374151;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-secondary-outline:hover {
            background: #f9fafb;
            border-color: #9ca3af;
            color: #1f2937;
        }

        .btn-secondary-outline:hover {
            background: #f8fafc;
        }

        .subject-chevron {
            transition: transform 0.2s ease;
        }

        .subject-chevron.open {
            transform: rotate(90deg);
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
            padding: 3rem 2rem;
            color: #999;
            background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
            border-radius: 12px;
            border: 1px solid #e5e7eb;
        }

        .empty-state i {
            font-size: 3.5rem;
            color: #d1d5db;
            margin-bottom: 1rem;
            display: block;
            opacity: 0.6;
        }

        .empty-state p {
            margin-bottom: 1.5rem;
            color: #6b7280;
            font-size: 1rem;
        }

        .accordion-header {
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.6rem 0.5rem;
            border-radius: 8px;
            background: #fbfbff;
            margin-bottom: 0.6rem;
        }

        .accordion-chevron {
            transition: transform 0.25s ease;
            display: inline-block;
        }

        .accordion-chevron.open {
            transform: rotate(90deg);
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

        /* Modal Manage Kelas */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            align-items: center;
            justify-content: center;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.3s ease;
            display: flex;
            flex-direction: column;
            max-height: calc(100vh - 40px);
            overflow: hidden;
        }

        .modal-content.modal-large {
            max-width: 900px;
        }

        .modal-content .modal-body {
            overflow-y: auto;
            max-height: calc(100vh - 220px);
        }

        @keyframes slideUp {
            from {
                transform: translateY(30px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #e5e7eb;
        }

        .modal-header h2 {
            margin: 0;
            font-size: 1.5rem;
            color: #111827;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #666;
            cursor: pointer;
            transition: color 0.2s;
        }

        .modal-close:hover {
            color: #111827;
        }

        .modal-body {
            margin-bottom: 1.5rem;
        }

        .modal-body .form-group {
            margin-bottom: 1rem;
        }

        .modal-body label {
            display: block;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: #2d3748;
        }

        .modal-body select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 0.95rem;
        }

        .modal-body select:focus {
            border-color: var(--primary-color);
            outline: none;
        }

        .modal-footer {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }

        .modal-btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .modal-btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
        }

        .modal-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(17, 24, 68, 0.3);
        }

        .modal-btn-secondary {
            background: #f3f4f6;
            color: #666;
        }

        .modal-btn-secondary:hover {
            background: #e5e7eb;
        }

        .modal-spinner {
            display: none;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .modal-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
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
            <button class="tab-btn active" data-tab="subjects" onclick="switchTab('subjects', this)">
                <i class="fas fa-book"></i> Mata Pelajaran
            </button>
            <button class="tab-btn" data-tab="classes" onclick="switchTab('classes', this)">
                <i class="fas fa-door-open"></i> Manage Kelas
            </button>
            <button class="tab-btn" data-tab="students" onclick="switchTab('students', this)">
                <i class="fas fa-users"></i> Manage Siswa
            </button>
            <button class="tab-btn" data-tab="student-progress" onclick="switchTab('student-progress', this)">
                <i class="fas fa-chart-bar"></i> Progress Siswa
            </button>
            <button class="tab-btn" data-tab="submissions" onclick="switchTab('submissions', this)">
                <i class="fas fa-inbox"></i> Submissions
            </button>
        </div>

        <!-- SUBJECTS TAB -->
        <div id="subjects-tab" class="tab-content">
            <div class="section-header">
                <div class="section-title">
                    <i class="fas fa-book"></i> Mata Pelajaran
                </div>
                <a href="{{ route('guru.subjects.create') }}" class="btn-add">
                    <i class="fas fa-plus"></i> Tambah Mata Pelajaran
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
                <div class="subject-grid">
                    @foreach ($subjects as $subject)
                        @php
                            $subjectModules = $modules->where('subject_id', $subject->id);
                            $subjectQuestionsCount = $questions->whereIn('module_id', $subjectModules->pluck('id'))->count();

                            $subjectClassChips = $subject->getClassChips($classesByGrade);
                        @endphp
                        <div class="subject-card" data-subject-classes="{{ implode(' ', $subjectClassChips) }}">
                            <div class="subject-card-header" onclick="toggleSubjectDetails({{ $subject->id }});">
                                <div>
                                        <div style="width:44px; height:44px; border-radius:14px; background: rgba(59, 130, 246, 0.15); display:flex; align-items:center; justify-content:center; color:#1d4ed8; font-size:1.1rem;">
                                            <i class="fas fa-book"></i>
                                        </div>
                                        <div>
                                            <h2>{{ $subject->name }}</h2>
                                            <p style="margin:0.5rem 0 0 0; color:#475569; font-size:0.95rem; max-width:520px;">{{ Str::limit($subject->description ?: 'Tidak ada deskripsi.', 120) }}</p>
                                        </div>
                                    </div>
                                    <div class="module-summary">
                                        <span>{{ $subjectModules->count() }} Modul</span>
                                        <span>{{ $subjectQuestionsCount }} Soal</span>
                                    </div>
                                    <div class="subject-class-chips">
                                        @if (!empty($subjectClassChips))
                                            @foreach ($subjectClassChips as $classChip)
                                                <button type="button" class="class-chip" data-filter-class="{{ $classChip }}" onclick="event.stopPropagation(); filterSubjectsByClass('{{ $classChip }}', this);">{{ $classChip }}</button>
                                            @endforeach
                                        @else
                                            <span class="class-chip class-chip-disabled">Semua kelas</span>
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <span id="subject-chevron-{{ $subject->id }}" class="subject-chevron">▶</span>
                                </div>
                            </div>
                            <div id="subject-details-{{ $subject->id }}" class="subject-card-body">
                                <div style="display:flex; gap:0.6rem; flex-wrap:wrap; margin-bottom:0.9rem;">
                                    <a href="{{ route('guru.subjects.edit', $subject->id) }}" class="btn-secondary-outline"> <i class="fas fa-edit"></i> Edit Mata Pelajaran</a>
                                    <a href="{{ route('guru.modules.create') }}?subject_id={{ $subject->id }}" class="btn-secondary-outline"> <i class="fas fa-plus"></i> Tambah Modul</a>
                                    <form method="POST" action="{{ route('guru.subjects.destroy', $subject->id) }}" onsubmit="return confirm('Hapus mata pelajaran ini beserta semua modul dan soal yang terkait?');" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-secondary-outline" style="color:#b91c1c; border-color:#fca5a5;"> <i class="fas fa-trash"></i> Hapus Mata Pelajaran</button>
                                    </form>
                                </div>

                                <div style="border-top: 1px solid #e5e7eb; padding-top: 0.9rem;">
                                    <h3 style="font-size:1rem; font-weight:700; color:#1f2937; margin:0 0 0.8rem 0; display:flex; align-items:center; gap:0.5rem;"><i class="fas fa-book-open"></i> Modul</h3>
                                    @if ($subjectModules->isEmpty())
                                        <div style="padding:1rem; border-radius:12px; background:#f9fafb; color:#6b7280; border:1px dashed #e5e7eb; text-align:center;">
                                            <i class="fas fa-inbox" style="font-size:1.75rem; color:#d1d5db; margin-bottom:0.4rem; display:block;"></i>
                                            <p style="margin:0; font-size:0.9rem;">Belum ada modul untuk mata pelajaran ini. Klik tombol "Tambah Modul" untuk memulai.</p>
                                        </div>
                                    @else
                                        <div style="display:grid; gap:0.8rem;">
                                            @foreach ($subjectModules as $module)
                                                @php
                                                    $moduleQuestions = $questions->where('module_id', $module->id);
                                                @endphp
                                                <div class="module-box" data-module-class="{{ $module->class ?? '' }}">
                                                <div class="module-box-header">
                                                    <div>
                                                        <div style="display:flex; align-items:center; gap:0.75rem;">
                                                            <div style="font-weight:700; color:#0f172a;">{{ $module->name }}</div>
                                                            @if($module->class)
                                                                <div style="font-size:0.8rem; background:#eef2ff; color:#1d4ed8; padding:0.25rem 0.5rem; border-radius:8px;">{{ $module->class }}</div>
                                                            @endif
                                                        </div>
                                                        <div style="color:#64748b; font-size:0.9rem; margin-top:0.35rem;">Modul {{ $module->module_number }} · <span class="module-question-count">{{ $moduleQuestions->count() }}</span> soal</div>
                                                    </div>
                                                    <div class="module-actions">
                                                        @if ($module->published)
                                                            <span style="background:#ecfdf5; color:#166534; padding:0.45rem 0.8rem; border-radius:999px; font-size:0.82rem;">Dipublikasi</span>
                                                        @else
                                                            <span style="background:#f8fafc; color:#475569; padding:0.45rem 0.8rem; border-radius:999px; font-size:0.82rem;">Draft</span>
                                                        @endif
                                                        <a href="{{ route('guru.modules.edit', [$module->subject_id, $module->id]) }}" class="btn-secondary-outline" style="font-size:0.85rem;">Ubah Modul</a>
                                                        <form method="POST" action="{{ route('guru.modules.destroy', [$module->subject_id, $module->id]) }}" onsubmit="return confirm('Hapus modul ini beserta semua soal yang terkait?');" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn-secondary-outline" style="color:#b91c1c; border-color:#fca5a5; font-size:0.85rem;">Hapus Modul</button>
                                                        </form>
                                                        <a href="{{ route('guru.questions.create') }}?module_id={{ $module->id }}" class="btn-secondary-outline" style="font-size:0.85rem;">Tambah Soal</a>
                                                    </div>
                                                </div>
                                                @if ($module->content)
                                                    <div style="color:#475569; font-size:0.9rem;">{{ Str::limit($module->content, 150) }}</div>
                                                @endif
                                                <div style="display:flex; gap:0.6rem; flex-wrap:wrap; margin-top:0.5rem;">
                                                    @if ($module->video_url)
                                                        <span style="background:#eef2ff; color:#1d4ed8; padding:0.35rem 0.65rem; border-radius:999px; font-size:0.75rem;">Video</span>
                                                    @endif
                                                    @if ($module->pdf_path)
                                                        <span style="background:#fef2f2; color:#991b1b; padding:0.35rem 0.65rem; border-radius:999px; font-size:0.75rem;">PDF</span>
                                                    @endif
                                                </div>
                                                @php
                                                    $moduleTasks = \App\Models\Task::where('module_id', $module->id)->get();
                                                @endphp
                                                <div style="margin-top:0.5rem;">
                                                    @if($moduleTasks->isNotEmpty())
                                                        <div style="display:flex; flex-direction:column; gap:0.4rem; margin-bottom:0.4rem;">
                                                            @foreach($moduleTasks as $task)
                                                                <div style="display:flex; align-items:center; gap:0.5rem;">
                                                                    <button type="button" class="btn-secondary-outline btn-open-task" data-task-id="{{ $task->id }}" style="font-size:0.85rem; padding:0.4rem 0.7rem;">{{ $task->name }} <span style="color:#64748b; font-size:0.8rem;">({{ count($task->question_ids ?? []) }} soal)</span></button>
                                                                    <form method="POST" action="{{ route('guru.tasks.destroy', $task->id) }}" onsubmit="return confirm('Hapus tugas ini?');" style="display:inline;">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="btn-secondary-outline" style="font-size:0.75rem; color:#b91c1c; border-color:#fca5a5; padding:0.4rem 0.7rem;">Hapus</button>
                                                                    </form>
                                                                </div>
                                                                <div id="task-questions-{{ $task->id }}" class="task-questions" style="display:none; margin-left:0.5rem; margin-top:0.3rem;">
                                                                    @php $tqs = $task->questions(); @endphp
                                                                    @if($tqs->isEmpty())
                                                                        <div style="color:#475569; font-size:0.85rem;">Tidak ada soal pada tugas ini.</div>
                                                                    @else
                                                                        @foreach($tqs as $tq)
                                                                            <div class="question-card-row" data-question-class="{{ $tq->class ?? '' }}" style="margin-bottom:0.4rem;">
                                                                                <div class="question-row-header">
                                                                                    <div>
                                                                                        <strong style="font-size:0.9rem;">{{ Str::limit($tq->question, 80) }}</strong>
                                                                                        <div class="question-meta" style="font-size:0.8rem;">{{ ucfirst(str_replace('_', ' ', $tq->type)) }} · {{ $tq->points }} pts</div>
                                                                                    </div>
                                                                                    <div style="display:flex; gap:0.5rem; align-items:center;">
                                                                                        <a href="{{ route('guru.questions.edit', $tq->id) }}" class="btn-secondary-outline question-action" style="font-size:0.75rem; padding:0.35rem 0.6rem;">Ubah</a>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="question-detail">
                                                                                    <div style="white-space:pre-wrap; font-size:0.85rem;">{{ $tq->question }}</div>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="module-questions-wrapper" id="module-questions-{{ $module->id }}" style="margin-top:0.6rem; display:grid; grid-gap:0.5rem;">
                                                    @if ($moduleQuestions->isEmpty())
                                                        <div style="color:#475569; font-size:0.85rem;">Belum ada soal pada modul ini.</div>
                                                    @else
                                                        @foreach ($moduleQuestions as $question)
                                                            <div class="question-card-row" data-question-class="{{ $question->class ?? '' }}">
                                                                <div class="question-row-header">
                                                                    <div>
                                                                        <strong style="font-size:0.9rem;">{{ Str::limit($question->question, 80) }}</strong>
                                                                        <div class="question-meta" style="font-size:0.8rem;">{{ ucfirst(str_replace('_', ' ', $question->type)) }} · {{ $question->points }} pts @if($question->class) · <span style="background:#fff7ed; color:#92400e; padding:0.12rem 0.35rem; border-radius:6px; font-size:0.75rem;">{{ $question->class }}</span>@endif</div>
                                                                    </div>
                                                                    <div style="display:flex; gap:0.4rem; align-items:center;">
                                                                        <a href="{{ route('guru.questions.edit', $question->id) }}" class="btn-secondary-outline question-action" style="font-size:0.75rem; padding:0.35rem 0.6rem;">Ubah</a>
                                                                        <form method="POST" action="{{ route('guru.questions.destroy', $question->id) }}" onsubmit="return confirm('Hapus soal ini?');" style="display:inline;" class="question-action">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit" class="btn-secondary-outline" style="color:#b91c1c; border-color:#fca5a5; font-size:0.75rem; padding:0.35rem 0.6rem;">Hapus</button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                                <div class="question-detail">
                                                                    <div style="font-weight:700; color:#0f172a; margin-bottom:0.15rem; font-size:0.85rem;">Full question</div>
                                                                    <div style="white-space:pre-wrap; font-size:0.85rem;">{{ $question->question }}</div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Debug panel removed --}}

        <!-- Modal Manage Students per Academic Year -->
        <div id="manageStudentsModal" class="modal-overlay">
            <div class="modal-content modal-large">
                <div class="modal-header">
                    <h2>Kelola Siswa - <span id="manageStudentsYear">-</span></h2>
                    <button type="button" class="modal-close" onclick="closeManageStudentsModal()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body" id="manageStudentsBody">
                    <div style="padding:1rem; color:#666;">Memuat daftar siswa...</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="modal-btn modal-btn-secondary" onclick="closeManageStudentsModal()">Tutup</button>
                    <button type="button" class="modal-btn modal-btn-primary" id="saveManageStudentsBtn" onclick="saveManageStudents()">Simpan Perubahan</button>
                </div>
            </div>
        </div>

        <!-- MANAGE KELAS TAB -->
        <div id="classes-tab" class="tab-content" style="display: none;">
            <div class="section-header">
                <div>
                    <div class="section-title">
                        <i class="fas fa-door-open"></i> Manage Kelas
                    </div>
                    <p style="margin:0.5rem 0 0; color:#FFFFFF; max-width:760px;">Atur tahun ajaran, penempatan kelas, dan wali kelas siswa per tahun. Akun siswa permanen tetap dikelola di tab Manage Siswa.</p>
                </div>
                <button class="btn-add" onclick="openAddAcademicYearModal()">
                    <i class="fas fa-plus"></i> Tambah Tahun Ajaran
                </button>
            </div>

            @if ($academicYears->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <p>Belum ada tahun ajaran yang ada</p>
                    <button class="btn-add" onclick="openAddAcademicYearModal()">
                        <i class="fas fa-plus"></i> Buat Tahun Ajaran Pertama
                    </button>
                </div>
            @else
                <div class="table-responsive-custom">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Tahun Ajaran</th>
                                <th>Jumlah Siswa</th>
                                <th>Jumlah Kelas</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($academicYears as $year)
                                <tr>
                                    <td>
                                        <strong>{{ $year->name }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge-draft" style="background: #eef2ff; color: #1d4ed8; padding: 0.4rem 0.8rem; border-radius: 999px; font-weight: 600;">
                                            {{ $year->student_count ?? '-' }} Siswa
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge-draft" style="background: #eff6ff; color: #1d4ed8; padding: 0.4rem 0.8rem; border-radius: 999px; font-weight: 600;">
                                            {{ $year->class_count ?? '-' }} Kelas
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button type="button" class="btn-sm btn-view" onclick='openEditAcademicYearModal({{ $year->id }}, @json($year->name))'>
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button type="button" class="btn-sm btn-view" onclick='openManageStudentsModal({{ $year->id }}, @json($year->name))'>
                                                <i class="fas fa-users"></i> Kelola Siswa
                                            </button>
                                            <button type="button" class="btn-sm btn-delete" onclick='deleteAcademicYear({{ $year->id }}, @json($year->name))'>
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
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
            @include('guru.students.partials.list')
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

        <!-- SUBMISSIONS TAB -->
        <div id="submissions-tab" class="tab-content" style="display: none;">
            <div class="section-header">
                <div class="section-title">
                    <i class="fas fa-inbox"></i> Submissions
                </div>
            </div>

            @if ($submissions->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <p>Belum ada jawaban yang dikumpulkan untuk soal Anda</p>
                </div>
            @else
                <div class="table-responsive-custom">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Siswa</th>
                                <th>Terakhir Kirim</th>
                                <th>Total Jawaban</th>
                                <th>Menunggu Dinilai</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($submissions as $userId => $answers)
                                @php
                                    $user = $answers->first()->user;
                                    $last = $answers->first()->created_at;
                                    $total = $answers->count();
                                    $pending = $answers
                                        ->filter(
                                            fn($a) => is_null($a->teacher_score) &&
                                                in_array($a->question->type, ['essay', 'mixed']),
                                        )
                                        ->count();
                                @endphp
                                <tr>
                                    <td>
                                        <strong>{{ $user->name }}</strong><br>
                                        <small style="color:#999;">{{ $user->email }}</small>
                                    </td>
                                    <td>{{ $last->diffForHumans() }}</td>
                                    <td>{{ $total }}</td>
                                    <td>{{ $pending }}</td>
                                    <td>
                                        <a href="#" class="btn-sm btn-view"
                                            onclick="toggleAnswers({{ $userId }}); return false;">
                                            <i class="fas fa-eye"></i> Lihat Jawaban
                                        </a>
                                    </td>
                                </tr>

                                <tr id="answers-{{ $userId }}" style="display:none;">
                                    <td colspan="5">
                                        <div style="padding:1rem; background:#fbfbff; border-radius:8px;">
                                            @php
                                                $notes = $teacherNotes[$userId] ?? null;
                                                $latestNote = $notes ? $notes->first() : null;
                                            @endphp
                                            <div style="margin-bottom:0.75rem;">
                                                <strong>Catatan Guru Terbaru:</strong>
                                                @if ($latestNote)
                                                    <div
                                                        style="background:#f6fffa; padding:0.5rem; border-radius:6px; margin-top:0.4rem; color:#064e3b;">
                                                        {{ Str::limit($latestNote->note, 300) }}<br><small
                                                            style="color:#666;">Diberikan:
                                                            {{ $latestNote->created_at->diffForHumans() }}</small></div>
                                                @else
                                                    <div style="color:#6b7280; margin-top:0.4rem;">Belum ada catatan</div>
                                                @endif
                                            </div>
                                            @foreach ($answers as $ans)
                                                <div
                                                    style="padding:0.75rem; border-bottom:1px solid #eef2ff; display:flex; justify-content:space-between; gap:1rem;">
                                                    <div style="flex:1;">
                                                        <div style="font-weight:700;">
                                                            {{ Str::limit($ans->question->question, 120) }}</div>
                                                        <div style="color:#666; margin-top:0.4rem;">
                                                            {{ ucfirst($ans->question->type) }} · Modul:
                                                            {{ $ans->question->module->name ?? '-' }}</div>
                                                        <div style="margin-top:0.6rem;">Jawaban:
                                                            <strong>{{ Str::limit($ans->answer, 200) }}</strong>
                                                        </div>
                                                    </div>
                                                    <div
                                                        style="display:flex; flex-direction:column; gap:0.5rem; align-items:flex-end;">
                                                        @if (is_null($ans->teacher_score) && in_array($ans->question->type, ['essay', 'mixed']))
                                                            <a href="{{ route('guru.grading.show', $ans->id) }}"
                                                                class="btn-sm btn-edit"> <i class="fas fa-pen"></i>
                                                                Nilai</a>
                                                        @else
                                                            <span
                                                                style="color:#6b7280;">{{ is_null($ans->teacher_score) ? ($ans->is_correct ? 'Benar' : 'Salah') : $ans->teacher_score . ' pts' }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                            <div style="margin-top:1rem;">
                                                <form action="{{ route('guru.students.note') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                                                    <input type="hidden" name="subject_id" value="">
                                                    <input type="hidden" name="module_id" value="">
                                                    <div style="margin-bottom:0.5rem;">
                                                        <label for="note-{{ $user->id }}"
                                                            style="font-weight:700;">Tambah Catatan untuk siswa</label>
                                                        <textarea id="note-{{ $user->id }}" name="note" rows="3"
                                                            style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid #e5e7eb;">{{ old('note') }}</textarea>
                                                    </div>
                                                    <div style="display:flex; gap:0.5rem; justify-content:flex-end;">
                                                        <button class="btn-sm btn-edit" type="submit"><i
                                                                class="fas fa-save"></i> Simpan Catatan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Add/Edit Kelas -->
    <div id="classManageModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="classModalTitle">Tambah Kelas Baru</h2>
                <button type="button" class="modal-close" onclick="closeClassManageModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label for="classNameInput">Tahun Ajaran:</label>
                    <input type="text" id="classNameInput" placeholder="Contoh: 2025/2026" style="width: 100%; padding: 0.75rem 1rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 0.95rem;">
                </div>
                <small style="color: #666; display: block; margin-top: -0.5rem; margin-bottom: 1rem;">Gunakan format: 2025/2026 atau 2025-2026</small>
            </div>

            <div class="modal-footer">
                <button type="button" class="modal-btn modal-btn-secondary" onclick="closeClassManageModal()">
                    Batal
                </button>
                <button type="button" id="submitClassBtn" class="modal-btn modal-btn-primary" onclick="submitClassManage()">
                    <span class="modal-spinner"></span>
                    Simpan
                </button>
            </div>
        </div>
    </div>
@endsection

@section('extra-js')
    <script>
        function switchTab(tabName, btn) {
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

            // Add active class to clicked button (if provided) or find by data-tab
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            if (btn && btn.classList) {
                btn.classList.add('active');
            } else {
                const btnEl = document.querySelector('.tab-btn[data-tab="' + tabName + '"]');
                if (btnEl) btnEl.classList.add('active');
            }
        }

        function toggleAnswers(userId) {
            const row = document.getElementById('answers-' + userId);
            if (!row) return;
            row.style.display = (row.style.display === 'none' || row.style.display === '') ? 'table-row' : 'none';
            if (row.style.display === 'table-row') row.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }

        function toggleSubjectDetails(subjectId) {
            const details = document.getElementById('subject-details-' + subjectId);
            const chevron = document.getElementById('subject-chevron-' + subjectId);
            if (!details) return;
            const isOpen = details.classList.contains('active');
            details.classList.toggle('active');
            if (chevron) chevron.classList.toggle('open', !isOpen);
            if (!isOpen) {
                details.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }
        }

        // Question filtering
        const modulesData = @json($modules->map(fn($m) => ['id' => $m->id, 'name' => $m->name, 'subject_id' => $m->subject_id]));

        function populateModuleOptions(subjectId) {
            const select = document.getElementById('filter-module');
            if (!select) return; // guard when the filter-module element is not present on the page
            select.innerHTML = '';
            const defaultOpt = document.createElement('option');
            defaultOpt.value = '';
            defaultOpt.text = 'Semua Modul';
            select.appendChild(defaultOpt);

            const filtered = subjectId ? modulesData.filter(m => String(m.subject_id) === String(subjectId)) : modulesData;
            filtered.forEach(m => {
                const opt = document.createElement('option');
                opt.value = m.id;
                opt.text = m.name;
                select.appendChild(opt);
            });

            select.disabled = filtered.length === 0;
        }

        function applyQuestionFilter() {
            const subjectId = document.getElementById('filter-subject').value;
            const moduleId = document.getElementById('filter-module').value;
            document.querySelectorAll('#questions-tab table tbody tr').forEach(row => {
                const rSubject = row.getAttribute('data-subject-id') || '';
                const rModule = row.getAttribute('data-module-id') || '';
                let visible = true;
                if (subjectId && String(rSubject) !== String(subjectId)) visible = false;
                if (moduleId && String(rModule) !== String(moduleId)) visible = false;
                row.style.display = visible ? '' : 'none';
            });
        }

        function resetQuestionFilter() {
            document.getElementById('filter-subject').value = '';
            populateModuleOptions('');
            document.getElementById('filter-module').value = '';
            applyQuestionFilter();
        }

        function showStudentsMessage(message, type = 'success') {
            const container = document.getElementById('students-actions-message');
            if (!container) return;
            container.innerHTML = `<div style="padding:1rem; margin-bottom:1rem; border-radius:10px; background:${type === 'success' ? '#ecfdf5' : '#fef3f2'}; color:${type === 'success' ? '#03543f' : '#991b1b'}; border:1px solid ${type === 'success' ? '#a7f3d0' : '#fecaca'};">${message}</div>`;
            setTimeout(() => {
                if (container) container.innerHTML = '';
            }, 6000);
        }

        function attachStudentListeners() {
            const form = document.getElementById('students-filter-form');
            if (form) {
                form.addEventListener('submit', function(event) {
                    event.preventDefault();
                    const formData = new FormData(form);
                    const params = new URLSearchParams();
                    for (const [key, value] of formData.entries()) {
                        if (value) params.append(key, value);
                    }
                    params.append('tab', 'students');
                    fetchStudentList(params);
                });
            }

            const resetBtn = document.getElementById('students-reset-btn');
            if (resetBtn) {
                resetBtn.addEventListener('click', function() {
                    const qInput = document.getElementById('search-q');
                    const classSelect = document.getElementById('class');
                    if (qInput) qInput.value = '';
                    if (classSelect) classSelect.value = '';
                    const params = new URLSearchParams();
                    params.append('tab', 'students');
                    fetchStudentList(params);
                });
            }

            document.querySelectorAll('#students-tab form[data-ajax-delete="true"]').forEach(form => {
                form.addEventListener('submit', function(event) {
                    event.preventDefault();
                    if (!confirm('Hapus siswa ini?')) return;
                    const action = form.getAttribute('action');
                    const token = form.querySelector('input[name="_token"]')?.value;
                    const method = form.querySelector('input[name="_method"]')?.value || 'POST';
                    fetch(action, {
                        method: method === 'DELETE' ? 'DELETE' : 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token || '',
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                    }).then(async response => {
                        if (!response.ok) {
                            const data = await response.json().catch(() => null);
                            throw new Error(data?.message || 'Gagal menghapus siswa.');
                        }
                        return response.json();
                    }).then(data => {
                        showStudentsMessage(data.message || 'Siswa berhasil dihapus.', 'success');
                        const currentClass = document.getElementById('class')?.value || '';
                        const currentSearch = document.getElementById('search-q')?.value || '';
                        const params = new URLSearchParams();
                        if (currentClass) params.append('class', currentClass);
                        if (currentSearch) params.append('q', currentSearch);
                        params.append('tab', 'students');
                        fetchStudentList(params);
                    }).catch(error => {
                        showStudentsMessage(error.message, 'error');
                    });
                });
            });

            document.querySelectorAll('#students-tab .pagination a').forEach(link => {
                link.addEventListener('click', function(event) {
                    event.preventDefault();
                    const url = new URL(this.href, window.location.origin);
                    const params = new URLSearchParams(url.search);
                    params.set('tab', 'students');
                    fetchStudentList(params);
                });
            });
        }

        function fetchStudentList(params) {
            const baseUrl = @json(route('guru.students.ajax-list'));
            const url = new URL(baseUrl, window.location.origin);
            if (params instanceof URLSearchParams) {
                params.forEach((value, key) => url.searchParams.set(key, value));
            }
            return fetch(url.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                },
            }).then(response => response.text()).then(html => {
                const tab = document.getElementById('students-tab');
                if (tab) {
                    tab.innerHTML = html;
                    attachStudentListeners();
                }
            });
        }

        function filterSubjectsByClass(className, button) {
            // normalize helper: trim, uppercase, replace spaces with '-', remove non A-Z0-9- chars
            const norm = (s) => {
                if (!s) return '';
                try { s = String(s); } catch (e) { return ''; }
                s = s.trim().toUpperCase();
                s = s.replace(/\s+/g, '-');
                s = s.replace(/[^A-Z0-9\-]/g, '');
                return s;
            };

            const target = norm(className);

            document.querySelectorAll('.subject-card').forEach(card => {
                const raw = card.dataset.subjectClasses ? card.dataset.subjectClasses.split(' ') : [];
                const classes = raw.map(c => norm(c)).filter(x => x !== '');
                const visible = !target || classes.includes(target);
                card.style.display = visible ? '' : 'none';
            });

            document.querySelectorAll('.class-chip:not(.class-chip-disabled)').forEach(btn => {
                const rawBtn = btn.dataset.filterClass ? btn.dataset.filterClass : (btn.textContent || '').trim();
                const btnClass = norm(rawBtn);
                btn.classList.toggle('selected', btnClass === target || (!target && (rawBtn === 'Semua kelas' || rawBtn === '')));
            });
            // Additionally filter modules and their questions by class
                // debug logging removed
                document.querySelectorAll('.module-box').forEach(module => {
                const moduleClass = (module.dataset.moduleClass || '').trim();
                const moduleClassNorm = norm(moduleClass);
                const questionRows = module.querySelectorAll('.question-card-row');
                let visibleCount = 0;

                // If module has NO class, it's for all classes - always show it
                if (!moduleClassNorm) {
                    // Module is for all classes, always visible
                } else if (target && moduleClassNorm !== target) {
                    // Module has a specific class but it doesn't match selected class - hide it
                    module.style.display = 'none';
                    return;
                }

                questionRows.forEach(row => {
                    const qClass = (row.dataset.questionClass || '').trim();
                    const qClassNorm = norm(qClass);
                    const show = !target || qClassNorm === '' || qClassNorm === target;
                    row.style.display = show ? '' : 'none';
                    if (show) visibleCount++;
                });

                // If module has no class, always show it
                if (!moduleClassNorm) {
                    module.style.display = '';
                } else if (moduleClassNorm && target && moduleClassNorm === target) {
                    // If module is explicitly targeted to the selected class, show it even if it has 0 questions
                    module.style.display = '';
                } else {
                    // For targeted modules that don't match, they're already hidden above
                    if (visibleCount === 0) {
                        module.style.display = 'none';
                    } else {
                        module.style.display = '';
                    }
                }

                const countEl = module.querySelector('.module-question-count');
                if (countEl) countEl.textContent = String(visibleCount);
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.class-chip:not(.class-chip-disabled)').forEach(btn => {
                btn.addEventListener('click', function(event) {
                    event.stopPropagation();
                    let cls = this.dataset.filterClass ? this.dataset.filterClass : (this.textContent || '').trim();
                    if (cls === 'Semua kelas') cls = '';
                    filterSubjectsByClass(cls, this);
                });
            });

            const subjSel = document.getElementById('filter-subject');
            const modSel = document.getElementById('filter-module');
            if (subjSel) {
                subjSel.addEventListener('change', function() {
                    populateModuleOptions(this.value);
                    applyQuestionFilter();
                });
            }
            if (modSel) {
                modSel.addEventListener('change', applyQuestionFilter);
            }
            // initialize modules select (only if element exists)
            if (document.getElementById('filter-module')) populateModuleOptions('');
            attachStudentListeners();

            // make question rows collapsible: toggle .expanded when header clicked
            document.querySelectorAll('.question-row-header').forEach(h => {
                h.addEventListener('click', function (ev) {
                    // if click on an action control, ignore
                    if (ev.target.closest('.question-action')) return;
                    const row = this.closest('.question-card-row');
                    if (!row) return;
                    row.classList.toggle('expanded');
                });
            });

            // prevent action controls from toggling the row
            document.querySelectorAll('.question-action').forEach(el => el.addEventListener('click', function(ev){ ev.stopPropagation(); }));

            // Open task question list when clicking task button
            document.querySelectorAll('.btn-open-task').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.dataset.taskId;
                    const wrapper = document.getElementById('task-questions-' + id);
                    if (!wrapper) return;
                    wrapper.style.display = wrapper.style.display === 'none' ? '' : 'none';
                });
            });



            // Restore active tab if provided from server (e.g., after selecting class)
            @if (!empty($activeTab))
                try {
                    switchTab('{{ $activeTab }}');
                } catch (e) {}
            @elseif (!empty($selectedClass))
                try {
                    switchTab('students');
                } catch (e) {}
            @endif
        });

        // Manage Kelas Functions
        // Close modal on overlay click
        document.addEventListener('DOMContentLoaded', function() {
            const classModal = document.getElementById('classManageModal');
            if (classModal) {
                classModal.addEventListener('click', function(event) {
                    if (event.target === classModal) {
                        closeClassManageModal();
                    }
                });
            }
        });

        // Manage Class Functions
        function openAddAcademicYearModal() {
            const modal = document.getElementById('classManageModal');
            if (!modal) return;
            
            document.getElementById('classModalTitle').textContent = 'Tambah Tahun Ajaran Baru';
            document.getElementById('classNameInput').value = '';
            document.getElementById('classNameInput').setAttribute('data-academic-year-id', '');
            document.getElementById('submitClassBtn').setAttribute('data-action', 'add');
            
            modal.classList.add('active');
        }

        function openEditAcademicYearModal(academicYearId, academicYearName) {
            const modal = document.getElementById('classManageModal');
            if (!modal) return;
            
            document.getElementById('classModalTitle').textContent = 'Edit Tahun Ajaran';
            document.getElementById('classNameInput').value = academicYearName;
            document.getElementById('classNameInput').setAttribute('data-academic-year-id', academicYearId);
            document.getElementById('submitClassBtn').setAttribute('data-action', 'edit');
            
            modal.classList.add('active');
        }

        function openManageStudentsModal(academicYearId, academicYearName) {
            const modal = document.getElementById('manageStudentsModal');
            if (!modal) return;
            document.getElementById('manageStudentsYear').textContent = academicYearName;
            modal.classList.add('active');
            modal.setAttribute('data-academic-year-id', academicYearId);

            const body = document.getElementById('manageStudentsBody');
            if (body) {
                body.innerHTML = '<div style="padding:1rem; color:#666;">Memuat daftar siswa...</div>';
            }

            const url = new URL(@json(route('guru.students.ajax-list')), window.location.origin);
            url.searchParams.set('academic_year', academicYearName);
            url.searchParams.set('editable', '1');
            fetch(url.toString(), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            }).then(r => r.text()).then(html => {
                if (body) body.innerHTML = html;
            }).catch(err => {
                if (body) body.innerHTML = '<div style="color:#b91c1c">Gagal memuat daftar siswa.</div>';
            });
        }

        function closeManageStudentsModal() {
            const modal = document.getElementById('manageStudentsModal');
            if (modal) {
                modal.classList.remove('active');
                const body = document.getElementById('manageStudentsBody');
                if (body) body.innerHTML = '';
            }
        }

        async function saveManageStudents() {
            const modal = document.getElementById('manageStudentsModal');
            if (!modal) return;
            const academicYearId = modal.getAttribute('data-academic-year-id');
            const body = document.getElementById('manageStudentsBody');
            const rows = body ? body.querySelectorAll('table tbody tr') : document.querySelectorAll('#students-tab table tbody tr');
            const students = [];
            rows.forEach(row => {
                const select = row.querySelector('.student-class-select');
                if (!select) return;
                const userId = select.getAttribute('data-user-id');
                const newClass = select.value || null;
                const homeroomInput = row.querySelector('.student-homeroom-input');
                const homeroomTeacher = homeroomInput ? homeroomInput.value.trim() || null : null;
                students.push({ id: parseInt(userId, 10), class: newClass, homeroom_teacher: homeroomTeacher });
            });

            if (students.length === 0) {
                alert('Tidak ada perubahan yang ditemukan.');
                return;
            }

            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || document.querySelector('input[name="_token"]')?.value || '';
            const saveBtn = document.getElementById('saveManageStudentsBtn');
            if (saveBtn) saveBtn.disabled = true;

            const url = '/guru/classes/' + encodeURIComponent(academicYearId) + '/assign-students';
            const resp = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token || '',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ students })
            });

            if (!resp.ok) {
                const data = await resp.json().catch(() => null);
                alert(data?.message || 'Gagal menyimpan perubahan.');
                if (saveBtn) saveBtn.disabled = false;
                return;
            }

            const data = await resp.json();
            showStudentsMessage(data.message || 'Perubahan tersimpan.', 'success');
            closeManageStudentsModal();
            if (saveBtn) saveBtn.disabled = false;
            setTimeout(() => location.reload(), 1200);
        }

        function closeClassManageModal() {
            const modal = document.getElementById('classManageModal');
            if (modal) {
                modal.classList.remove('active');
                document.getElementById('classNameInput').value = '';
            }
        }

        function submitClassManage() {
            const classNameInput = document.getElementById('classNameInput');
            const newClassName = classNameInput.value.trim();
            const action = document.getElementById('submitClassBtn').getAttribute('data-action') || 'add';
            const academicYearId = classNameInput.getAttribute('data-academic-year-id') || '';
            const submitBtn = document.getElementById('submitClassBtn');

            if (!newClassName) {
                alert('Silakan masukkan nama tahun ajaran.');
                return;
            }

            submitBtn.disabled = true;
            const spinner = submitBtn.querySelector('.modal-spinner');
            if (spinner) spinner.style.display = 'block';

            const token = document.querySelector('input[name="_token"]')?.value;
            let url;
            if (action === 'edit') {
                url = '/guru/classes/' + encodeURIComponent(academicYearId);
            } else {
                url = '{{ route("guru.classes.store") }}';
            }

            const method = action === 'edit' ? 'PUT' : 'POST';
            const body = action === 'edit' 
                ? JSON.stringify({ name: newClassName })
                : JSON.stringify({ name: newClassName });

            fetch(url, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': token || '',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: body,
            })
            .then(async response => {
                if (!response.ok) {
                    const data = await response.json().catch(() => null);
                    throw new Error(data?.message || `Gagal ${action === 'edit' ? 'mengupdate' : 'menambah'} tahun ajaran.`);
                }
                return response.json();
            })
            .then(data => {
                showStudentsMessage(data.message || `Tahun ajaran berhasil ${action === 'edit' ? 'diupdate' : 'ditambah'}.`, 'success');
                closeClassManageModal();
                setTimeout(() => {
                    location.reload();
                }, 1500);
            })
            .catch(error => {
                showStudentsMessage(error.message, 'error');
            })
            .finally(() => {
                submitBtn.disabled = false;
                if (spinner) spinner.style.display = 'none';
            });
        }

        function deleteAcademicYear(academicYearId, academicYearName) {
            if (!confirm(`Hapus tahun ajaran "${academicYearName}"? Ini akan mengosongkan tahun ajaran semua siswa pada tahun ajaran ini.`)) {
                return;
            }

            const token = document.querySelector('input[name="_token"]')?.value;
            const url = '/guru/classes/' + encodeURIComponent(academicYearId);

            fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': token || '',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
            })
            .then(async response => {
                if (!response.ok) {
                    const data = await response.json().catch(() => null);
                    throw new Error(data?.message || 'Gagal menghapus tahun ajaran.');
                }
                return response.json();
            })
            .then(data => {
                showStudentsMessage(data.message || 'Tahun ajaran berhasil dihapus.', 'success');
                setTimeout(() => {
                    location.reload();
                }, 1500);
            })
            .catch(error => {
                showStudentsMessage(error.message, 'error');
            });
        }
    </script>
@endsection
