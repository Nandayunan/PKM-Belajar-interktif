@extends('layouts.app')

@section('title', 'Penilaian Soal')

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

        .grading-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .stat-mini {
            background: rgba(255, 255, 255, 0.2);
            padding: 1rem;
            border-radius: 10px;
            text-align: center;
        }

        .stat-mini-number {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .stat-mini-label {
            font-size: 0.9rem;
            opacity: 0.9;
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
        }

        .tab-btn.active {
            border-color: var(--primary-color);
            background: var(--primary-color);
            color: white;
        }

        .answer-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border-left: 4px solid var(--info-color);
            transition: all 0.3s;
        }

        .answer-card:hover {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            transform: translateY(-2px);
        }

        .answer-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 1rem;
        }

        .answer-student {
            font-weight: 600;
            color: #2d3748;
        }

        .answer-meta {
            display: flex;
            gap: 1rem;
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 0.5rem;
        }

        .answer-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            background: #dbeafe;
            color: var(--info-color);
        }

        .answer-question {
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

        .question-type {
            font-size: 0.85rem;
            color: #666;
        }

        .answer-content {
            background: white;
            border: 1px solid #e5e7eb;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            min-height: 100px;
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

        .btn-grade {
            background: linear-gradient(135deg, var(--success-color), #059669);
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s;
        }

        .btn-grade:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
            color: white;
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
            .answer-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
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
            <h1><i class="fas fa-check-circle"></i> Penilaian Soal Essay</h1>
            <p style="margin: 0; opacity: 0.9;">Kelola dan nilai jawaban essay serta soal gabungan dari siswa</p>
            <div class="grading-stats">
                <div class="stat-mini">
                    <div class="stat-mini-number">{{ $pendingAnswers->total() }}</div>
                    <div class="stat-mini-label">Pending</div>
                </div>
                <div class="stat-mini">
                    <div class="stat-mini-number">
                        {{ \App\Models\QuestionAnswer::whereHas('question', function ($q) {$q->where('created_by', auth()->user()->id)->whereIn('type', ['essay', 'mixed']);})->whereNotNull('teacher_score')->count() }}
                    </div>
                    <div class="stat-mini-label">Sudah Dinilai</div>
                </div>
            </div>
        </div>

        <!-- Tab Buttons -->
        <div class="tab-buttons">
            <a href="{{ route('guru.grading.index') }}" class="tab-btn active">
                <i class="fas fa-hourglass-half"></i> Belum Dinilai
            </a>
            <a href="{{ route('guru.grading.graded') }}" class="tab-btn">
                <i class="fas fa-check-double"></i> Sudah Dinilai
            </a>
        </div>

        <!-- Pending Answers -->
        @if ($pendingAnswers->isEmpty())
            <div class="empty-state">
                <i class="fas fa-check-circle"></i>
                <p>Tidak ada soal yang perlu dinilai</p>
                <p style="font-size: 0.9rem; color: #bbb;">Semua jawaban essay telah dinilai</p>
            </div>
        @else
            @foreach ($pendingAnswers as $answer)
                <div class="answer-card">
                    <div class="answer-header">
                        <div>
                            <div class="answer-student">{{ $answer->user->name }}</div>
                            <div class="answer-meta">
                                <span><i class="fas fa-envelope"></i> {{ $answer->user->email }}</span>
                                <span><i class="fas fa-calendar"></i> {{ $answer->created_at->format('d M Y H:i') }}</span>
                            </div>
                            <span class="answer-badge">{{ ucfirst(str_replace('_', ' ', $answer->question->type)) }}</span>
                        </div>
                        <a href="{{ route('guru.grading.show', $answer->id) }}" class="btn-grade">
                            <i class="fas fa-pen"></i> Nilai Sekarang
                        </a>
                    </div>

                    <div class="answer-question">
                        <div class="question-text">{{ Str::limit($answer->question->question, 80) }}</div>
                        <div class="question-type">
                            Modul: {{ $answer->question->module->name }} | Poin: {{ $answer->question->points }}
                        </div>
                    </div>

                    <div class="answer-content">
                        <strong style="color: #666; font-size: 0.9rem; display: block; margin-bottom: 0.5rem;">Jawaban
                            Siswa:</strong>
                        {{ Str::limit(strip_tags($answer->answer), 150) }}...
                    </div>
                </div>
            @endforeach

            <!-- Pagination -->
            @if ($pendingAnswers->hasPages())
                <div class="pagination">
                    {{ $pendingAnswers->links() }}
                </div>
            @endif
        @endif
    </div>
@endsection
