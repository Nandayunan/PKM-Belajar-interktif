@extends('layouts.app')

@section('title', 'Import Soal')

@section('extra-css')
    <style>
        .import-card {
            max-width: 920px;
            margin: 0 auto;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            background: white;
            display: flex;
            overflow: hidden;
        }

        .import-hero {
            min-width: 260px;
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.98), rgba(79, 70, 229, 0.95));
            color: white;
            padding: 2rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .import-form {
            padding: 2rem;
            flex: 1
        }
    </style>
@endsection

@section('content')
    <div class="import-card card">
        <div class="import-hero">
            <h2>Bulk Import Soal</h2>
            <div style="opacity:0.95;">Upload file Excel atau CSV berformat untuk menambahkan banyak soal sekaligus.</div>
        </div>

        <div class="import-form">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if (session('import_failures'))
                <div class="alert alert-warning">
                    <strong>Beberapa baris gagal diimpor:</strong>
                    <ul>
                        @foreach (session('import_failures') as $f)
                            <li>{{ $f }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('guru.questions.import.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Pilih Modul (opsional)</label>
                    <select name="module_id" class="form-select">
                        <option value="">-- Pilih Modul (atau biarkan kosong untuk gunakan module_id di file) --
                        </option>
                        @foreach ($modules as $m)
                            <option value="{{ $m->id }}">[{{ $m->id }}] {{ $m->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">File (.xlsx, .xls, .csv)</label>
                    <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Petunjuk singkat</label>
                    <div class="form-note">Gunakan template CSV/Excel yang tersedia: <a
                            href="/resources/templates/question-import-template.csv">Download template</a>. Untuk pilihan
                        ganda pisahkan opsi dengan <code>||</code>.</div>
                </div>

                <div class="d-flex" style="gap:0.5rem">
                    <button class="btn btn-primary">Impor Soal</button>
                    <a href="{{ route('guru.questions.create') }}" class="btn btn-outline-primary">Kembali</a>
                </div>
            </form>
        </div>
    </div>

@endsection
