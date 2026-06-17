@extends('layouts.app')

@section('title', 'Buat Mata Pelajaran')

@section('content')
    <div
        style="background: white; border-radius: 15px; padding: 2rem; box-shadow: 0 10px 30px rgba(17, 24, 68, 0.1); max-width: 600px; margin: 0 auto;">
        <h1 style="color: var(--primary-color); margin-bottom: 2rem;">
            <i class="fas fa-plus"></i> Buat Mata Pelajaran
        </h1>

        <form method="POST" action="{{ route('guru.subjects.store') }}">
            @csrf

            <div style="margin-bottom: 1.5rem;">
                <label style="font-weight: 700; color: #2d3748; display: block; margin-bottom: 0.5rem;">Nama Mata
                    Pelajaran</label>
                <input type="text" name="name"
                    style="width: 100%; padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 10px; font-family: 'Poppins', sans-serif;"
                    required>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="font-weight: 700; color: #2d3748; display: block; margin-bottom: 0.5rem;">Deskripsi</label>
                <textarea name="description"
                    style="width: 100%; padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 10px; font-family: 'Poppins', sans-serif; min-height: 100px;"
                    required></textarea>
            </div>

            <!-- Icon and color fields removed per design request -->

            <div style="margin-bottom: 1.5rem;">
                <label style="font-weight: 700; color: #2d3748; display: block; margin-bottom: 0.5rem;">Kelas</label>
                <select id="subject-grade" name="grade" required
                    style="width: 100%; padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 10px; font-family: 'Poppins', sans-serif; background:white;">
                    <option value="">Pilih kelas</option>
                    <option value="VII" {{ old('grade') == 'VII' ? 'selected' : '' }}>VII</option>
                    <option value="VIII" {{ old('grade') == 'VIII' ? 'selected' : '' }}>VIII</option>
                    <option value="IX" {{ old('grade') == 'IX' ? 'selected' : '' }}>IX</option>
                </select>
                @error('grade')
                    <div style="color:#dc2626; margin-top:0.5rem;">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="font-weight: 700; color: #2d3748; display: block; margin-bottom: 0.5rem;">Subkelas</label>
                <div style="display:flex; flex-wrap:wrap; gap:0.75rem;">
                    @foreach(['A','B','C','D'] as $section)
                        <label style="display:inline-flex; align-items:center; gap:0.5rem;">
                            <input type="checkbox" name="sections[]" value="{{ $section }}"
                                {{ in_array($section, old('sections', [])) ? 'checked' : '' }}
                                onchange="syncSubjectClass();" />
                            <span>{{ $section }}</span>
                        </label>
                    @endforeach
                    <label style="display:inline-flex; align-items:center; gap:0.5rem; margin-left:1rem;">
                        <input type="checkbox" id="subject-all-classes" name="all_sections" value="1"
                            {{ old('all_sections') ? 'checked' : '' }} onchange="syncSubjectClass(); toggleSectionInputs();" />
                        <span>Semua kelas</span>
                    </label>
                </div>
                <small style="color:#6b7280; display:block; margin-top:0.75rem;">Pilih satu atau beberapa subkelas, atau centang Semua kelas.</small>
                @error('sections')
                    <div style="color:#dc2626; margin-top:0.5rem;">{{ $message }}</div>
                @enderror
                @error('all_sections')
                    <div style="color:#dc2626; margin-top:0.5rem;">{{ $message }}</div>
                @enderror
            </div>

            <input type="hidden" name="class" id="subject-class-hidden" value="{{ old('class') }}">

            <div style="margin-bottom: 1.5rem;">
                <label style="font-weight: 700; color: #2d3748; display: block; margin-bottom: 0.5rem;">Kode Akses (opsional)</label>
                <input type="text" name="access_code" placeholder="Contoh: ABC123 (opsional)"
                    style="width: 100%; padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 10px; font-family: 'Poppins', sans-serif;"
                    value="{{ old('access_code') }}">
                <small style="color:#6b7280;">Jika diisi, siswa harus memasukkan kode ini untuk mendaftar.</small>
            </div>

            <div style="display: flex; gap: 1rem;">
                <button type="submit"
                    style="padding: 0.75rem 2rem; background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)); color: white; border: none; border-radius: 10px; font-weight: 700; cursor: pointer; transition: all 0.3s;">
                    <i class="fas fa-save"></i> Buat Mata Pelajaran
                </button>
                <a href="{{ route('guru.dashboard') }}"
                    style="padding: 0.75rem 2rem; background: #f3f4f6; color: #666; border-radius: 10px; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-arrow-left"></i> Batal
                </a>
            </div>
        </form>
    </div>

    <script>
        function syncSubjectClass() {
            const grade = document.getElementById('subject-grade').value;
            const hidden = document.getElementById('subject-class-hidden');
            const allClasses = document.getElementById('subject-all-classes').checked;
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
            const allClasses = document.getElementById('subject-all-classes').checked;
            document.querySelectorAll('input[name="sections[]"]').forEach(input => {
                input.disabled = allClasses;
                if (allClasses) input.checked = false;
            });
            syncSubjectClass();
        }

        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('subject-grade').addEventListener('change', syncSubjectClass);
            document.getElementById('subject-all-classes').addEventListener('change', toggleSectionInputs);
            document.querySelectorAll('input[name="sections[]"]').forEach(input => {
                input.addEventListener('change', syncSubjectClass);
            });
            toggleSectionInputs();
        });
    </script>
@endsection
