@extends('layouts.app')

@section('title', 'Modul - ' . $module->name)

@section('extra-css')
    <style>
        .module-detail-header {
            background: linear-gradient(135deg, {{ $subject->color }}, {{ adjustColor($subject->color, 20) }});
            color: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
        }

        .module-detail-header h1 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
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

        .content-section {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--card-shadow);
        }

        .section-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .section-title i {
            color: var(--primary-color);
        }

        /* Video Player */
        .video-container {
            position: relative;
            width: 100%;
            padding-bottom: 56.25%;
            /* 16:9 aspect ratio */
            height: 0;
            overflow: hidden;
            border-radius: 15px;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.2);
        }

        .video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border-radius: 15px;
            border: none;
        }

        .video-placeholder {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            border-radius: 15px;
        }

        /* Module Content */
        .module-text {
            color: #2d3748;
            line-height: 1.8;
            font-size: 1rem;
            margin-bottom: 1rem;
        }

        .module-text strong {
            color: var(--primary-color);
            font-weight: 700;
        }

        /* Questions Section */
        .questions-container {
            margin-top: 2rem;
        }

        .question-card {
            background: #f8f9ff;
            border: 2px solid #e5e7eb;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            transition: all 0.3s;
            border-left: 5px solid transparent;
        }

        .question-card.current {
            border-color: var(--primary-color);
            border-left-color: var(--primary-color);
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.05), rgba(99, 102, 241, 0.02));
        }

        .question-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .question-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.1rem;
        }

        .question-text {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2d3748;
            flex: 1;
        }

        .question-type-badge {
            display: inline-block;
            padding: 0.4rem 1rem;
            background: var(--primary-color);
            color: white;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .question-points {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--success-color);
            margin-left: 1rem;
        }

        /* Answer Options */
        .answer-option {
            background: white;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            padding: 1rem 1.5rem;
            margin-bottom: 1rem;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }

        .answer-option:hover {
            border-color: var(--primary-color);
            background: #f8f9ff;
        }

        .answer-option input[type="radio"],
        .answer-option input[type="checkbox"] {
            width: 24px;
            height: 24px;
            cursor: pointer;
            margin-top: 2px;
            flex-shrink: 0;
        }

        .answer-text {
            color: #2d3748;
            font-weight: 500;
            flex: 1;
        }

        /* Essay Answer */
        .essay-textarea {
            width: 100%;
            padding: 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-family: 'Poppins', sans-serif;
            font-size: 0.95rem;
            resize: vertical;
            min-height: 150px;
            transition: all 0.3s;
        }

        .essay-textarea:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.1);
        }

        /* Action Buttons */
        .question-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            flex-wrap: wrap;
        }

        .btn-action {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-action.primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
        }

        .btn-action.primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(99, 102, 241, 0.3);
            color: white;
        }

        .btn-action.secondary {
            background: #f3f4f6;
            color: #666;
        }

        .btn-action.secondary:hover {
            background: #e5e7eb;
        }

        /* Progress Bar */
        .progress-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            background: #f8f9ff;
            padding: 1rem 1.5rem;
            border-radius: 10px;
        }

        .progress-bar-thin {
            flex: 1;
            height: 8px;
            background: #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
            margin: 0 1rem;
        }

        .progress-fill-thin {
            height: 100%;
            background: linear-gradient(90deg, var(--primary-color), var(--primary-dark));
            border-radius: 10px;
            transition: width 0.3s;
        }

        .progress-text {
            font-weight: 600;
            color: var(--primary-color);
            min-width: 80px;
            text-align: right;
        }

        /* Result Toast */
        .result-toast {
            position: fixed;
            top: 100px;
            right: 20px;
            background: white;
            padding: 1.5rem;
            border-radius: 15px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            animation: slideInRight 0.3s ease;
            max-width: 350px;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .result-toast.success {
            border-left: 5px solid var(--success-color);
        }

        .result-toast.error {
            border-left: 5px solid var(--danger-color);
        }

        .result-toast-icon {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .result-toast-message {
            color: #2d3748;
            font-weight: 600;
            margin-bottom: 0.3rem;
        }

        .result-toast-details {
            font-size: 0.9rem;
            color: #666;
        }

        @media (max-width: 768px) {
            .question-actions {
                flex-direction: column;
            }

            .btn-action {
                width: 100%;
                justify-content: center;
            }

            .progress-section {
                flex-wrap: wrap;
            }

            .progress-bar-thin {
                width: 100%;
                margin: 1rem 0;
            }

            .progress-text {
                width: 100%;
                text-align: center;
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
        <a href="{{ route('siswa.modules.index', $subject->id) }}">
            {{ $subject->name }}
        </a>
        <span style="color: #ccc;"> / </span>
        <span style="color: #666;">Modul {{ $module->module_number }}</span>
    </div>

    <!-- Header -->
    <div class="module-detail-header">
        <h1><i class="fas fa-book-open"></i> {{ $module->name }}</h1>
        <p style="opacity: 0.9; margin: 0;">Modul {{ $module->module_number }} dari {{ $totalModules }}</p>
    </div>

    <!-- Module Content Section -->
    <div class="content-section">
        <div class="section-title">
            <i class="fas fa-file-alt"></i> Materi Pembelajaran
        </div>

        <p class="module-text">{{ $module->content }}</p>

        @if ($module->pdf_path)
            <div style="margin-top:1rem;">
                <a href="{{ asset('storage/' . $module->pdf_path) }}" target="_blank" class="btn-action primary"
                    style="display:inline-flex; align-items:center; gap:0.5rem; text-decoration:none;">
                    <i class="fas fa-file-pdf"></i> Buka/Unduh PDF Materi
                </a>
                <p style="color:#666; font-size:0.9rem; margin-top:0.5rem;">File PDF tersedia untuk diunduh atau
                    dibuka di tab baru.</p>
            </div>
        @endif
    </div>

    <!-- Video Section -->
    @if ($module->video_url)
        <div class="content-section">
            <div class="section-title">
                <i class="fas fa-video"></i> Video Pembelajaran
            </div>

            <div class="video-container">
                {!! $embedVideoUrl !!}
            </div>

            <p style="color: #666; font-size: 0.9rem; text-align: center;">
                <i class="fas fa-play-circle"></i> Tonton video untuk memahami materi dengan lebih baik
            </p>
        </div>
    @endif

    <!-- Questions Section -->
    @if ($questions->isNotEmpty())
        <div class="content-section">
            <div class="progress-section">
                <span style="font-weight: 600; color: #666;">Soal {{ $currentQuestionNumber }} dari
                    {{ $questions->count() }}</span>
                <div class="progress-bar-thin">
                    <div class="progress-fill-thin"
                        style="width: {{ ($currentQuestionNumber / $questions->count()) * 100 }}%"></div>
                </div>
                <span class="progress-text">{{ round(($currentQuestionNumber / $questions->count()) * 100) }}%</span>
            </div>

            <form action="{{ route('siswa.modules.submit-answer', [$subject->id, $module->id]) }}" method="POST">
                @csrf

                <div class="questions-container">
                    @foreach ($questions as $index => $question)
                        @php
                            $isFirst = $index === 0;
                            $userAnswer = auth()->user()->answers()->where('question_id', $question->id)->first();
                        @endphp

                        <div class="question-card {{ $isFirst ? 'current' : '' }}" id="question-{{ $question->id }}"
                            style="display: {{ $isFirst ? 'block' : 'none' }};">
                            <div class="question-header">
                                <div class="question-number">{{ $index + 1 }}</div>
                                <div class="question-text">{{ $question->question }}</div>
                                <span class="question-type-badge">
                                    @switch($question->type)
                                        @case('multiple_choice')
                                            Pilihan Ganda
                                        @break

                                        @case('true_false')
                                            Benar/Salah
                                        @break

                                        @case('essay')
                                            Essay
                                        @break
                                    @endswitch
                                </span>
                                <span class="question-points"><i class="fas fa-star"></i> +{{ $question->points }}
                                    Poin</span>
                            </div>

                            @if ($question->type === 'essay')
                                <textarea name="answers[{{ $question->id }}]" class="essay-textarea" placeholder="Tulis jawaban Anda di sini...">{{ old('answers.' . $question->id, $userAnswer?->answer ?? '') }}</textarea>
                            @elseif ($question->type === 'true_false')
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                                    <label class="answer-option" style="cursor: pointer;">
                                        <input type="radio" name="answers[{{ $question->id }}]" value="true"
                                            {{ old('answers.' . $question->id, $userAnswer?->answer) === 'true' || $userAnswer?->answer === true ? 'checked' : '' }}>
                                        <div class="answer-text">
                                            <i class="fas fa-check" style="color: var(--success-color);"></i> Benar
                                        </div>
                                    </label>
                                    <label class="answer-option" style="cursor: pointer;">
                                        <input type="radio" name="answers[{{ $question->id }}]" value="false"
                                            {{ old('answers.' . $question->id, $userAnswer?->answer) === 'false' || $userAnswer?->answer === false ? 'checked' : '' }}>
                                        <div class="answer-text">
                                            <i class="fas fa-times" style="color: var(--danger-color);"></i> Salah
                                        </div>
                                    </label>
                                </div>
                            @else
                                @php
                                    // Options may already be cast to array by the model. Handle both cases.
                                    if (is_array($question->options)) {
                                        $options = $question->options;
                                    } else {
                                        $options = json_decode($question->options ?? '[]', true) ?? [];
                                    }
                                @endphp

                                @foreach ($options as $option)
                                    <label class="answer-option" style="cursor: pointer;">
                                        <input type="radio" name="answers[{{ $question->id }}]"
                                            value="{{ $option }}"
                                            {{ old('answers.' . $question->id, $userAnswer?->answer) === $option ? 'checked' : '' }}>
                                        <div class="answer-text">{{ $option }}</div>
                                    </label>
                                @endforeach
                            @endif

                            <div class="question-actions">
                                @if ($index > 0)
                                    <button type="button" class="btn-action secondary"
                                        onclick="showQuestion({{ $questions[$index - 1]->id }})">
                                        <i class="fas fa-arrow-left"></i> Sebelumnya
                                    </button>
                                @endif

                                @if ($index < count($questions) - 1)
                                    <button type="button" class="btn-action secondary"
                                        onclick="showQuestion({{ $questions[$index + 1]->id }})">
                                        Selanjutnya <i class="fas fa-arrow-right"></i>
                                    </button>
                                @else
                                    <button type="submit" class="btn-action primary" style="margin-left: auto;">
                                        <i class="fas fa-check"></i> Selesai & Kirim Jawaban
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </form>
        </div>
    @else
        <div class="content-section">
            <div style="text-align: center; padding: 2rem;">
                <i class="fas fa-inbox" style="font-size: 3rem; color: #ccc; margin-bottom: 1rem; display: block;"></i>
                <h3 style="color: #999; margin-bottom: 0.5rem;">Belum ada soal</h3>
                <p style="color: #bbb;">Guru belum menambahkan soal untuk modul ini</p>
            </div>
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

        function showQuestion(questionId) {
            // Hide all questions
            document.querySelectorAll('.question-card').forEach(card => {
                card.style.display = 'none';
                card.classList.remove('current');
            });

            // Show selected question
            const selectedQuestion = document.getElementById('question-' + questionId);
            if (selectedQuestion) {
                selectedQuestion.style.display = 'block';
                selectedQuestion.classList.add('current');
                selectedQuestion.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        }
    </script>
@endsection
