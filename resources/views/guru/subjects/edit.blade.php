@extends('layouts.app')

@section('title', 'Edit Mata Pelajaran')

@section('content')
    @php
        $subjectGrade = null;
        $subjectSections = [];
        $subjectAll = false;

        if (!empty($subject->class)) {
            $classParts = explode('-', $subject->class, 2);
            $subjectGrade = $classParts[0] ?? null;
            if (isset($classParts[1])) {
                if (strtoupper($classParts[1]) === 'ALL') {
                    $subjectAll = true;
                } else {
                    $subjectSections = explode(',', strtoupper($classParts[1]));
                }
            }
        }
    @endphp

    <div
        style="background: white; border-radius: 15px; padding: 2rem; box-shadow: 0 10px 30px rgba(17, 24, 68, 0.1); max-width: 600px; margin: 0 auto;">
        <h1 style="color: var(--primary-color); margin-bottom: 2rem;">
            <i class="fas fa-edit"></i> Edit Mata Pelajaran
        </h1>

        <form method="POST" action="{{ route('guru.subjects.update', $subject->id) }}">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 1.5rem;">
                <label style="font-weight: 700; color: #2d3748; display: block; margin-bottom: 0.5rem;">Nama Mata
                    Pelajaran</label>
                <input type="text" name="name" value="{{ old('name', $subject->name) }}"
                    style="width: 100%; padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 10px; font-family: 'Poppins', sans-serif;"
                    required>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="font-weight: 700; color: #2d3748; display: block; margin-bottom: 0.5rem;">Deskripsi</label>
                <textarea name="description"
                    style="width: 100%; padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 10px; font-family: 'Poppins', sans-serif; min-height: 100px;"
                    required>{{ old('description', $subject->description) }}</textarea>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="font-weight: 700; color: #2d3748; display: block; margin-bottom: 0.5rem;">Kelas</label>
                <select id="subject-grade" name="grade" required
                    style="width: 100%; padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 10px; font-family: 'Poppins', sans-serif; background:white;">
                    <option value="">Pilih kelas</option>
                    @if(!empty($classesByGrade) && is_array($classesByGrade))
                        @foreach(array_keys($classesByGrade) as $gradeOpt)
                            <option value="{{ $gradeOpt }}" {{ old('grade', $subjectGrade) == $gradeOpt ? 'selected' : '' }}>{{ $gradeOpt }}</option>
                        @endforeach
                    @else
                        <option value="VII" {{ old('grade', $subjectGrade) == 'VII' ? 'selected' : '' }}>VII</option>
                        <option value="VIII" {{ old('grade', $subjectGrade) == 'VIII' ? 'selected' : '' }}>VIII</option>
                        <option value="IX" {{ old('grade', $subjectGrade) == 'IX' ? 'selected' : '' }}>IX</option>
                    @endif
                </select>
                @error('grade')
                    <div style="color:#dc2626; margin-top:0.5rem;">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="font-weight: 700; color: #2d3748; display: block; margin-bottom: 0.5rem;">Subkelas</label>
                <div id="subject-sections-container" style="display:flex; flex-wrap:wrap; gap:0.75rem;">
                    <!-- dynamic sections populated by JS -->
                </div>
                <small style="color:#6b7280; display:block; margin-top:0.75rem;">Pilih satu atau beberapa subkelas, atau centang Semua kelas.</small>
                @error('sections')
                    <div style="color:#dc2626; margin-top:0.5rem;">{{ $message }}</div>
                @enderror
                @error('all_sections')
                    <div style="color:#dc2626; margin-top:0.5rem;">{{ $message }}</div>
                @enderror
            </div>

            <input type="hidden" name="class" id="subject-class-hidden" value="{{ old('class', $subject->class) }}">

            <!-- access_code removed from edit form -->

            <div style="display: flex; gap: 1rem;">
                <button type="submit"
                    style="padding: 0.75rem 2rem; background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)); color: white; border: none; border-radius: 10px; font-weight: 700; cursor: pointer; transition: all 0.3s;">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
                <a href="{{ route('guru.dashboard') }}"
                    style="padding: 0.75rem 2rem; background: #f3f4f6; color: #666; border-radius: 10px; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-arrow-left"></i> Batal
                </a>
            </div>
        </form>
    </div>

    <script>
        const classesByGrade = @json($classesByGrade ?? []);
        const initialSubjectSections = @json($subjectSections ?? []);
        const initialSubjectAll = {{ $subjectAll ? 'true' : 'false' }};

        function renderSectionsForGrade(grade) {
            const container = document.getElementById('subject-sections-container');
            container.innerHTML = '';

            const allLabel = document.createElement('label');
            allLabel.style = 'display:inline-flex; align-items:center; gap:0.5rem; margin-left:1rem;';
            allLabel.innerHTML = `<input type="checkbox" id="subject-all-classes" name="all_sections" value="1"> <span>Semua kelas</span>`;
            container.appendChild(allLabel);

            let sections = [];
            if (grade && classesByGrade && classesByGrade[grade]) {
                sections = classesByGrade[grade].map(c => {
                    const parts = String(c).split('-');
                    return parts[1] || c;
                }).filter((v, i, a) => a.indexOf(v) === i);
            }
            if (sections.length === 0) sections = ['A','B','C','D'];

            sections.forEach(section => {
                const lbl = document.createElement('label');
                lbl.style = 'display:inline-flex; align-items:center; gap:0.5rem;';
                const checked = initialSubjectSections.includes(section) || (Array.isArray(@json(old('sections', []))) && @json(old('sections', [])).includes(section));
                lbl.innerHTML = `<input type="checkbox" name="sections[]" value="${section}" ${checked ? 'checked' : ''}> <span>${section}</span>`;
                container.appendChild(lbl);
            });

            try {
                const oldAll = {{ old('all_sections', $subjectAll ? '1' : 0) ? 'true' : 'false' }};
                const allEl = document.getElementById('subject-all-classes');
                if (allEl) allEl.checked = oldAll || initialSubjectAll;
            } catch (e) {}

            document.querySelectorAll('input[name="sections[]"]').forEach(input => input.addEventListener('change', syncSubjectClass));
            const allEl = document.getElementById('subject-all-classes');
            if (allEl) allEl.addEventListener('change', function() { toggleSectionInputs(); syncSubjectClass(); });
        }

        function syncSubjectClass() {
            const grade = document.getElementById('subject-grade').value;
            const hidden = document.getElementById('subject-class-hidden');
            const allEl = document.getElementById('subject-all-classes');
            const allClasses = allEl ? allEl.checked : false;
            const sectionInputs = Array.from(document.querySelectorAll('input[name="sections[]"]'));
            const selectedSections = sectionInputs
                .filter(input => input.checked)
                .map(input => input.value)
                .filter(Boolean);

            if (!grade) {
                hidden.value = '';
                return;
            }

            if (allClasses) {
                hidden.value = grade + '-ALL';
                return;
            }

            if (selectedSections.length > 0) {
                hidden.value = grade + '-' + selectedSections.join(',');
                return;
            }

            hidden.value = grade;
        }

        function toggleSectionInputs() {
            const allClasses = document.getElementById('subject-all-classes')?.checked;
            document.querySelectorAll('input[name="sections[]"]').forEach(input => {
                input.disabled = allClasses;
                if (allClasses) input.checked = false;
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            const gradeSel = document.getElementById('subject-grade');
            const initialGrade = gradeSel.value || '';
            renderSectionsForGrade(initialGrade);

            gradeSel.addEventListener('change', function() {
                renderSectionsForGrade(this.value);
                syncSubjectClass();
            });

            syncSubjectClass();
        });
    </script>
@endsection
