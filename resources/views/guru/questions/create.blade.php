@extends('layouts.app')

@section('title', 'Buat Soal')

@section('content')
    @php
        $subjects = \App\Models\Subject::with('modules')->get();
        $modules = \App\Models\Module::all(['id', 'subject_id', 'name']);
    @endphp
@section('extra-css')
    <style>
        .question-card {
            max-width: 1100px;
            margin: 0 auto;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--card-shadow);
            background: transparent;
        }

        .question-hero {
            width: 100%;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 2rem 2.25rem;
            display: block;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }

        .question-hero h2 {
            font-size: 1.6rem;
            margin: 0 0 0.25rem 0;
            font-weight: 800;
        }

        .question-form {
            padding: 1.75rem 2.25rem 2.25rem 2.25rem;
            background: white;
            border-bottom-left-radius: 12px;
            border-bottom-right-radius: 12px;
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
            .question-hero {
                padding: 1.25rem 1rem;
            }

            .question-form {
                padding: 1rem;
            }
        }

        .selection-panel {
            background: white;
            border-radius: 12px;
            padding: 1rem 1.25rem;
            box-shadow: var(--card-shadow);
            margin-bottom: 1rem;
        }

        .btn-primary-hero {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            border: none;
            box-shadow: 0 10px 30px rgba(21, 30, 81, 0.18);
            padding: 0.6rem 1.1rem;
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
                <h2>Buat / Edit Soal</h2>
                <div style="opacity:0.95;">Desain form yang bersih dan fokus pada produktivitas guru.</div>
            </div>
        </div>
    </div>

    <div class="question-form">
        <form method="POST" action="{{ route('guru.questions.store') }}">
            @csrf

            <div class="selection-panel">
                <div class="row g-3 align-items-center">
                    <!-- Selection panel: choose subject, module, class first -->
                    <div class="col-md-6">
                        <label class="form-label">Pilih Mata Pelajaran</label>
                        <select id="subject-select" class="form-select" required>
                            <option value="">-- Pilih Mata Pelajaran --</option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Pilih Modul</label>
                        <select id="module-select" name="module_id" class="form-select" disabled>
                            <option value="">-- Pilih Modul --</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Kelas</label>
                        <input type="text" name="class" id="class-input" class="form-control"
                            placeholder="Contoh: VII-A">
                    </div>

                    <div class="col-12 mt-2">
                        <div class="d-flex flex-wrap align-items-center" style="gap:0.75rem;">
                            <button type="button" id="btn-continue" class="btn btn-primary btn-lg btn-primary-hero">
                                <i class="fas fa-arrow-right"></i>&nbsp; Lanjutkan
                            </button>
                            <button type="button" id="btn-reset-selection"
                                class="btn btn-outline-secondary btn-lg">Ubah
                                Pilihan</button>
                            <a href="{{ route('guru.dashboard') }}" class="btn btn-outline-danger btn-lg">Batal</a>
                            <div class="form-note text-muted ms-3">Pilih mata pelajaran, modul, dan kelas terlebih
                                dahulu.</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hidden manual form: revealed after user clicks 'Buat Soal Manual' -->
            <div id="manual-form" style="display:none; width:100%">
                <div class="col-md-4 mt-3">
                    <label class="form-label">Tipe Soal</label>
                    <select name="type" id="question-type" class="form-select" required>
                        <option value="">-- Pilih Tipe --</option>
                        <option value="multiple_choice">Pilihan Ganda</option>
                        <option value="essay">Essay</option>
                        <option value="mixed">Essay & Pilihan Ganda</option>
                        <option value="true_false">Benar / Salah</option>
                    </select>
                </div>

                <div class="col-md-4 mt-3">
                    <label class="form-label">Poin</label>
                    <input type="number" name="points" value="10" min="0" class="form-control" required>
                </div>

                <div class="col-12 mt-3">
                    <label class="form-label">Pertanyaan</label>
                    <textarea name="question" id="question-text" class="form-control" rows="4" required></textarea>
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

            </div>

            <!-- Multiple choice options -->
            <div class="col-12" id="mc-options" style="display:none;">
                <label class="form-label">Pilihan Jawaban (Pilihan Ganda)</label>
                <div id="options-list" style="display:flex;flex-direction:column;gap:0.5rem">
                    <!-- option rows injected by JS -->
                </div>

                <div style="margin-top:0.75rem; display:flex; gap:0.5rem; align-items:center;">
                    <button type="button" id="add-option" class="btn-add-option">+ Tambah Opsi</button>
                    <div class="form-note" style="margin-left:0.5rem">Centang pilihan yang benar.</div>
                </div>
            </div>

            <!-- True/False -->
            <div class="col-12" id="tf-options" style="display:none;">
                <label class="form-label">Jawaban Benar (Benar / Salah)</label>
                <select name="correct_answer_tf" class="form-select" style="max-width:240px;">
                    <option value="true">Benar</option>
                    <option value="false">Salah</option>
                </select>
            </div>

            <div class="col-12 mt-2">
                <div class="d-flex justify-content-between align-items-center">
                    <div id="manual-actions" style="display:none;">
                        <button type="submit" class="btn btn-success btn-lg me-2" id="btn-save-manual">
                            <i class="fas fa-save"></i>&nbsp; Simpan Soal
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-lg"
                            id="btn-cancel-manual">Batal</button>
                    </div>
                    <div>
                        <a href="{{ route('guru.dashboard') }}" class="btn btn-outline-secondary">Kembali</a>
                    </div>
                </div>
            </div>
    </div>
    <!-- Decision card: shown after pressing Continue -->
    <div id="decision-card" style="display:none; margin-top:1.25rem;">
        <div class="card shadow-sm">
            <div class="card-body d-flex flex-column flex-md-row align-items-center justify-content-between">
                <div>
                    <h5 class="mb-1">Pilih Aksi Selanjutnya</h5>
                    <div class="text-muted">Lanjutkan membuat soal manual atau impor dari file CSV/Excel.</div>
                </div>
                <div class="d-flex" style="gap:0.75rem; margin-top:0.75rem;">
                    <button type="button" id="dec-manual" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-pencil-alt"></i>&nbsp; Buat Manual
                    </button>
                    <button type="button" id="dec-import" class="btn btn-primary btn-lg">
                        <i class="fas fa-file-import"></i>&nbsp; Import File (.csv/.xlsx)
                    </button>
                </div>
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
        const modules = {!! $modules->toJson() !!};

        const subjectSelect = document.getElementById('subject-select');
        const moduleSelect = document.getElementById('module-select');
        const questionType = document.getElementById('question-type');
        const mcOptions = document.getElementById('mc-options');
        const tfOptions = document.getElementById('tf-options');
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
        const textFields = document.querySelectorAll('textarea[name="question"], input[type="text"], textarea');
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
            moduleSelect.innerHTML = '<option value="">-- Pilih Modul --</option>';
            if (!subjectId) {
                moduleSelect.disabled = true;
                return;
            }
            const filtered = modules.filter(m => m.subject_id === parseInt(subjectId));
            filtered.forEach(m => {
                const opt = document.createElement('option');
                opt.value = m.id;
                opt.textContent = m.name;
                moduleSelect.appendChild(opt);
            });
            moduleSelect.disabled = false;
        }

        subjectSelect.addEventListener('change', function(e) {
            populateModules(e.target.value);
        });

        function updateVisibility() {
            const type = questionType.value;
            mcOptions.style.display = (type === 'multiple_choice' || type === 'mixed') ? 'block' : 'none';
            tfOptions.style.display = (type === 'true_false') ? 'block' : 'none';
        }

        questionType.addEventListener('change', updateVisibility);
        updateVisibility();

        // Options management
        function createOptionRow(text = '', index = null) {
            const row = document.createElement('div');
            row.className = 'option-row';

            const radio = document.createElement('input');
            radio.type = 'radio';
            radio.name = 'correct_answer_mc';
            radio.value = index !== null ? index : '';
            radio.style.width = '18px';

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
            optionsList.appendChild(createOptionRow('', null));
            refreshOptionIndexes();
        });

        // Initialize with 4 empty options to match previous UX
        for (let i = 0; i < 4; i++) {
            optionsList.appendChild(createOptionRow('', i));
        }

        // New flow: require subject/module/class first, then continue -> decision manual/import
        const btnContinue = document.getElementById('btn-continue');
        const btnReset = document.getElementById('btn-reset-selection');
        const decisionCard = document.getElementById('decision-card');
        const decManual = document.getElementById('dec-manual');
        const decImport = document.getElementById('dec-import');
        const manualForm = document.getElementById('manual-form');
        const manualActions = document.getElementById('manual-actions');
        const classInput = document.getElementById('class-input');

        btnContinue.addEventListener('click', () => {
            // require selections
            if (!subjectSelect.value) {
                alert('Pilih mata pelajaran terlebih dahulu.');
                return;
            }
            if (!moduleSelect.value) {
                alert('Pilih modul terlebih dahulu.');
                return;
            }
            // show decision card
            decisionCard.style.display = 'block';
            decisionCard.scrollIntoView({
                behavior: 'smooth'
            });
        });

        btnReset.addEventListener('click', () => {
            // allow user to change selections
            decisionCard.style.display = 'none';
            manualForm.style.display = 'none';
            if (manualActions) manualActions.style.display = 'none';
        });

        decManual.addEventListener('click', () => {
            // reveal manual form
            manualForm.style.display = 'block';
            decisionCard.style.display = 'none';
            if (manualActions) {
                manualActions.style.display = 'flex';
                // scroll to manual actions at bottom
                manualActions.scrollIntoView({
                    behavior: 'smooth'
                });
            } else {
                manualForm.scrollIntoView({
                    behavior: 'smooth'
                });
            }
            updateVisibility();
        });

        decImport.addEventListener('click', () => {
            // redirect to import with query params
            let url = '{{ route('guru.questions.import') }}';
            const params = new URLSearchParams();
            if (moduleSelect.value) params.set('module_id', moduleSelect.value);
            if (classInput.value) params.set('class', classInput.value);
            const q = params.toString();
            if (q) url += '?' + q;
            window.location.href = url;
        });

        // cancel manual creation and go back to decision card
        const btnCancelManual = document.getElementById('btn-cancel-manual');
        if (btnCancelManual) {
            btnCancelManual.addEventListener('click', () => {
                manualForm.style.display = 'none';
                if (manualActions) manualActions.style.display = 'none';
                decisionCard.style.display = 'block';
                decisionCard.scrollIntoView({
                    behavior: 'smooth'
                });
            });
        }
    </script>
@endsection
@endsection
