@extends('layouts.app')

@section('title', 'Buat Modul')

@section('content')
@section('extra-css')
    <style>
        .module-card {
            max-width: 980px;
            margin: 0 auto;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--card-shadow);
            background: white;
            display: flex;
        }

        .module-hero {
            min-width: 280px;
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.98), rgba(79, 70, 229, 0.95));
            color: white;
            padding: 2rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 0.5rem;
        }

        .module-hero h2 {
            font-size: 1.75rem;
            margin: 0;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .module-hero p {
            margin: 0;
            opacity: 0.95;
        }

        .module-form {
            padding: 2rem;
            flex: 1;
        }

        .form-label {
            font-weight: 700;
            color: #2d3748;
        }

        .form-note {
            font-size: 0.9rem;
            color: #6b7280;
        }

        .file-input {
            border: 2px dashed #e5e7eb;
            padding: 0.6rem;
            border-radius: 10px;
            background: #fbfdff;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
            margin-top: 0.5rem;
        }

        .toggle {
            position: relative;
            display: inline-block;
            width: 46px;
            height: 26px;
        }

        .toggle input {
            display: none;
        }

        .toggle .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: #e5e7eb;
            border-radius: 999px;
            transition: 0.25s;
        }

        .toggle .slider:before {
            content: '';
            position: absolute;
            height: 20px;
            width: 20px;
            left: 3px;
            top: 3px;
            background: white;
            border-radius: 50%;
            transition: 0.25s;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.12);
        }

        .toggle input:checked+.slider {
            background: var(--primary-color);
        }

        .toggle input:checked+.slider:before {
            transform: translateX(20px);
        }

        .btn-cancel {
            background: #f3f4f6;
            color: #374151;
            border-radius: 10px;
            padding: 0.6rem 1rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        @media (max-width: 768px) {
            .module-card {
                flex-direction: column;
            }

            .module-hero {
                min-height: 140px;
            }
        }
    </style>
@endsection

<div class="module-card card">
    <div class="module-hero">
        <div class="d-flex align-items-center" style="gap:0.75rem">
            <div
                style="width:56px;height:56px;border-radius:12px;background:rgba(255,255,255,0.12);display:flex;align-items:center;justify-content:center">
                <i class="fas fa-plus" style="font-size:20px"></i>
            </div>
            <div>
                <h2>Buat Modul Baru</h2>
                <p class="mb-0">Buat materi singkat, jelas, dan menarik untuk siswa.</p>
            </div>
        </div>
    </div>

    <div class="module-form">
        <form method="POST" action="{{ route('guru.modules.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label">Pilih Mata Pelajaran</label>
                <select name="subject_id" class="form-select form-select-lg" required>
                    <option value="">-- Pilih Mata Pelajaran --</option>
                    @foreach ($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="row">
                <div class="col-md-8 mb-3">
                    <label class="form-label">Nama Modul</label>
                    <input type="text" name="name" class="form-control form-control-lg" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Nomor Modul</label>
                    <input type="number" name="module_number" class="form-control form-control-lg" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Konten/Materi</label>
                <textarea name="content" class="form-control" rows="7" required></textarea>
                <div class="form-note mt-2">Gunakan heading dan poin agar materi mudah dipahami.</div>
            </div>

            <div class="mb-3">
                <label class="form-label">Link Video YouTube</label>
                <input type="url" name="video_url" class="form-control"
                    placeholder="https://youtube.com/watch?v=...">
            </div>

            <div class="d-flex align-items-center gap-3 mb-3">
                <label class="toggle mb-0">
                    <input type="checkbox" name="published" id="published">
                    <span class="slider"></span>
                </label>
                <label for="published" class="form-label mb-0" style="margin-left:6px;">Publikasikan modul ini</label>
            </div>

            <div class="mb-3">
                <label class="form-label">Upload PDF Materi (opsional)</label>
                <div class="file-input">
                    <input type="file" name="pdf" accept="application/pdf" class="form-control form-control-sm"
                        style="border:none; padding:0;">
                </div>
                <div class="form-note mt-2">File PDF maksimal 10MB. Nama file akan disimpan di server.</div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save"></i>&nbsp; Buat Modul
                </button>

                <a href="{{ route('guru.dashboard') }}" class="btn-cancel">
                    <i class="fas fa-arrow-left"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
