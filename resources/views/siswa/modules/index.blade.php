@extends('layouts.app')

@section('title', 'Modul - ' . $subject->name)

@section('extra-css')
    <style>
        .module-header {
            background: linear-gradient(135deg, {{ $subject->color }}, {{ adjustColor($subject->color, 20) }});
            color: white;
            padding: 2.5rem;
            border-radius: 20px;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .module-header-icon {
            font-size: 4rem;
        }

        .module-header-content h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .module-header-content p {
            opacity: 0.9;
            font-size: 1rem;
        }

        .breadcrumb-custom {
            background: transparent;
            padding: 0 0 1rem 0;
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 2rem;
        }

        .breadcrumb-custom a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
        }

        .breadcrumb-custom a:hover {
            text-decoration: underline;
        }

        .module-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .module-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            transition: all 0.3s;
            border-left: 5px solid transparent;
            display: flex;
            flex-direction: column;
        }

        .module-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 50px rgba(99, 102, 241, 0.2);
        }

        .module-card.locked {
            opacity: 0.7;
        }

        .module-number {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 1rem;
            font-size: 0.85rem;
            font-weight: 700;
            text-align: center;
        }

        .module-content {
            padding: 1.5rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .module-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 1rem;
        }

        .module-description {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 1rem;
            flex: 1;
        }

        .module-stats {
            display: flex;
            justify-content: space-between;
            padding: 1rem;
            background: #f8f9ff;
            border-top: 1px solid #e5e7eb;
            border-radius: 0;
            margin: 0 -1.5rem -1.5rem -1.5rem;
        }

        .module-stat {
            text-align: center;
            flex: 1;
        }

        .module-stat-number {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .module-stat-label {
            font-size: 0.8rem;
            color: #999;
            margin-top: 0.2rem;
        }

        .module-status {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 1rem;
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            width: fit-content;
        }

        .module-status.completed {
            background: #dcfce7;
            color: #166534;
        }

        .module-status.in-progress {
            background: #dbeafe;
            color: #0c2340;
        }

        .module-status.not-started {
            background: #f3f4f6;
            color: #6b7280;
        }

        .module-progress {
            margin: 1rem 0;
        }

        .progress-bar-custom {
            height: 6px;
            background: #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-fill-custom {
            height: 100%;
            background: linear-gradient(90deg, var(--primary-color), var(--primary-dark));
            border-radius: 10px;
            transition: width 0.3s;
        }

        .btn-module-action {
            width: 100%;
            padding: 0.75rem;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .btn-module-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(99, 102, 241, 0.3);
            color: white;
        }

        .btn-module-action.disabled {
            opacity: 0.6;
            cursor: not-allowed;
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

        @media (max-width: 768px) {
            .module-header {
                flex-direction: column;
                text-align: center;
            }

            .module-list {
                grid-template-columns: 1fr;
            }

            .module-stats {
                flex-direction: column;
            }

            .module-stat {
                padding: 0.5rem 0;
            }
        }
    </style>
@endsection

@section('content')
    <!-- Breadcrumb -->
    <div class="breadcrumb-custom">
        <a href="{{ route('siswa.dashboard') }}">
            <i class="fas fa-home"></i> Dashboard
        </a>
        <span style="color: #ccc;"> / </span>
        <span style="color: #666;">{{ $subject->name }}</span>
    </div>

    <!-- Header -->
    <div class="module-header">
        <div class="module-header-icon">{{ $subject->icon }}</div>
        <div class="module-header-content">
            <h1>{{ $subject->name }}</h1>
            <p>{{ $subject->description }}</p>
        </div>
    </div>

    <!-- Overall Progress -->
    @php
        $overallProgress = $subject
            ->progress()
            ->where('user_id', auth()->id())
            ->first();
        $overallPercentage = $overallProgress?->percentage ?? 0;
    @endphp

    <div
        style="background: white; padding: 1.5rem; border-radius: 15px; margin-bottom: 2rem; box-shadow: var(--card-shadow);">
        <h4 style="color: #2d3748; margin-bottom: 1rem; font-weight: 700;">Progress Keseluruhan</h4>
        <div style="display: flex; align-items: center; gap: 2rem;">
            <div style="flex: 1;">
                <div class="progress-bar-custom">
                    <div class="progress-fill-custom" style="width: {{ $overallPercentage }}%"></div>
                </div>
            </div>
            <div
                style="font-size: 1.5rem; font-weight: 700; color: var(--primary-color); min-width: 80px; text-align: right;">
                {{ round($overallPercentage) }}%
            </div>
        </div>
    </div>

    <!-- Modules List -->
    @if ($modules->isEmpty())
        <div class="empty-state">
            <i class="fas fa-folder-open"></i>
            <h3>Belum ada modul</h3>
            <p>Guru belum membuat modul untuk mata pelajaran ini</p>
        </div>
    @else
        <div class="module-list">
            @foreach ($modules as $module)
                @php
                    $moduleProgress = $module
                        ->progress()
                        ->where('user_id', auth()->id())
                        ->first();
                    $modulePercentage = $moduleProgress?->percentage ?? 0;
                    $statusClass = match ($moduleProgress?->status) {
                        'completed' => 'completed',
                        'in_progress' => 'in-progress',
                        default => 'not-started',
                    };
                    $statusLabel = match ($moduleProgress?->status) {
                        'completed' => '✓ Selesai',
                        'in_progress' => '◈ Sedang Dikerjakan',
                        default => '○ Belum Dimulai',
                    };
                    $isCompleted = $moduleProgress?->status === 'completed';
                @endphp

                <div class="module-card" style="border-left-color: {{ $subject->color }};">
                    <div class="module-number">Modul {{ $module->module_number }}</div>

                    <div class="module-content">
                        <h3 class="module-title">{{ $module->name }}</h3>

                        <p class="module-description">{{ Str::limit($module->content, 80) }}</p>

                        <div class="module-status {{ $statusClass }}">
                            <i class="fas fa-circle-check"></i> {{ $statusLabel }}
                        </div>

                        <div class="module-progress">
                            <div style="font-size: 0.85rem; color: #999; margin-bottom: 0.5rem;">
                                Progress: <span
                                    style="color: var(--primary-color); font-weight: 600;">{{ round($modulePercentage) }}%</span>
                            </div>
                            <div class="progress-bar-custom">
                                <div class="progress-fill-custom" style="width: {{ $modulePercentage }}%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="module-stats">
                        <div class="module-stat">
                            <div class="module-stat-number">
                                @if ($module->video_url)
                                    <i class="fas fa-video"></i>
                                @else
                                    -
                                @endif
                            </div>
                            <div class="module-stat-label">Video</div>
                        </div>
                        <div class="module-stat">
                            <div class="module-stat-number">{{ $module->questions()->count() }}</div>
                            <div class="module-stat-label">Soal</div>
                        </div>
                        <div class="module-stat">
                            <div class="module-stat-number">{{ $module->questions()->sum('points') }}</div>
                            <div class="module-stat-label">Poin</div>
                        </div>
                    </div>

                    <div style="padding: 1.5rem;">
                        <a href="{{ route('siswa.modules.show', [$subject->id, $module->id]) }}" class="btn-module-action">
                            <i class="fas fa-play"></i>
                            @if ($isCompleted)
                                Ulangi
                            @else
                                Mulai
                            @endif
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection

@section('extra-js')
    <script>
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
