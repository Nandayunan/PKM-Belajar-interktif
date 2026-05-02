@extends('layouts.app')

@section('title', 'Review Modul - ' . $module->name)

@section('extra-css')
    <style>
        .review-hero {
            padding: 1rem 1.5rem;
            border-radius: 12px;
            background: linear-gradient(135deg, {{ $subject->color }}, {{ adjustColor($subject->color, 18) }});
            color: white;
            margin-bottom: 1.25rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        .review-summary {
            background: white;
            border-radius: 12px;
            padding: 1rem;
            display: flex;
            gap: 1rem;
            align-items: center;
            justify-content: space-between;
            box-shadow: var(--card-shadow);
            margin-bottom: 1rem;
        }

        .review-score {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .review-list .question-card {
            background: white;
            border-radius: 12px;
            padding: 1rem;
            box-shadow: var(--card-shadow);
            margin-bottom: 1rem;
        }

        .qa-label {
            font-weight: 700;
            margin-bottom: 0.35rem;
        }

        .correct-badge {
            background: rgba(16, 185, 129, 0.12);
            color: #059669;
            padding: 6px 10px;
            border-radius: 999px;
            font-weight: 700;
            display: inline-flex;
            gap: 0.5rem;
            align-items: center;
        }

        .wrong-badge {
            background: rgba(239, 68, 68, 0.08);
            color: #ef4444;
            padding: 6px 10px;
            border-radius: 999px;
            font-weight: 700;
            display: inline-flex;
            gap: 0.5rem;
            align-items: center;
        }

        .pending-badge {
            background: rgba(250, 204, 21, 0.12);
            color: #b45309;
            padding: 6px 10px;
            border-radius: 999px;
            font-weight: 700;
            display: inline-flex;
            gap: 0.5rem;
            align-items: center;
        }

        .answer-box {
            background: #f8fafc;
            border-radius: 8px;
            padding: 0.7rem;
            border: 1px solid #eef2ff;
        }

        .correct-answer {
            background: #ecfdf5;
            border: 1px solid #bbf7d0;
        }

        .question-meta {
            display: flex;
            gap: 1rem;
            align-items: center;
            color: #6b7280;
            font-size: 0.95rem;
        }

        @media (max-width:640px) {
            .review-summary {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
        }

        /* Modern buttons and breadcrumb pills */
        .btn-modern {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem 0.9rem;
            border-radius: 10px;
            font-weight: 700;
            text-decoration: none;
        }

        .btn-modern svg,
        .btn-modern i {
            line-height: 0;
        }

        .btn-modern-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            box-shadow: 0 8px 20px rgba(99, 102, 241, 0.18);
        }

        .btn-modern-ghost {
            background: transparent;
            border: 1px solid rgba(99, 102, 241, 0.12);
            color: var(--primary-color);
        }

        .breadcrumb-pills {
            display: flex;
            gap: 0.5rem;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .breadcrumb-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.4rem 0.65rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.08);
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            font-weight: 600;
        }

        .breadcrumb-pill.current {
            background: rgba(255, 255, 255, 0.16);
        }
    </style>
@endsection

@section('content')
    <div class="breadcrumb-pills">
        <a class="breadcrumb-pill" href="{{ route('siswa.dashboard') }}"><i class="fas fa-home"></i> Dashboard</a>
        <a class="breadcrumb-pill" href="{{ route('siswa.modules.index', $subject->id) }}"><i class="fas fa-book"></i>
            {{ $subject->name }}</a>
        <span class="breadcrumb-pill current"><i class="fas fa-clipboard-check"></i> Review Modul
            {{ $module->module_number }}</span>
    </div>

    <div class="review-hero">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:1rem; flex-wrap:wrap;">
            <div>
                <h2 style="margin:0; font-size:1.4rem;">Review: {{ $module->name }}</h2>
                <div style="opacity:0.9; margin-top:0.25rem;">Tinjau jawaban Anda. Beberapa detail ditampilkan sesuai
                    pengaturan guru.</div>
            </div>
            <div class="question-meta">
                <div><i class="fas fa-layer-group"></i> Modul {{ $module->module_number }}</div>
                <div><i class="fas fa-book"></i> {{ $questions->count() }} Soal</div>
            </div>
        </div>
    </div>

    <div class="review-summary">
        @php
            $earned = $answers->sum('points_earned');
            $possible = $questions->sum('points');
            $percent = $possible > 0 ? round(($earned / $possible) * 100) : 0;
        @endphp
        <div>
            <div style="color:#6b7280; font-weight:600;">Ringkasan Hasil</div>
            <div class="review-score">{{ $earned }} / {{ $possible }} poin</div>
            <div style="color:#6b7280;">{{ $percent }}% tercapai</div>
        </div>

        <div style="display:flex; gap:0.5rem;">
            <a href="{{ route('siswa.modules.index', $subject->id) }}" class="btn-modern btn-modern-ghost"><i
                    class="fas fa-list"></i> Daftar Modul</a>
            <a href="{{ route('siswa.modules.show', [$subject->id, $module->id]) }}"
                class="btn-modern btn-modern-primary"><i class="fas fa-arrow-left"></i> Kembali ke Modul</a>
        </div>
    </div>

    <div class="review-list">
        @foreach ($questions as $index => $question)
            @php
                $answer = $answers->get($question->id);
                $correctAnswer = $question->correct_answer;
                $isCorrect = $answer?->is_correct;
                $showCorrect = $teacherSettings?->show_correct_answers ?? true;
            @endphp

            <div class="question-card">
                <div style="display:flex; justify-content:space-between; align-items:start; gap:1rem;">
                    <div>
                        <div style="font-weight:700;">Soal {{ $index + 1 }}.</div>
                        <div style="margin-top:0.5rem; color:#374151;">{!! nl2br(e($question->question)) !!}</div>
                    </div>

                    <div style="text-align:right;">
                        @php
                            $isPendingEssay =
                                $question->type === 'essay' &&
                                $answer &&
                                is_null($answer->teacher_score) &&
                                is_null($answer->graded_at);
                        @endphp

                        @if ($isPendingEssay)
                            <div class="pending-badge"><i class="fas fa-hourglass-half"></i> Menunggu guru menilai</div>
                        @elseif (!is_null($isCorrect))
                            @if ($isCorrect)
                                <div class="correct-badge"><i class="fas fa-check"></i> Benar</div>
                            @else
                                <div class="wrong-badge"><i class="fas fa-times"></i> Salah</div>
                            @endif
                        @else
                            <div style="color:#9CA3AF;">Belum dinilai</div>
                        @endif
                        <div style="margin-top:0.5rem; color:#6b7280; font-size:0.9rem;">+{{ $question->points }} poin
                        </div>
                    </div>
                </div>

                <div style="margin-top:0.9rem;">
                    <div class="qa-label">Jawaban Anda</div>
                    <div class="answer-box">{{ $answer?->answer ?? '-' }}</div>
                </div>

                @if ($showCorrect)
                    <div style="margin-top:0.8rem;">
                        <div class="qa-label">Jawaban Benar</div>
                        <div class="answer-box correct-answer">{{ $correctAnswer ?? '-' }}</div>
                    </div>
                @endif

                @if ($question->type === 'essay')
                    <div style="margin-top:0.6rem; color:#6b7280; font-size:0.9rem;">Soal essay perlu dinilai guru untuk
                        mendapatkan nilai.</div>
                @endif
            </div>
        @endforeach
    </div>

@endsection
