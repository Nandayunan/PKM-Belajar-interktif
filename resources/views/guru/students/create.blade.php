@extends('layouts.app')

@section('title', 'Tambah Siswa')

@section('extra-css')
    <style>
        .form-container {
            max-width: 600px;
            margin: 2rem auto;
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 5px 20px rgba(99, 102, 241, 0.1);
        }

        .form-header {
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid #e5e7eb;
        }

        .form-header h1 {
            color: var(--primary-color);
            font-size: 1.8rem;
            margin: 0;
            margin-bottom: 0.5rem;
        }

        .form-header p {
            color: #666;
            margin: 0;
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 0.5rem;
            display: block;
        }

        .form-control,
        .form-select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.3s;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.1);
            outline: none;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .btn-group {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 2px solid #e5e7eb;
        }

        .btn-submit,
        .btn-cancel {
            flex: 1;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-submit {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            box-shadow: 0 5px 15px rgba(99, 102, 241, 0.3);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.4);
        }

        .btn-cancel {
            background: #f3f4f6;
            color: #666;
        }

        .btn-cancel:hover {
            background: #e5e7eb;
            color: #2d3748;
        }

        .error-text {
            color: #dc2626;
            font-size: 0.85rem;
            margin-top: 0.25rem;
        }

        .required {
            color: #dc2626;
        }

        @media (max-width: 640px) {
            .form-container {
                margin: 1rem;
                padding: 1.5rem;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .btn-group {
                flex-direction: column;
            }
        }
    </style>
@endsection

@section('content')
    <div class="form-container">
        <div class="form-header">
            <h1><i class="fas fa-user-plus"></i> Tambah Siswa Baru</h1>
            <p>Isi formulir di bawah untuk menambahkan siswa baru ke sistem</p>
        </div>

        <form action="{{ route('guru.students.store') }}" method="POST">
            @csrf

            <div class="form-row">
                <div class="form-group">
                    <label for="name">Nama Lengkap <span class="required">*</span></label>
                    <input type="text" id="name" name="name"
                        class="form-control @error('name') is-invalid @enderror"
                        placeholder="Nama lengkap siswa"
                        value="{{ old('name') }}" required>
                    @error('name')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email <span class="required">*</span></label>
                    <input type="email" id="email" name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        placeholder="Email siswa"
                        value="{{ old('email') }}" required>
                    @error('email')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="phone">Nomor HP <span class="required">*</span></label>
                    <input type="tel" id="phone" name="phone"
                        class="form-control @error('phone') is-invalid @enderror"
                        placeholder="Nomor HP (contoh: 081234567890)"
                        value="{{ old('phone') }}" required>
                    @error('phone')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="date_of_birth">Tanggal Lahir <span class="required">*</span></label>
                    <input type="date" id="date_of_birth" name="date_of_birth"
                        class="form-control @error('date_of_birth') is-invalid @enderror"
                        value="{{ old('date_of_birth') }}" required>
                    @error('date_of_birth')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="class">Kelas <span class="required">*</span></label>
                    <input type="text" id="class" name="class"
                        class="form-control @error('class') is-invalid @enderror"
                        placeholder="Contoh: VII-A, VII-B, VIII-A"
                        value="{{ old('class') }}" required>
                    @error('class')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="homeroom_teacher">Wali Kelas <span class="required">*</span></label>
                    <input type="text" id="homeroom_teacher" name="homeroom_teacher"
                        class="form-control @error('homeroom_teacher') is-invalid @enderror"
                        placeholder="Nama wali kelas"
                        value="{{ old('homeroom_teacher') }}" required>
                    @error('homeroom_teacher')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password <span class="required">*</span></label>
                <input type="password" id="password" name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    placeholder="Password minimal 8 karakter dengan angka dan huruf"
                    required>
                @error('password')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="btn-group">
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> Simpan Siswa
                </button>
                <a href="{{ route('guru.dashboard') }}" class="btn-cancel">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </form>
    </div>
@endsection
