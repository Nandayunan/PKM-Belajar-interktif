@extends('layouts.app')

@section('title', 'Buat Soal')

@section('content')
    @php
        $subjects = \App\Models\Subject::with('modules')->get();
        $modules = \App\Models\Module::all(['id', 'subject_id', 'name']);
    @endphp

    <div
        style="background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 8px 24px rgba(0,0,0,0.06); max-width: 820px; margin: 0 auto;">
        <h1 style="color: var(--primary-color); margin-bottom: 1.5rem; font-size: 28px; font-weight:700;">
            <i class="fas fa-plus" style="margin-right:8px"></i> Buat Soal Baru
        </h1>

        <form method="POST" action="{{ route('guru.questions.store') }}">
            @csrf

            <div style="display:flex; gap:1.5rem;">
                <div style="flex:1">
                    <label style="font-weight:700; display:block; margin-bottom:0.5rem">Pilih Mata Pelajaran</label>
                    <select id="subject-select"
                        style="width:100%; padding:0.65rem; border:1px solid #e6e6f0; border-radius:8px;" required>
                        <option value="">-- Pilih Mata Pelajaran --</option>
                        @foreach ($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="flex:1">
                    <label style="font-weight:700; display:block; margin-bottom:0.5rem">Pilih Modul</label>
                    <select id="module-select" name="module_id"
                        style="width:100%; padding:0.65rem; border:1px solid #e6e6f0; border-radius:8px;" required disabled>
                        <option value="">-- Pilih Modul --</option>
                    </select>
                </div>
            </div>

            <div style="margin-top:1rem; display:flex; gap:1.5rem;">
                <div style="flex:1">
                    <label style="font-weight:700; display:block; margin-bottom:0.5rem">Kelas</label>
                    <input type="text" name="class"
                        style="width:100%; padding:0.65rem; border:1px solid #e6e6f0; border-radius:8px;"
                        placeholder="Contoh: VII-A, VII-B, VIII-A">
                </div>

                <div style="flex:1">
                    <label style="font-weight:700; display:block; margin-bottom:0.5rem">Tipe Soal</label>
                    <select name="type" id="question-type"
                        style="width:100%; padding:0.65rem; border:1px solid #e6e6f0; border-radius:8px;" required>
                        <option value="">-- Pilih Tipe --</option>
                        <option value="multiple_choice">Pilihan Ganda</option>
                        <option value="essay">Essay</option>
                        <option value="mixed">Essay & Pilihan Ganda</option>
                        <option value="true_false">Benar / Salah</option>
                    </select>
                </div>

                <div style="flex:1">
                    <label style="font-weight:700; display:block; margin-bottom:0.5rem">Poin</label>
                    <input type="number" name="points" value="10" min="0"
                        style="width:100%; padding:0.65rem; border:1px solid #e6e6f0; border-radius:8px;" required>
                </div>
            </div>

            <div style="margin-top:1rem;">
                <label style="font-weight:700; display:block; margin-bottom:0.5rem">Pertanyaan</label>
                <textarea name="question" id="question-text"
                    style="width:100%; padding:0.75rem; border:1px solid #e6e6f0; border-radius:8px; min-height:120px;" required></textarea>
            </div>

            <!-- Multiple choice options -->
            <div id="mc-options" style="margin-top:1rem; display:none;">
                <label style="font-weight:700; display:block; margin-bottom:0.5rem">Pilihan Jawaban (Pilihan Ganda)</label>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.5rem">
                    <input type="text" name="options[0]" placeholder="Opsi A"
                        style="padding:0.6rem; border:1px solid #e6e6f0; border-radius:6px;">
                    <input type="text" name="options[1]" placeholder="Opsi B"
                        style="padding:0.6rem; border:1px solid #e6e6f0; border-radius:6px;">
                    <input type="text" name="options[2]" placeholder="Opsi C"
                        style="padding:0.6rem; border:1px solid #e6e6f0; border-radius:6px;">
                    <input type="text" name="options[3]" placeholder="Opsi D"
                        style="padding:0.6rem; border:1px solid #e6e6f0; border-radius:6px;">
                </div>

                <div style="margin-top:0.75rem;">
                    <label style="font-weight:700; display:block; margin-bottom:0.5rem">Jawaban Benar (Pilihan
                        Ganda)</label>
                    <select name="correct_answer_mc" id="correct-answer-mc"
                        style="padding:0.6rem; border:1px solid #e6e6f0; border-radius:6px; width:200px;">
                        <option value="">-- Pilih Jawaban --</option>
                        <option value="0">Opsi A</option>
                        <option value="1">Opsi B</option>
                        <option value="2">Opsi C</option>
                        <option value="3">Opsi D</option>
                    </select>
                </div>
            </div>

            <!-- True/False -->
            <div id="tf-options" style="margin-top:1rem; display:none;">
                <label style="font-weight:700; display:block; margin-bottom:0.5rem">Jawaban Benar (Benar / Salah)</label>
                <select name="correct_answer_tf"
                    style="padding:0.6rem; border:1px solid #e6e6f0; border-radius:6px; width:200px;">
                    <option value="true">Benar</option>
                    <option value="false">Salah</option>
                </select>
            </div>

            <div style="margin-top:1.25rem; display:flex; gap:1rem;">
                <button type="submit"
                    style="padding:0.7rem 1.5rem; background:linear-gradient(135deg,var(--primary-color),var(--primary-dark)); color:#fff; border:none; border-radius:8px; font-weight:700;">Simpan</button>
                <a href="{{ route('guru.dashboard') }}"
                    style="padding:0.7rem 1.5rem; background:#f3f4f6; color:#333; border-radius:8px; text-decoration:none; display:inline-flex; align-items:center;">Batal</a>
            </div>
        </form>
    </div>

    <script>
        const modules = {!! $modules->toJson() !!};

        document.getElementById('subject-select').addEventListener('change', function(e) {
            const subjectId = parseInt(e.target.value || 0);
            const moduleSelect = document.getElementById('module-select');
            moduleSelect.innerHTML = '<option value="">-- Pilih Modul --</option>';
            if (!subjectId) {
                moduleSelect.disabled = true;
                return;
            }
            const filtered = modules.filter(m => m.subject_id === subjectId);
            filtered.forEach(m => {
                const opt = document.createElement('option');
                opt.value = m.id;
                opt.textContent = m.name;
                moduleSelect.appendChild(opt);
            });
            moduleSelect.disabled = false;
        });

        function updateVisibility() {
            const type = document.getElementById('question-type').value;
            document.getElementById('mc-options').style.display = (type === 'multiple_choice' || type === 'mixed') ?
                'block' : 'none';
            document.getElementById('tf-options').style.display = (type === 'true_false') ? 'block' : 'none';
        }

        document.getElementById('question-type').addEventListener('change', updateVisibility);
        updateVisibility();
    </script>
@endsection
