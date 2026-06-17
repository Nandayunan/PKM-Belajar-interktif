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
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
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

        .symbol-toolbar {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 0.75rem;
            padding: 0.75rem;
            border: 1px solid #e6e6f0;
            border-radius: 10px;
            background: #fafafe;
        }

        .symbol-toolbar-label {
            width: 100%;
            font-size: 0.85rem;
            font-weight: 700;
            color: #6b7280;
            margin-bottom: 0.15rem;
        }

        .symbol-btn {
            border: 1px solid #d7dbea;
            background: white;
            color: #1f2937;
            border-radius: 999px;
            padding: 0.35rem 0.7rem;
            font-weight: 700;
            line-height: 1;
            transition: all 0.15s ease;
        }

        .symbol-btn:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
            transform: translateY(-1px);
        }

        .symbol-btn.template-btn {
            background: #f8faff;
        }

        .equation-launcher {
            margin-top: 0.75rem;
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .equation-panel {
            margin-top: 0.75rem;
            padding: 0.9rem;
            border: 1px solid #dfe3f0;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.06);
        }

        .equation-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.75rem;
        }

        .equation-grid .full {
            grid-column: 1 / -1;
        }

        .equation-output {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            background: #f8faff;
            border: 1px dashed #cfd7ea;
            border-radius: 8px;
            padding: 0.65rem 0.75rem;
            min-height: 42px;
            color: #1f2937;
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
                            <option value="{{ $subject->id }}"
                                {{ $subject->id === $currentSubjectId ? 'selected' : '' }}>{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Pilih Modul</label>
                    <select id="module-select" name="module_id" class="form-select" required>
                        <option value="">-- Pilih Modul --</option>
                        @foreach ($modules as $module)
                            <option value="{{ $module->id }}"
                                {{ $module->id === $question->module_id ? 'selected' : '' }}
                                data-subject-id="{{ $module->subject_id }}">{{ $module->name }}</option>
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
                        <option value="multiple_choice" {{ $question->type === 'multiple_choice' ? 'selected' : '' }}>
                            Pilihan Ganda</option>
                        <option value="essay" {{ $question->type === 'essay' ? 'selected' : '' }}>Essay</option>
                        <option value="mixed" {{ $question->type === 'mixed' ? 'selected' : '' }}>Essay & Pilihan
                            Ganda</option>
                        <option value="true_false" {{ $question->type === 'true_false' ? 'selected' : '' }}>Benar /
                            Salah</option>
                    </select>
                </div>

                <div class="col-md-4 mt-3">
                    <label class="form-label">Poin</label>
                    <input type="number" name="points" value="{{ old('points', $question->points) }}" min="0"
                        class="form-control" required>
                </div>

                <div class="col-12 mt-3">
                    <label class="form-label">Pertanyaan</label>
                    <textarea name="question" id="question-text" class="form-control" rows="4" required>{{ old('question', $question->question) }}</textarea>
                    <div class="symbol-toolbar" aria-label="Symbol toolbar for question input">
                        <div class="symbol-toolbar-label">Simbol cepat untuk soal dan opsi aktif</div>
                        <button type="button" class="symbol-btn" data-symbol="√">√ akar</button>
                        <button type="button" class="symbol-btn template-btn" data-template="power">x^n</button>
                        <button type="button" class="symbol-btn template-btn" data-template="fraction">a/b</button>
                        <button type="button" class="symbol-btn" data-symbol="²">x²</button>
                        <button type="button" class="symbol-btn" data-symbol="³">x³</button>
                        <button type="button" class="symbol-btn" data-symbol="½">½</button>
                        <button type="button" class="symbol-btn" data-symbol="¼">¼</button>
                        <button type="button" class="symbol-btn" data-symbol="¾">¾</button>
                        <button type="button" class="symbol-btn" data-symbol="π">π</button>
                        <button type="button" class="symbol-btn" data-symbol="∞">∞</button>
                        <button type="button" class="symbol-btn" data-symbol="≤">≤</button>
                        <button type="button" class="symbol-btn" data-symbol="≥">≥</button>
                        <button type="button" class="symbol-btn" data-symbol="≠">≠</button>
                        <button type="button" class="symbol-btn" data-symbol="×">×</button>
                        <button type="button" class="symbol-btn" data-symbol="÷">÷</button>
                        <button type="button" class="symbol-btn" data-symbol="∑">∑</button>
                        <button type="button" class="symbol-btn" data-symbol="∠">∠</button>
                    </div>
                    <div class="equation-launcher">
                        <button type="button" class="btn btn-outline-primary btn-sm" id="open-equation-builder">
                            <i class="fas fa-square-root-alt"></i>&nbsp; Insert Equation
                        </button>
                        <div class="form-note">Isi bagian rumus, lalu sisipkan ke soal atau opsi yang sedang aktif.</div>
                    </div>
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
                        <option value="true" {{ $question->correct_answer === 'true' ? 'selected' : '' }}>Benar
                        </option>
                        <option value="false" {{ $question->correct_answer === 'false' ? 'selected' : '' }}>Salah
                        </option>
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

<div id="equation-panel" class="equation-panel" style="display:none; max-width:1100px; margin:0 auto 1.25rem;">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
        <strong>Equation Builder</strong>
        <button type="button" class="btn btn-sm btn-outline-secondary" id="close-equation-builder">Tutup</button>
    </div>
    <div class="equation-grid">
        <div>
            <label class="form-label">Jenis</label>
            <select id="equation-type" class="form-select">
                <option value="power">Pangkat</option>
                <option value="fraction">Pecahan</option>
                <option value="root">Akar</option>
            </select>
        </div>
        <div>
            <label class="form-label">Base / Pembilang / Radikan</label>
            <input type="text" id="equation-base" class="form-control" placeholder="Contoh: x atau 3">
        </div>
        <div>
            <label class="form-label">Pangkat / Penyebut / Akar ke-</label>
            <input type="text" id="equation-exponent" class="form-control" placeholder="Contoh: 2 atau 4">
        </div>
        <div>
            <label class="form-label">Tambahan</label>
            <input type="text" id="equation-extra" class="form-control" placeholder="Opsional">
        </div>
        <div class="full">
            <label class="form-label">Hasil Equation</label>
            <div id="equation-preview" class="equation-output">x^(2)</div>
        </div>
        <div class="full d-flex gap-2 flex-wrap">
            <button type="button" class="btn btn-primary" id="insert-equation">Sisipkan Equation</button>
            <button type="button" class="btn btn-outline-secondary" id="clear-equation-fields">Bersihkan</button>
        </div>
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
        const symbolButtons = document.querySelectorAll('[data-symbol]');
        const templateButtons = document.querySelectorAll('[data-template]');
        const openEquationBuilder = document.getElementById('open-equation-builder');
        const closeEquationBuilder = document.getElementById('close-equation-builder');
        const equationPanel = document.getElementById('equation-panel');
        const equationType = document.getElementById('equation-type');
        const equationBase = document.getElementById('equation-base');
        const equationExponent = document.getElementById('equation-exponent');
        const equationExtra = document.getElementById('equation-extra');
        const equationPreview = document.getElementById('equation-preview');
        const insertEquationBtn = document.getElementById('insert-equation');
        const clearEquationFieldsBtn = document.getElementById('clear-equation-fields');
        let activeSymbolTarget = document.getElementById('question-text');

        function setActiveSymbolTarget(element) {
            if (!element) return;
            activeSymbolTarget = element;
        }

        function insertSymbol(symbol) {
            const target = activeSymbolTarget;
            if (!target || typeof target.value === 'undefined') return;

            const start = typeof target.selectionStart === 'number' ? target.selectionStart : target.value.length;
            const end = typeof target.selectionEnd === 'number' ? target.selectionEnd : target.value.length;
            const before = target.value.slice(0, start);
            const after = target.value.slice(end);
            target.value = before + symbol + after;
            const caret = start + symbol.length;
            if (typeof target.setSelectionRange === 'function') {
                target.setSelectionRange(caret, caret);
            }
            target.focus();
        }

        function insertTemplate(templateName) {
            const target = activeSymbolTarget;
            if (!target || typeof target.value === 'undefined') return;

            const start = typeof target.selectionStart === 'number' ? target.selectionStart : target.value.length;
            const end = typeof target.selectionEnd === 'number' ? target.selectionEnd : target.value.length;
            const before = target.value.slice(0, start);
            const after = target.value.slice(end);

            let inserted = '';
            let caretOffset = 0;

            if (templateName === 'power') {
                inserted = '^( )';
                caretOffset = 2;
            } else if (templateName === 'fraction') {
                inserted = '( )/( )';
                caretOffset = 1;
            }

            if (!inserted) return;

            target.value = before + inserted + after;
            const caret = start + caretOffset;
            if (typeof target.setSelectionRange === 'function') {
                target.setSelectionRange(caret, caret);
            }
            target.focus();
        }

        function toSuperscript(value) {
            const map = {
                '0': '⁰',
                '1': '¹',
                '2': '²',
                '3': '³',
                '4': '⁴',
                '5': '⁵',
                '6': '⁶',
                '7': '⁷',
                '8': '⁸',
                '9': '⁹',
                '+': '⁺',
                '-': '⁻',
                '=': '⁼',
                '(': '⁽',
                ')': '⁾'
            };

            return String(value).split('').map((char) => map[char] || char).join('');
        }

        function formatFraction(numerator, denominator) {
            const key = `${numerator}/${denominator}`;
            const glyphMap = {
                '1/2': '½',
                '1/4': '¼',
                '3/4': '¾',
                '1/3': '⅓',
                '2/3': '⅔',
                '1/5': '⅕',
                '2/5': '⅖',
                '3/5': '⅗',
                '4/5': '⅘',
                '1/6': '⅙',
                '5/6': '⅚',
                '1/8': '⅛',
                '3/8': '⅜',
                '5/8': '⅝',
                '7/8': '⅞'
            };

            if (glyphMap[key]) {
                return glyphMap[key];
            }

            return `${numerator}⁄${denominator}`;
        }

        function buildEquationText() {
            const type = equationType.value || 'power';
            const first = (equationBase.value || '').trim();
            const second = (equationExponent.value || '').trim();
            const extra = (equationExtra.value || '').trim();

            if (type === 'fraction') {
                const numerator = first || 'a';
                const denominator = second || 'b';
                return formatFraction(numerator, denominator);
            }

            if (type === 'root') {
                const radicand = first || 'x';
                if (second) {
                    return `${second}√(${radicand})`;
                }
                return `√(${radicand})`;
            }

            const base = first || 'x';
            const exponent = second || '2';
            return `${base}${toSuperscript(exponent)}${extra ? ` ${extra}` : ''}`.trim();
        }

        function refreshEquationPreview() {
            equationPreview.textContent = buildEquationText();
        }

        function openEquationBuilderPanel() {
            equationPanel.style.display = 'block';
            refreshEquationPreview();
            equationPanel.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        function closeEquationBuilderPanel() {
            equationPanel.style.display = 'none';
        }

        equationType.addEventListener('change', refreshEquationPreview);
        equationBase.addEventListener('input', refreshEquationPreview);
        equationExponent.addEventListener('input', refreshEquationPreview);
        equationExtra.addEventListener('input', refreshEquationPreview);

        if (openEquationBuilder) {
            openEquationBuilder.addEventListener('click', openEquationBuilderPanel);
        }
        if (closeEquationBuilder) {
            closeEquationBuilder.addEventListener('click', closeEquationBuilderPanel);
        }
        if (clearEquationFieldsBtn) {
            clearEquationFieldsBtn.addEventListener('click', () => {
                equationBase.value = '';
                equationExponent.value = '';
                equationExtra.value = '';
                equationType.value = 'power';
                refreshEquationPreview();
            });
        }
        if (insertEquationBtn) {
            insertEquationBtn.addEventListener('click', () => {
                insertSymbol(buildEquationText());
            });
        }

        document.querySelectorAll('textarea, input[type="text"]').forEach((field) => {
            field.addEventListener('focus', () => setActiveSymbolTarget(field));
            field.addEventListener('click', () => setActiveSymbolTarget(field));
        });

        symbolButtons.forEach((button) => {
            button.addEventListener('mousedown', (event) => event.preventDefault());
            button.addEventListener('click', () => insertSymbol(button.dataset.symbol || ''));
        });

        templateButtons.forEach((button) => {
            button.addEventListener('mousedown', (event) => event.preventDefault());
            button.addEventListener('click', () => insertTemplate(button.dataset.template || ''));
        });

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
