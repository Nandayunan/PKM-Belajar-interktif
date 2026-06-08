@extends('layouts.app')

@section('title', 'Edit Soal')

@section('content')
    @php
        $subjects = \App\Models\Subject::with('modules')->get();
        $modules = \App\Models\Module::all(['id', 'subject_id', 'name']);
        $currentModule = $question->module;
        $currentSubjectId = $currentModule ? $currentModule->subject_id : null;

        $currentOptions = [];
        if (is_array($question->options)) {
            $currentOptions = $question->options;
        } else {
            $decoded = json_decode($question->options ?? '[]', true);
            if (is_array($decoded)) {
                $currentOptions = $decoded;
            }
        }
    @endphp

@section('extra-css')
    <style>
        .question-card {
            max-width: 1100px;
            margin: 0 auto;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--card-shadow);
            display: flex;
            background: white;
        }

        .question-hero {
            min-width: 320px;
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.98), rgba(79, 70, 229, 0.95));
            color: white;
            padding: 2rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 0.5rem;
        }

        .question-hero h2 {
            font-size: 1.6rem;
            margin: 0;
            font-weight: 800;
        }

        .question-form {
            padding: 2rem;
            flex: 1;
        }

        .option-row {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .option-input {
            flex: 1;
            padding: 0.55rem;
            border: 1px solid #e6e6f0;
            border-radius: 8px;
        }

        .option-actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn-add-option {
            background: transparent;
            border: 2px dashed #e6e6f0;
            padding: 0.5rem 0.8rem;
            border-radius: 8px;
            color: #374151;
            font-weight: 700;
        }

        .correct-badge {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 0.35rem 0.6rem;
            border-radius: 999px;
            font-weight: 700;
        }

        @media (max-width: 768px) {
            .question-card {
                flex-direction: column;
            }

            .question-hero {
                min-height: 140px;
            }
        }
    </style>
@endsection

<div class="question-card card">
    <div class="question-hero">
        <div style="display:flex; gap:0.75rem; align-items:center">
            <div
                style="width:56px;height:56px;border-radius:12px;background:rgba(255,255,255,0.12);display:flex;align-items:center;justify-content:center">
                <i class="fas fa-question" style="font-size:20px"></i>
            </div>
            <div>
                <h2>Edit Soal</h2>
                <div style="opacity:0.95;">Perbarui isi soal dan opsi dengan mudah.</div>
            </div>
        </div>
    </div>

    <div class="question-form">
        <form method="POST" action="{{ route('guru.questions.update', $question->id) }}">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Pilih Mata Pelajaran</label>
                    <select id="subject-select" class="form-select" required>
                        <option value="">-- Pilih Mata Pelajaran --</option>
                        @foreach ($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ $subject->id === $currentSubjectId ? 'selected' : '' }}>{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Pilih Modul</label>
                    <select id="module-select" name="module_id" class="form-select" required>
                        <option value="">-- Pilih Modul --</option>
                        @foreach ($modules as $module)
                            <option value="{{ $module->id }}" {{ $module->id === $question->module_id ? 'selected' : '' }} data-subject-id="{{ $module->subject_id }}">{{ $module->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Kelas</label>
                    <input type="text" name="class" id="class-input" class="form-control"
                        placeholder="Contoh: VII-A" value="{{ old('class', $question->class) }}">
                </div>

                <div class="col-md-4 mt-3">
                    <label class="form-label">Tipe Soal</label>
                    <select name="type" id="question-type" class="form-select" required>
                        <option value="">-- Pilih Tipe --</option>
                        <option value="multiple_choice" {{ $question->type === 'multiple_choice' ? 'selected' : '' }}>Pilihan Ganda</option>
                        <option value="essay" {{ $question->type === 'essay' ? 'selected' : '' }}>Essay</option>
                        <option value="mixed" {{ $question->type === 'mixed' ? 'selected' : '' }}>Essay & Pilihan Ganda</option>
                        <option value="true_false" {{ $question->type === 'true_false' ? 'selected' : '' }}>Benar / Salah</option>
                    </select>
                </div>

                <div class="col-md-4 mt-3">
                    <label class="form-label">Poin</label>
                    <input type="number" name="points" value="{{ old('points', $question->points) }}" min="0" class="form-control"
                        required>
                </div>

                <div class="col-12 mt-3">
                    <label class="form-label">Pertanyaan</label>
                    <textarea name="question" id="question-text" class="form-control" rows="4" required>{{ old('question', $question->question) }}</textarea>
                </div>

                <div class="col-12" id="mc-options" style="display:none;">
                    <label class="form-label">Pilihan Jawaban (Pilihan Ganda)</label>
                    <div id="options-list" style="display:flex;flex-direction:column;gap:0.5rem"></div>

                    <div style="margin-top:0.75rem; display:flex; gap:0.5rem; align-items:center;">
                        <button type="button" id="add-option" class="btn-add-option">+ Tambah Opsi</button>
                        <div class="form-note" style="margin-left:0.5rem">Centang pilihan yang benar.</div>
                    </div>
                </div>

                <div class="col-12" id="tf-options" style="display:none;">
                    <label class="form-label">Jawaban Benar (Benar / Salah)</label>
                    <select name="correct_answer_tf" class="form-select" style="max-width:240px;">
                        <option value="true" {{ $question->correct_answer === 'true' ? 'selected' : '' }}>Benar</option>
                        <option value="false" {{ $question->correct_answer === 'false' ? 'selected' : '' }}>Salah</option>
                    </select>
                </div>

                <div class="col-12" id="essay-options" style="display:none;">
                    <label class="form-label">Jawaban Essay (opsional)</label>
                    <textarea name="correct_answer_essay" class="form-control" rows="3">{{ old('correct_answer_essay', $question->correct_answer) }}</textarea>
                </div>

                <div class="col-12 mt-2">
                    <div class="text-end">
                        <a href="{{ route('guru.dashboard') }}" class="btn btn-outline-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@section('extra-js')
    <script>
        const subjects = {!! $subjects->toJson() !!};
        const modules = {!! $modules->toJson() !!};
        const currentSubjectId = {{ json_encode($currentSubjectId) }};
        const currentModuleId = {{ json_encode($question->module_id) }};
        const currentOptions = {!! json_encode($currentOptions) !!};
        const currentCorrectAnswer = {{ json_encode($question->correct_answer) }};

        const subjectSelect = document.getElementById('subject-select');
        const moduleSelect = document.getElementById('module-select');
        const questionType = document.getElementById('question-type');
        const mcOptions = document.getElementById('mc-options');
        const tfOptions = document.getElementById('tf-options');
        const essayOptions = document.getElementById('essay-options');
        const optionsList = document.getElementById('options-list');
        const addOptionBtn = document.getElementById('add-option');

        function populateModules(subjectId) {
            const selected = moduleSelect.value;
            moduleSelect.innerHTML = '<option value="">-- Pilih Modul --</option>';
            if (!subjectId) {
                return;
            }
            modules.filter(m => m.subject_id === parseInt(subjectId)).forEach(m => {
                const opt = document.createElement('option');
                opt.value = m.id;
                opt.textContent = m.name;
                opt.dataset.subjectId = m.subject_id;
                if (m.id === currentModuleId) {
                    opt.selected = true;
                }
                moduleSelect.appendChild(opt);
            });
        }

        function updateVisibility() {
            const type = questionType.value;
            mcOptions.style.display = (type === 'multiple_choice' || type === 'mixed') ? 'block' : 'none';
            tfOptions.style.display = (type === 'true_false') ? 'block' : 'none';
            essayOptions.style.display = (type === 'essay' || type === 'mixed') ? 'block' : 'none';
        }

        function createOptionRow(text = '', index = null, checked = false) {
            const row = document.createElement('div');
            row.className = 'option-row';

            const radio = document.createElement('input');
            radio.type = 'radio';
            radio.name = 'correct_answer_mc';
            radio.value = index !== null ? index : '';
            radio.style.width = '18px';
            if (checked) {
                radio.checked = true;
            }

            const input = document.createElement('input');
            input.type = 'text';
            input.name = (typeof index === 'number') ? `options[${index}]` : 'options[]';
            input.placeholder = 'Tulis opsi jawaban...';
            input.className = 'option-input';
            input.value = text;

            const actions = document.createElement('div');
            actions.className = 'option-actions';

            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'btn btn-sm btn-outline-primary';
            removeBtn.innerHTML = '<i class="fas fa-trash"></i>';
            removeBtn.addEventListener('click', () => {
                row.remove();
                refreshOptionIndexes();
            });

            actions.appendChild(removeBtn);
            row.appendChild(radio);
            row.appendChild(input);
            row.appendChild(actions);

            return row;
        }

        function refreshOptionIndexes() {
            const rows = optionsList.querySelectorAll('.option-row');
            rows.forEach((r, i) => {
                const input = r.querySelector('input[type="text"]');
                const radio = r.querySelector('input[type="radio"]');
                input.name = `options[${i}]`;
                radio.value = i;
            });
        }

        addOptionBtn.addEventListener('click', () => {
            optionsList.appendChild(createOptionRow('', null, false));
            refreshOptionIndexes();
        });

        function initOptions() {
            optionsList.innerHTML = '';
            if (currentOptions.length > 0) {
                currentOptions.forEach((opt, index) => {
                    const checked = currentCorrectAnswer === opt;
                    optionsList.appendChild(createOptionRow(opt, index, checked));
                });
            } else {
                for (let i = 0; i < 4; i++) {
                    optionsList.appendChild(createOptionRow('', i, false));
                }
            }
            refreshOptionIndexes();
        }

        subjectSelect.value = currentSubjectId || '';
        populateModules(subjectSelect.value);
        questionType.addEventListener('change', updateVisibility);
        updateVisibility();
        initOptions();
    </script>
@endsection
@endsection
