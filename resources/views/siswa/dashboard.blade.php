@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@section('extra-css')
    <style>
        .dashboard-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 3rem 2rem;
            border-radius: 20px;
            margin-bottom: 3rem;
            box-shadow: 0 15px 40px rgba(99, 102, 241, 0.3);
        }

        .dashboard-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .dashboard-header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .profile-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--card-shadow);
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .profile-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: white;
            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.3);
        }

        .profile-info h3 {
            color: var(--primary-color);
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .profile-info p {
            color: #666;
            margin-bottom: 0.3rem;
        }

        .section-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .section-title i {
            color: var(--primary-color);
            font-size: 2rem;
        }

        .subject-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .subject-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            transition: all 0.3s;
            cursor: pointer;
            border: 2px solid transparent;
        }

        .subject-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 50px rgba(99, 102, 241, 0.2);
            border-color: var(--primary-color);
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
            opacity: 0.1;
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
            font-size: 1.3rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 0.5rem;
        }

        .subject-description {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .subject-badge {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary-color);
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .subject-body {
            padding: 1.5rem;
            background: #f8f9ff;
            border-top: 1px solid #e5e7eb;
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
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            font-weight: 600;
            color: #2d3748;
        }

        .progress-bar {
            height: 8px;
            background: #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary-color), var(--primary-dark));
            border-radius: 10px;
            transition: width 0.3s;
        }

        .subject-footer {
            padding: 1rem 1.5rem;
            background: white;
            border-top: 1px solid #e5e7eb;
        }

        .btn-modules {
            width: 100%;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            border: none;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-modules:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(99, 102, 241, 0.3);
            color: white;
            text-decoration: none;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 15px;
            box-shadow: var(--card-shadow);
        }

        .empty-state i {
            font-size: 4rem;
            color: #ccc;
            margin-bottom: 1rem;
        }

        .empty-state h3 {
            color: #999;
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: #bbb;
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
    <div class="dashboard-header">
        <h1><i class="fas fa-star"></i> Selamat Datang, {{ auth()->user()->name }}! 🎉</h1>
        <p>Mari kita mulai petualangan belajar hari ini</p>
    </div>

    <!-- Profile Card -->
    <div class="profile-card">
        <div class="profile-avatar">
            <i class="fas fa-user-graduate"></i>
        </div>
        <div class="profile-info">
            <h3>{{ auth()->user()->name }}</h3>
            <p><i class="fas fa-envelope"></i> {{ auth()->user()->email }}</p>
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
    <div class="section-title">
        <i class="fas fa-book"></i> Pilih Mata Pelajaran
    </div>

    @if ($subjects->isEmpty())
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <h3>Belum ada mata pelajaran</h3>
            <p>Tunggu guru membuat mata pelajaran</p>
        </div>
    @else
        <div class="subject-grid">
            @foreach ($subjects as $subject)
                @php
                    $progress = $subject
                        ->progress()
                        ->where('user_id', auth()->id())
                        ->first();
                    $progressPercentage = $progress?->percentage ?? 0;
                    $moduleCount = $subject->modules()->count();
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
                        <a href="{{ route('siswa.modules.index', $subject->id) }}" class="btn-modules">
                            <i class="fas fa-arrow-right"></i> Lihat Modul
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
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
@endsection
