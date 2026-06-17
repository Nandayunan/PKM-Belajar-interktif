@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@section('extra-css')
    <style>
        .dashboard-header {
            background: #eef2ff;
            color: #1f2937;
            padding: 3rem 2rem;
            border-radius: 20px;
            margin-bottom: 3rem;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
        }

        .dashboard-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .dashboard-header p {
            font-size: 1.05rem;
            color: #4b5563;
        }

        /* Notification bell */
        .notif-bell {
            position: absolute;
            right: 2rem;
            top: 1.6rem;
            background: white;
            color: var(--primary-color);
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.12);
            cursor: pointer;
            z-index: 70;
        }

        .notif-count {
            position: absolute;
            top: -6px;
            right: -6px;
            background: var(--danger-color);
            color: white;
            min-width: 18px;
            height: 18px;
            border-radius: 999px;
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 4px;
            font-weight: 700;
        }

        .notif-dropdown {
            position: absolute;
            right: 2rem;
            top: 5.2rem;
            width: 420px;
            max-width: calc(100vw - 4rem);
            background: white;
            border-radius: 16px;
            box-shadow: 0 12px 40px rgba(15, 23, 42, 0.12);
            display: none;
            z-index: 1000;
            overflow: hidden;
            max-height: 60vh;
            overflow-y: auto;
        }

        .notif-item {
            padding: 0.95rem;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
        }

        .notif-item:last-child {
            border-bottom: none;
        }

        .profile-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
            margin-bottom: 3rem;
            display: flex;
            align-items: center;
            gap: 2rem;
            border: 1px solid #e2e8f0;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: white;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.12);
            flex-shrink: 0;
        }

        .profile-info {
            flex: 1;
        }

        .profile-info h3 {
            color: #111827;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .profile-info p {
            color: #475569;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .section-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 1.6rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-top: 1rem;
        }

        .section-title i {
            color: #4338ca;
            font-size: 2rem;
        }

        .subject-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }

        .subject-card {
            background: #ffffff;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
            transition: transform 0.24s ease, box-shadow 0.24s ease, border-color 0.24s ease;
            cursor: default;
            border: 1px solid #e2e8f0;
        }

        .subject-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.12);
            border-color: #4338ca;
        }

        .subject-header {
            padding: 2rem 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .subject-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            opacity: 0.08;
            z-index: 0;
        }

        .subject-header>* {
            position: relative;
            z-index: 1;
        }

        .subject-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .subject-name {
            font-size: 1.35rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 0.5rem;
        }

        .subject-description {
            color: #475569;
            font-size: 0.95rem;
            margin-bottom: 0.75rem;
            line-height: 1.5;
        }

        .subject-badge {
            display: inline-block;
            padding: 0.4rem 0.9rem;
            background: rgba(67, 56, 202, 0.1);
            color: #4338ca;
            border-radius: 999px;
            font-size: 0.82rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .subject-body {
            padding: 1.5rem;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
        }

        .progress-item {
            margin-bottom: 1rem;
        }

        .progress-item:last-child {
            margin-bottom: 0;
        }

        .progress-label {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.6rem;
            font-size: 0.9rem;
            font-weight: 600;
            color: #111827;
        }

        .progress-bar {
            height: 8px;
            background: #e2e8f0;
            border-radius: 999px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #4338ca, #6366f1);
            border-radius: 999px;
            transition: width 0.3s;
        }

        .subject-footer {
            padding: 1rem 1.5rem;
            background: #ffffff;
            border-top: 1px solid #e2e8f0;
        }

        .btn-modules {
            width: 100%;
            background: #4338ca;
            color: white;
            border: none;
            padding: 0.85rem 1rem;
            border-radius: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-modules:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 24px rgba(67, 56, 202, 0.24);
            text-decoration: none;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: #f8fafc;
            border-radius: 18px;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
        }

        .empty-state i {
            font-size: 4rem;
            color: #94a3b8;
            margin-bottom: 1rem;
        }

        .empty-state h3 {
            color: #334155;
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: #64748b;
        }

        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            text-align: center;
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

        @media (max-width: 768px) {
            .dashboard-header {
                padding: 2rem 1rem;
            }

            .dashboard-header h1 {
                font-size: 1.8rem;
            }

            .profile-card {
                flex-direction: column;
                text-align: center;
            }

            .subject-grid {
                grid-template-columns: 1fr;
            }

            .stats-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('content')
    <div style="padding: 0 1.5rem;">
        <div class="dashboard-header">
            <h1><i class="fas fa-star"></i> Selamat Datang, {{ auth()->user()->name }}! 🎉</h1>
            <p>Mari kita mulai petualangan belajar hari ini</p>

            <div class="notif-bell" id="notifBell" title="Notifikasi Penilaian Guru">
            <i class="fas fa-bell"></i>
            @if (!empty($gradedAnswers) && $gradedAnswers->count() > 0)
                <div class="notif-count">{{ $gradedAnswers->count() }}</div>
            @endif
        </div>

        <div class="notif-dropdown" id="notifDropdown">
            @if (empty($gradedAnswers) || $gradedAnswers->isEmpty())
                <div style="padding:1rem; color:#666;">Belum ada penilaian terbaru dari guru.</div>
            @else
                @foreach ($gradedAnswers as $ans)
                    <div class="notif-item">
                        <div style="max-width:72%;">
                            <div style="font-weight:700; font-size:0.95rem;">{{ Str::limit($ans->question->question, 80) }}
                            </div>
                            <div style="color:#666; font-size:0.85rem;">Nilai:
                                <strong>{{ $ans->teacher_score }}/{{ $ans->question->points }}</strong></div>
                            @if (!empty($ans->teacher_feedback))
                                <div style="color:#555; font-size:0.85rem;">Feedback:
                                    {{ Str::limit($ans->teacher_feedback, 120) }}</div>
                            @endif
                        </div>
                        <div
                            style="text-align:right; display:flex; flex-direction:column; gap:0.5rem; align-items:flex-end;">
                            @if ($ans->question->module)
                                <a href="{{ route('siswa.modules.review', [$ans->question->module->subject_id, $ans->question->module->id]) }}"
                                    class="btn-modules" style="padding:0.35rem 0.6rem;">Lihat Review</a>
                            @else
                                <a href="#" class="btn-modules" style="padding:0.35rem 0.6rem;">Lihat Review</a>
                            @endif
                            <div style="color:#999; font-size:0.75rem;">{{ $ans->graded_at?->diffForHumans() }}</div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    @if (session('review_link'))
        <div style="max-width:900px; margin: 0 auto 1.5rem;">
            <div class="result-toast success" style="display:flex; align-items:center; justify-content:space-between;">
                <div>
                    <div class="result-toast-icon"><i class="fas fa-check-circle" style="color:var(--success-color);"></i>
                    </div>
                    <div class="result-toast-message">Jawaban dan feedback Anda telah disimpan.</div>
                    <div class="result-toast-details">Klik tombol di samping untuk meninjau jawaban Anda pada modul yang
                        baru
                        saja diselesaikan.</div>
                </div>
                <div style="margin-left:1rem;">
                    <a href="{{ session('review_link') }}" class="btn-modules"
                        style="display:inline-flex; padding:0.6rem 1rem; align-items:center;"> <i
                            class="fas fa-search"></i>&nbsp; Review Jawaban</a>
                </div>
            </div>
        </div>
    @endif

    {{-- Graded answers moved to notification bell in header --}}

    @if (!empty($teacherNotes) && $teacherNotes->isNotEmpty())
        <div style="max-width:900px; margin: 1.25rem auto;">
            <div class="section-title"><i class="fas fa-sticky-note"></i> Catatan Guru</div>
            <div style="background:white; padding:1rem; border-radius:12px; box-shadow:var(--card-shadow);">
                @foreach ($teacherNotes as $note)
                    <div style="padding:0.75rem; border-bottom:1px solid #f1f5f9;">
                        <div style="font-weight:700;">Dari: {{ $note->teacher->name }} <span
                                style="color:#666; font-weight:600; font-size:0.9rem;">·
                                {{ $note->created_at->diffForHumans() }}</span></div>
                        <div style="color:#374151; margin-top:0.4rem;">{{ $note->note }}</div>
                        @if ($note->module)
                            <div style="color:#6b7280; margin-top:0.35rem; font-size:0.9rem;">Modul:
                                {{ $note->module->name }}</div>
                        @elseif($note->subject)
                            <div style="color:#6b7280; margin-top:0.35rem; font-size:0.9rem;">Mata Pelajaran:
                                {{ $note->subject->name }}</div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Profile Card -->
    <div class="profile-card">
        <div class="profile-avatar">
            <i class="fas fa-user-graduate"></i>
        </div>
        <div class="profile-info">
            <h3>{{ auth()->user()->name }}</h3>
            <p><i class="fas fa-envelope"></i> {{ auth()->user()->email }}</p>
            <p><i class="fas fa-user-graduate"></i> Kelas: {{ auth()->user()->class ?? 'Belum ditentukan' }}</p>
            <p><i class="fas fa-school"></i> Tahun Ajaran: {{ auth()->user()->academic_year ?? 'Belum ditentukan' }}</p>
            <p><i class="fas fa-badge"></i> Siswa (Pelajar)</p>
            <p><i class="fas fa-calendar"></i> Bergabung sejak {{ auth()->user()->created_at->format('d M Y') }}</p>
        </div>
    </div>

    <!-- Statistics -->
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-number">{{ $totalSubjects }}</div>
            <div class="stat-label">Mata Pelajaran</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $totalModules }}</div>
            <div class="stat-label">Modul Pembelajaran</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $completedSubjects }}</div>
            <div class="stat-label">Selesai</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ round($averageProgress) }}%</div>
            <div class="stat-label">Rata-rata Progress</div>
        </div>
    </div>

    <!-- Subjects Section -->
    <div class="section-title" style="display:flex; align-items:center; justify-content:space-between;">
        <div><i class="fas fa-book"></i> Mata Pelajaran Kelas Anda</div>
    </div>

    @if ($subjects->isEmpty())
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <h3>Belum ada mata pelajaran untuk kelas Anda</h3>
            <p>Tunggu guru menambahkan mata pelajaran atau periksa kembali data kelas Anda.</p>
        </div>
    @else
        <div class="subject-grid">
            @foreach ($subjects as $subject)
                @php
                    // Prefer subject-level progress (module_id is null) so we show aggregated percentage per subject
                    $progress = $subject
                        ->progress()
                        ->where('user_id', auth()->id())
                        ->whereNull('module_id')
                        ->first();
                    $progressPercentage = $progress?->percentage ?? 0;
                    $moduleCount = $subject->publishedModules()->count();
                    $isEnrolled = in_array($subject->id, $enrolledSubjectIds ?? []);
                @endphp

                <div class="subject-card" style="cursor: default;">
                    <div class="subject-header"
                        style="background: linear-gradient(135deg, {{ $subject->color }}, {{ adjustColor($subject->color, 20) }});">
                        <div class="subject-icon">{{ $subject->icon }}</div>
                        <div class="subject-name" style="color: white;">{{ $subject->name }}</div>
                        <div class="subject-description" style="color: rgba(255,255,255,0.9);">{{ $subject->description }}
                        </div>
                        <span class="subject-badge" style="background: rgba(255,255,255,0.2); color: white;">
                            {{ $moduleCount }} Modul
                        </span>
                    </div>
                    <div class="subject-body">
                        <div class="progress-item">
                            <div class="progress-label">
                                <span>Progress Pembelajaran</span>
                                <span
                                    style="color: var(--primary-color); font-weight: 700;">{{ round($progressPercentage) }}%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: {{ $progressPercentage }}%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="subject-footer">
                        @if ($isEnrolled)
                            <a href="{{ route('siswa.modules.index', $subject->id) }}" class="btn-modules">
                                <i class="fas fa-arrow-right"></i> Lihat Modul
                            </a>
                        @else
                            <form method="POST" action="{{ route('siswa.subjects.enroll', $subject->id) }}">
                                @csrf
                                <button type="submit" class="btn-modules" style="width:100%;">
                                    <i class="fas fa-sign-in-alt"></i> Daftar Mata Pelajaran
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
    </div>
