@extends('layouts.app')

@section('title', 'Lihat Mata Pelajaran')

@section('extra-css')
    <style>
        .subject-header {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 14px 45px rgba(15, 23, 42, 0.08);
            margin-bottom: 1.75rem;
        }

        .subject-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.65rem 1rem;
            background: rgba(59, 130, 246, 0.12);
            color: #1d4ed8;
            border-radius: 999px;
            font-weight: 700;
            font-size: 0.95rem;
        }

        .module-card {
            background: white;
            border-radius: 18px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
            overflow: hidden;
            margin-bottom: 1.5rem;
            border: 1px solid #e5e7eb;
        }

        .module-card-header {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 1rem;
            padding: 1.5rem 1.75rem;
            background: #f8fafc;
            align-items: center;
        }

        .module-card-header h3 {
            margin: 0;
            font-size: 1.25rem;
            color: #111827;
        }

        .module-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-top: 0.75rem;
        }

        .module-pill {
            display: inline-flex;
            padding: 0.45rem 0.85rem;
            border-radius: 999px;
            font-size: 0.85rem;
            font-weight: 700;
            color: #374151;
            background: #f3f4f6;
        }

        .module-actions {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .module-body {
            padding: 1.5rem 1.75rem 1.2rem;
            color: #4b5563;
        }

        .question-list {
            border-top: 1px solid #e5e7eb;
            padding: 1rem 1.75rem 1.5rem;
            background: #ffffff;
        }

        .question-item {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 1rem;
            padding: 0.9rem 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .question-item:last-child {
            border-bottom: none;
        }

        .question-type {
            display: inline-block;
            padding: 0.25rem 0.6rem;
            border-radius: 999px;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: capitalize;
            background: #eef2ff;
            color: #4338ca;
        }

        .btn-secondary-outline {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.7rem 1rem;
            border-radius: 999px;
            border: 1px solid #d1d5db;
            background: white;
            color: #374151;
            text-decoration: none;
            font-weight: 700;
        }

        .btn-secondary-outline:hover {
            background: #f8fafc;
        }

        @media (max-width: 900px) {
            .module-card-header {
                grid-template-columns: 1fr;
            }

            .module-actions {
                justify-content: flex-start;
            }
        }
    </style>
@endsection

@section('content')
    <div class="subject-header">
        <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:1rem; flex-wrap:wrap;">
            <div style="max-width:720px;">
                <div class="subject-badge">
                    <i class="fas fa-book-open"></i> Mata Pelajaran
                </div>
                <h1 style="margin:1rem 0 0 0; font-size:2rem; color:#111827;">{{ $subject->name }}</h1>
                <p style="margin:1rem 0 0 0; line-height:1.8; color:#4b5563; max-width:760px;">
                    {{ $subject->description ?: 'Belum ada deskripsi untuk mata pelajaran ini.' }}
                </p>
                <div style="display:grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap:1rem; margin-top:1.5rem;">
                    <div style="background:#ffffff; border-radius:16px; padding:1rem 1.2rem; box-shadow: inset 0 0 0 1px rgba(15,23,42,.05);">
                        <div style="font-size:0.85rem; color:#6b7280; font-weight:700;">Modul</div>
                        <div style="font-size:1.55rem; font-weight:800; color:#111827; margin-top:0.55rem;">{{ $totalModules }}</div>
                    </div>
                    <div style="background:#ffffff; border-radius:16px; padding:1rem 1.2rem; box-shadow: inset 0 0 0 1px rgba(15,23,42,.05);">
                        <div style="font-size:0.85rem; color:#6b7280; font-weight:700;">Total Soal</div>
                        <div style="font-size:1.55rem; font-weight:800; color:#111827; margin-top:0.55rem;">{{ $totalQuestions }}</div>
                    </div>
                    <div style="background:#ffffff; border-radius:16px; padding:1rem 1.2rem; box-shadow: inset 0 0 0 1px rgba(15,23,42,.05);">
                        <div style="font-size:0.85rem; color:#6b7280; font-weight:700;">Kelas</div>
                        <div style="font-size:1.55rem; font-weight:800; color:#111827; margin-top:0.55rem;">{{ $subject->class ?: '-' }}</div>
                    </div>
                </div>
            </div>

            <div style="display:flex; gap:0.75rem; flex-wrap:wrap; align-items:center;">
                <a href="{{ route('guru.subjects.edit', $subject->id) }}" class="btn-secondary-outline">
                    <i class="fas fa-edit"></i> Edit Mata Pelajaran
                </a>
                <a href="{{ route('guru.modules.create') }}?subject_id={{ $subject->id }}" class="btn-secondary-outline">
                    <i class="fas fa-plus"></i> Tambah Modul
                </a>
                <form method="POST" action="{{ route('guru.subjects.destroy', $subject->id) }}" onsubmit="return confirm('Hapus mata pelajaran ini beserta semua modul dan soal yang terkait?');" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-secondary-outline" style="color:#b91c1c; border-color:#fca5a5;"> <i class="fas fa-trash"></i> Hapus Mata Pelajaran</button>
                </form>
            </div>
        </div>
    </div>

    @if ($subject->modules->isEmpty())
        <div style="background:white; border-radius:20px; padding:2rem; box-shadow:0 10px 30px rgba(15,23,42,.08); text-align:center;">
            <h2 style="margin:0 0 1rem 0; color:#111827;">Belum ada modul</h2>
            <p style="margin:0 0 1.5rem 0; color:#4b5563;">Tambahkan modul baru untuk mulai memasukkan materi dan soal ke dalam mata pelajaran ini.</p>
            <a href="{{ route('guru.modules.create') }}?subject_id={{ $subject->id }}" class="btn-secondary-outline" style="margin:0 auto;"> <i class="fas fa-plus"></i> Buat Modul Pertama</a>
        </div>
    @else
        <div style="display:grid; gap:1.5rem;">
            @foreach ($subject->modules as $module)
                <div class="module-card">
                    <div class="module-card-header">
                        <div>
                            <div style="display:flex; align-items:center; gap:0.75rem; flex-wrap:wrap;">
                                <span class="module-pill">Modul {{ $module->module_number }}</span>
                                <h3>{{ $module->name }}</h3>
                            </div>
                            <div class="module-meta">
                                <span class="module-pill">{{ $module->questions->count() }} Soal</span>
                                <span class="module-pill">{{ $module->published ? 'Dipublikasi' : 'Draft' }}</span>
                                @if($module->video_url)
                                    <span class="module-pill">Video</span>
                                @endif
                                @if($module->pdf_path)
                                    <span class="module-pill">PDF</span>
                                @endif
                            </div>
                        </div>
                        <div class="module-actions">
                            <a href="{{ route('guru.modules.edit', [$module->subject_id, $module->id]) }}" class="btn-secondary-outline">
                                <i class="fas fa-edit"></i> Edit Modul
                            </a>
                            <form method="POST" action="{{ route('guru.modules.destroy', [$module->subject_id, $module->id]) }}" onsubmit="return confirm('Hapus modul ini beserta semua soal yang terkait?');" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-secondary-outline" style="color:#b91c1c; border-color:#fca5a5;"> <i class="fas fa-trash"></i> Hapus Modul</button>
                            </form>
                            <a href="{{ route('guru.questions.create') }}?module_id={{ $module->id }}" class="btn-secondary-outline">
                                <i class="fas fa-plus"></i> Tambah Soal
                            </a>
                        </div>
                    </div>
                    <div class="module-body">
                        <p style="margin:0;">{{ Str::limit($module->content ?: 'Tidak ada deskripsi modul.', 220) }}</p>
                    </div>
                    <div class="question-list">
                        @if ($module->questions->isEmpty())
                            <div style="color:#6b7280;">Belum ada soal di modul ini. Tambahkan soal untuk mulai melatih siswa.</div>
                        @else
                            <div style="display:grid; gap:0.5rem;">
                                @foreach ($module->questions->take(4) as $question)
                                    <div class="question-item">
                                        <div>
                                            <div style="font-weight:700; color:#111827;">{{ Str::limit($question->question, 90) }}</div>
                                            <div style="font-size:0.9rem; color:#6b7280; margin-top:0.35rem;">Tipe: {{ ucfirst(str_replace('_', ' ', $question->type)) }}</div>
                                        </div>
                                        <div style="display:flex; gap:0.5rem; align-items:center;">
                                            <span class="question-type">{{ str_replace('_', ' ', $question->type) }}</span>
                                            <a href="{{ route('guru.questions.edit', $question->id) }}" class="btn-secondary-outline" style="padding:0.45rem 0.8rem; font-size:0.85rem;">Ubah</a>
                                            <form method="POST" action="{{ route('guru.questions.destroy', $question->id) }}" onsubmit="return confirm('Hapus soal ini?');" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-secondary-outline" style="color:#b91c1c; border-color:#fca5a5; font-size:0.85rem;">Hapus</button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if ($module->questions->count() > 4)
                                <div style="margin-top:1rem; text-align:right;">
                                    <span style="font-size:0.9rem; color:#6b7280;">Menampilkan 4 soal pertama dari {{ $module->questions->count() }}.</span>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
