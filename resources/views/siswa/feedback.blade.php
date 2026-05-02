@extends('layouts.app')

@section('title', 'Feedback Pembelajaran')

@section('extra-css')
    <style>
        .feedback-container {
            max-width: 700px;
            margin: 0 auto;
        }

        .feedback-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.3);
            text-align: center;
        }

        .feedback-header h1 {
            margin: 0;
            margin-bottom: 0.5rem;
            font-size: 1.8rem;
        }

        .feedback-header p {
            margin: 0;
            opacity: 0.95;
        }

        .progress-summary {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .summary-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .summary-label {
            color: #666;
            font-weight: 600;
        }

        .summary-value {
            font-weight: 700;
            color: var(--primary-color);
            font-size: 1.2rem;
        }

        .feedback-form {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .form-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .form-title i {
            color: var(--primary-color);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 0.75rem;
            font-size: 1rem;
        }

        .label-help {
            display: block;
            font-weight: 400;
            color: #666;
            font-size: 0.9rem;
            margin-top: 0.25rem;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 1rem;
            font-family: inherit;
            transition: all 0.3s;
            resize: vertical;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.1);
            outline: none;
        }

        textarea.form-control {
            min-height: 120px;
        }

        .char-count {
            font-size: 0.8rem;
            color: #999;
            margin-top: 0.25rem;
            text-align: right;
        }

        .btn-group {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 2px solid #e5e7eb;
        }

        .btn-submit,
        .btn-cancel {
            flex: 1;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-submit {
            background: linear-gradient(135deg, var(--success-color), #059669);
            color: white;
            box-shadow: 0 5px 15px rgba(16, 185, 129, 0.3);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
        }

        .btn-cancel {
            background: #f3f4f6;
            color: #666;
        }

        .btn-cancel:hover {
            background: #e5e7eb;
            color: #2d3748;
        }

        .error-text {
            color: #dc2626;
            font-size: 0.85rem;
            margin-top: 0.25rem;
        }

        .note-box {
            background: #fef3c7;
            border: 1px solid #fcd34d;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            color: #92400e;
        }

        .note-box i {
            margin-right: 0.5rem;
        }

        @media (max-width: 640px) {
            .feedback-container {
                margin: 1rem;
            }

            .feedback-form {
                padding: 1.5rem;
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
    <div class="feedback-container">
        <!-- Header -->
        <div class="feedback-header">
            <h1><i class="fas fa-comments"></i> Feedback Pembelajaran</h1>
            <p>Bagikan pengalaman dan perasaan Anda setelah mengerjakan soal</p>
        </div>

        <!-- Progress Summary -->
        <div class="progress-summary">
            <div class="summary-item">
                <span class="summary-label"><i class="fas fa-book"></i> Modul</span>
                <span class="summary-value">{{ $progress->module->name }}</span>
            </div>
            <div class="summary-item">
                <span class="summary-label"><i class="fas fa-percentage"></i> Nilai Akhir</span>
                <span class="summary-value">{{ round($progress->percentage) }}%</span>
            </div>
            <div class="summary-item">
                <span class="summary-label"><i class="fas fa-star"></i> Poin</span>
                <span class="summary-value">{{ $progress->earned_points }}/{{ $progress->total_points }}</span>
            </div>
        </div>

        <!-- Feedback Form -->
        <div class="feedback-form">
            <div class="form-title">
                <i class="fas fa-pen-fancy"></i> Berikan Feedback Anda
            </div>

            <div class="note-box">
                <i class="fas fa-lightbulb"></i>
                <strong>Catatan:</strong> Feedback Anda sangat membantu kami untuk meningkatkan kualitas pembelajaran.
                Silakan isi dengan jujur dan detail.
            </div>

            <form method="POST" action="{{ route('siswa.feedback.store', $progress->id) }}">
                @csrf

                <div class="form-group">
                    <label for="student_feedback">
                        Evaluasi Hasil Kinerja
                        <span class="label-help">Bagaimana menurut Anda dengan hasil kinerja Anda di modul ini?
                            Apa yang sudah baik? Apa yang perlu ditingkatkan?</span>
                    </label>
                    <textarea id="student_feedback" name="student_feedback"
                        class="form-control @error('student_feedback') is-invalid @enderror"
                        placeholder="Contoh: Saya sudah paham tentang konsep X, tetapi masih kesulitan dengan Y..." required>{{ old('student_feedback') }}</textarea>
                    <div class="char-count">
                        <span id="feedback-count">0</span>/1000 karakter
                    </div>
                    @error('student_feedback')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="student_feelings">
                        Perasaan & Kesan Pembelajaran
                        <span class="label-help">Bagaimana perasaan Anda setelah mengerjakan soal? Adakah hal yang membuat
                            Anda semangat atau kesulitan?</span>
                    </label>
                    <textarea id="student_feelings" name="student_feelings"
                        class="form-control @error('student_feelings') is-invalid @enderror"
                        placeholder="Contoh: Saya merasa senang karena soal-soalnya menarik, tapi saya sedikit tegang saat mengerjakan..."
                        required>{{ old('student_feelings') }}</textarea>
                    <div class="char-count">
                        <span id="feelings-count">0</span>/500 karakter
                    </div>
                    @error('student_feelings')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-check"></i> Kirim Feedback
                    </button>
                    <a href="{{ route('siswa.modules.show', [$progress->subject_id, $progress->module_id]) }}"
                        class="btn-cancel">
                        <i class="fas fa-times"></i> Lewati
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Character counter for feedback
        const feedbackInput = document.getElementById('student_feedback');
        const feedbackCount = document.getElementById('feedback-count');
        feedbackInput.addEventListener('input', () => {
            feedbackCount.textContent = feedbackInput.value.length;
        });

        // Character counter for feelings
        const feelingsInput = document.getElementById('student_feelings');
        const feelingsCount = document.getElementById('feelings-count');
        feelingsInput.addEventListener('input', () => {
            feelingsCount.textContent = feelingsInput.value.length;
        });

        // Initialize counters
        feedbackCount.textContent = feedbackInput.value.length;
        feelingsCount.textContent = feelingsInput.value.length;
    </script>
@endsection
