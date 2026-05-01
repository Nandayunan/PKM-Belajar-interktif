@extends('layouts.app')

@section('title', 'Nilai Soal')

@section('extra-css')
    <style>
        .grading-form-container {
            max-width: 900px;
            margin: 0 auto;
        }

        .grading-form-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.3);
        }

        .grading-form-header h1 {
            margin: 0;
            margin-bottom: 1rem;
            font-size: 1.8rem;
        }

        .student-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
        }

        .info-item {
            border-left: 3px solid var(--primary-color);
            padding-left: 1rem;
        }

        .info-label {
            font-size: 0.85rem;
            color: #999;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .info-value {
            color: #2d3748;
            font-weight: 600;
        }

        .question-section,
        .answer-section,
        .grading-section {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .section-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .section-title i {
            color: var(--primary-color);
            font-size: 1.3rem;
        }

        .question-text {
            background: #f8f9ff;
            border-left: 3px solid var(--primary-color);
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .question-content {
            font-size: 1.05rem;
            line-height: 1.6;
            color: #2d3748;
            margin-bottom: 1rem;
        }

        .question-meta {
            display: flex;
            gap: 1.5rem;
            font-size: 0.9rem;
            color: #666;
        }

        .question-meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .answer-text {
            background: #fafbfc;
            border: 1px solid #e5e7eb;
            padding: 1rem;
            border-radius: 8px;
            line-height: 1.6;
            color: #2d3748;
            min-height: 120px;
            max-height: 300px;
            overflow-y: auto;
            margin-bottom: 1rem;
        }

        .grading-form {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 0.5rem;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.1);
            outline: none;
        }

        .score-input-group {
            display: flex;
            gap: 1rem;
            align-items: flex-end;
        }

        .score-input {
            flex: 1;
            max-width: 150px;
        }

        .score-info {
            color: #666;
            font-size: 0.9rem;
        }

        .btn-group {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn-submit {
            background: linear-gradient(135deg, var(--success-color), #059669);
            color: white;
            padding: 0.75rem 2rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            flex: 1;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
        }

        .btn-cancel {
            background: #f3f4f6;
            color: #666;
            padding: 0.75rem 2rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s;
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-cancel:hover {
            background: #e5e7eb;
        }

        .error-text {
            color: #dc2626;
            font-size: 0.85rem;
            margin-top: 0.25rem;
        }

        .type-badge {
            display: inline-block;
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 600;
            background: #dbeafe;
            color: var(--info-color);
        }

        @media (max-width: 640px) {
            .student-info {
                grid-template-columns: 1fr;
            }

            .score-input-group {
                flex-direction: column;
                align-items: stretch;
            }

            .score-input {
                max-width: none;
            }

            .btn-group {
                flex-direction: column;
            }

            .btn-submit,
            .btn-cancel {
                width: 100%;
            }
        }
    </style>
@endsection

@section('content')
    <div class="grading-form-container">
        <!-- Header -->
        <div class="grading-form-header">
            <h1><i class="fas fa-pen-fancy"></i> Nilai Jawaban Essay</h1>
            <p style="margin: 0; opacity: 0.9;">Berikan nilai dan feedback untuk jawaban siswa</p>
        </div>

        <!-- Student Info -->
        <div class="student-info">
            <div class="info-item">
                <div class="info-label">Nama Siswa</div>
                <div class="info-value">{{ $answer->user->name }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Email</div>
                <div class="info-value">{{ $answer->user->email }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Kelas</div>
                <div class="info-value">{{ $answer->user->class ?? '-' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Waktu Jawab</div>
                <div class="info-value">{{ $answer->created_at->format('d M Y H:i') }}</div>
            </div>
        </div>

        <!-- Question Section -->
        <div class="question-section">
            <div class="section-title">
                <i class="fas fa-question-circle"></i> Pertanyaan
            </div>
            <div class="question-text">
                <div class="question-content">{{ $question->question }}</div>
                <div class="question-meta">
                    <div class="question-meta-item">
                        <span class="type-badge">{{ ucfirst(str_replace('_', ' ', $question->type)) }}</span>
                    </div>
                    <div class="question-meta-item">
                        <i class="fas fa-star"></i> <strong>Poin: {{ $question->points }}</strong>
                    </div>
                    <div class="question-meta-item">
                        <i class="fas fa-layer-group"></i> {{ $question->module->name }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Answer Section -->
        <div class="answer-section">
            <div class="section-title">
                <i class="fas fa-lightbulb"></i> Jawaban Siswa
            </div>
            <div class="answer-text">
                @if (is_array(json_decode($answer->answer, true)))
                    @php
                        $decodedAnswers = json_decode($answer->answer, true);
                    @endphp
                    @foreach ($decodedAnswers as $qId => $ans)
                        <div style="margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid #e5e7eb;">
                            <strong style="color: #666;">Jawaban untuk soal (ID: {{ $qId }}):</strong><br>
                            {{ $ans }}
                        </div>
                    @endforeach
                @else
                    {{ $answer->answer }}
                @endif
            </div>
        </div>

        <!-- Grading Form Section -->
        <div class="grading-form">
            <div class="section-title" style="margin-bottom: 1.5rem;">
                <i class="fas fa-check-circle"></i> Penilaian & Feedback
            </div>

            <form method="POST" action="{{ route('guru.grading.store', $answer->id) }}">
                @csrf

                <div class="form-group">
                    <label for="teacher_score">Nilai (Score)</label>
                    <div class="score-input-group">
                        <div class="score-input">
                            <input type="number" id="teacher_score" name="teacher_score"
                                class="form-control @error('teacher_score') is-invalid @enderror"
                                min="0" max="{{ $question->points }}" value="{{ old('teacher_score') }}" required>
                            @error('teacher_score')
                                <div class="error-text">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="score-info">
                            dari <strong>{{ $question->points }} poin</strong>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="teacher_feedback">Feedback & Komentar</label>
                    <textarea id="teacher_feedback" name="teacher_feedback"
                        class="form-control @error('teacher_feedback') is-invalid @enderror"
                        rows="5" placeholder="Berikan feedback konstruktif untuk siswa..." required>{{ old('teacher_feedback') }}</textarea>
                    @error('teacher_feedback')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-check"></i> Simpan Penilaian
                    </button>
                    <a href="{{ route('guru.grading.index') }}" class="btn-cancel">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
