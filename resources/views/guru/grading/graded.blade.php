@extends('layouts.app')

@section('title', 'Soal Sudah Dinilai')

@section('extra-css')
    <style>
        .grading-container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .grading-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.3);
        }

        .grading-header h1 {
            margin: 0;
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .tab-buttons {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
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
            text-decoration: none;
        }

        .tab-btn.active {
            border-color: var(--primary-color);
            background: var(--primary-color);
            color: white;
        }

        .graded-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border-left: 4px solid var(--success-color);
            transition: all 0.3s;
        }

        .graded-card:hover {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            transform: translateY(-2px);
        }

        .graded-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 1rem;
        }

        .graded-student {
            font-weight: 600;
            color: #2d3748;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }

        .graded-meta {
            display: flex;
            gap: 1.5rem;
            font-size: 0.9rem;
            color: #666;
            flex-wrap: wrap;
        }

        .graded-score {
            text-align: right;
        }

        .score-badge {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background: linear-gradient(135deg, var(--success-color), #059669);
            color: white;
            border-radius: 8px;
            font-weight: 700;
            font-size: 1.2rem;
        }

        .score-label {
            color: #666;
            font-size: 0.85rem;
            display: block;
        }

        .question-preview {
            background: #f8f9ff;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            border-left: 3px solid var(--primary-color);
        }

        .question-text {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 0.5rem;
        }

        .question-details {
            font-size: 0.9rem;
            color: #666;
        }

        .feedback-box {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            padding: 1rem;
            border-radius: 8px;
            margin-top: 1rem;
        }

        .feedback-label {
            font-weight: 600;
            color: #166534;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .feedback-text {
            color: #2d6a4f;
            line-height: 1.6;
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            background: white;
            border-radius: 12px;
            color: #999;
        }

        .empty-state i {
            font-size: 3rem;
            color: #ccc;
            margin-bottom: 1rem;
            display: block;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 2rem;
            flex-wrap: wrap;
        }

        .pagination a,
        .pagination span {
            padding: 0.5rem 0.75rem;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            text-decoration: none;
            color: #666;
        }

        .pagination a:hover {
            background: #f3f4f6;
        }

        .pagination .active span {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        @media (max-width: 640px) {
            .graded-header {
                flex-direction: column;
                gap: 1rem;
            }

            .graded-score {
                text-align: left;
            }

            .graded-meta {
                flex-direction: column;
                gap: 0.5rem;
            }

            .tab-buttons {
                flex-direction: column;
            }

            .tab-btn {
                width: 100%;
            }
        }
    </style>
@endsection

@section('content')
    <div class="grading-container">
        <!-- Header -->
        <div class="grading-header">
            <h1><i class="fas fa-check-double"></i> Soal Yang Sudah Dinilai</h1>
            <p style="margin: 0; opacity: 0.9;">Lihat riwayat penilaian dan feedback untuk jawaban essay siswa</p>
        </div>

        <!-- Tab Buttons -->
        <div class="tab-buttons">
            <a href="{{ route('guru.grading.index') }}" class="tab-btn">
                <i class="fas fa-hourglass-half"></i> Belum Dinilai
            </a>
            <a href="{{ route('guru.grading.graded') }}" class="tab-btn active">
                <i class="fas fa-check-double"></i> Sudah Dinilai
            </a>
        </div>

        <!-- Graded Answers -->
        @if ($gradedAnswers->isEmpty())
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <p>Belum ada soal yang dinilai</p>
                <p style="font-size: 0.9rem; color: #bbb;">Mulai menilai jawaban essay dari siswa</p>
            </div>
        @else
            @foreach ($gradedAnswers as $answer)
                <div class="graded-card">
                    <div class="graded-header">
                        <div>
                            <div class="graded-student">{{ $answer->user->name }}</div>
                            <div class="graded-meta">
                                <span><i class="fas fa-envelope"></i> {{ $answer->user->email }}</span>
                                <span><i class="fas fa-calendar"></i> Dinilai:
                                    {{ $answer->graded_at->format('d M Y H:i') }}</span>
                                @if ($answer->question->type === 'mixed')
                                    <span>
                                        <i class="fas fa-{{ $answer->is_correct ? 'check-circle' : 'times-circle' }}"></i>
                                        {{ $answer->is_correct ? 'Benar' : 'Salah' }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="graded-score">
                            <div class="score-badge">{{ $answer->teacher_score }}/{{ $answer->question->points }}</div>
                            <span class="score-label">Skor</span>
                        </div>
                    </div>

                    <div class="question-preview">
                        <div class="question-text">{{ Str::limit($answer->question->question, 100) }}</div>
                        <div class="question-details">
                            Tipe: {{ ucfirst(str_replace('_', ' ', $answer->question->type)) }} | Modul:
                            {{ $answer->question->module->name }}
                        </div>
                    </div>

                    <div class="feedback-box">
                        <div class="feedback-label">
                            <i class="fas fa-comment-dots"></i> Feedback Guru
                        </div>
                        <div class="feedback-text">
                            {{ $answer->teacher_feedback }}
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Pagination -->
            @if ($gradedAnswers->hasPages())
                <div class="pagination">
                    {{ $gradedAnswers->links() }}
                </div>
            @endif
        @endif
    </div>
@endsection