@endsection

@section('extra-js')
    <script>
        // Fungsi untuk menyesuaikan warna (lighten/darken)
        function adjustColor(color, percent) {
            var usePound = false;
            if (color[0] == "#") {
                color = color.slice(1);
                usePound = true;
            }
            var num = parseInt(color, 16);
            var amt = Math.round(2.55 * percent);
            var R = (num >> 16) + amt;
            var G = (num >> 8 & 0x00FF) + amt;
            var B = (num & 0x0000FF) + amt;
            return (usePound ? "#" : "") + (0x1000000 + (R < 255 ? R < 1 ? 0 : R : 255) * 0x10000 +
                    (G < 255 ? G < 1 ? 0 : G : 255) * 0x100 + (B < 255 ? B < 1 ? 0 : B : 255))
                .toString(16).slice(1);
        }
    </script>
    <script>
        // Notification dropdown toggle
        (function() {
            const bell = document.getElementById('notifBell');
            const dd = document.getElementById('notifDropdown');
            if (!bell || !dd) return;

            bell.addEventListener('click', function(e) {
                e.stopPropagation();
                dd.style.display = dd.style.display === 'block' ? 'none' : 'block';
            });

            document.addEventListener('click', function() {
                dd.style.display = 'none';
            });
        })();
    </script>
@endsection
