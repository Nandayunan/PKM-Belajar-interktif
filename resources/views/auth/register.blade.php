@extends('layouts.app')

@section('title', 'Register')

@section('extra-css')
    <style>
        .auth-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 2rem;
        }

        .auth-card {
            width: 100%;
            max-width: 450px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(99, 102, 241, 0.3);
            padding: 3rem;
            animation: slideUp 0.5s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .auth-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .auth-header h1 {
            color: var(--primary-color);
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .auth-header p {
            color: #999;
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

        .form-control {
            height: 45px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.1);
        }

        .form-select {
            height: 45px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
        }

        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.1);
        }

        .btn-auth {
            width: 100%;
            height: 45px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border: none;
            border-radius: 10px;
            color: white;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s;
            box-shadow: 0 5px 15px rgba(99, 102, 241, 0.3);
        }

        .btn-auth:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.4);
            color: white;
        }

        .auth-footer {
            text-align: center;
            margin-top: 1.5rem;
            color: #666;
        }

        .auth-footer a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }

        .auth-footer a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        .icon-input {
            position: relative;
        }

        .icon-input i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-color);
        }

        .icon-input .form-control,
        .icon-input .form-select {
            padding-left: 45px;
        }

        .role-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-top: 0.5rem;
        }

        .role-badge.guru {
            background: #fef3c7;
            color: #92400e;
        }

        .role-badge.siswa {
            background: #dbeafe;
            color: #0c2340;
        }

        @media (max-width: 576px) {
            .auth-card {
                padding: 2rem 1.5rem;
            }

            .auth-header h1 {
                font-size: 1.5rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h1><i class="fas fa-user-plus"></i></h1>
                <h1>Daftar</h1>
                <p>Buat akun baru untuk memulai pembelajaran</p>
            </div>

            <form action="{{ route('register') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="name">Nama Lengkap</label>
                    <div class="icon-input">
                        <i class="fas fa-user"></i>
                        <input type="text" id="name" name="name"
                            class="form-control @error('name') is-invalid @enderror" placeholder="Masukkan nama lengkap"
                            value="{{ old('name') }}" required>
                    </div>
                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <div class="icon-input">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email"
                            class="form-control @error('email') is-invalid @enderror" placeholder="Masukkan email Anda"
                            value="{{ old('email') }}" required>
                    </div>
                    @error('email')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="role">Daftar Sebagai</label>
                    <div class="icon-input">
                        <i class="fas fa-badge"></i>
                        <select id="role" name="role" class="form-select @error('role') is-invalid @enderror"
                            required onchange="updateRoleBadge()">
                            <option value="">-- Pilih Role --</option>
                            <option value="0">Siswa (Pelajar)</option>
                            <option value="1">Guru (Pendidik)</option>
                        </select>
                    </div>
                    <div id="roleBadge" style="display: none;">
                        <span class="role-badge siswa" id="badgeText"></span>
                    </div>
                    @error('role')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="icon-input">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Buat password yang kuat" required>
                    </div>
                    @error('password')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <div class="icon-input">
                        <i class="fas fa-check"></i>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control"
                            placeholder="Ulangi password Anda" required>
                    </div>
                </div>

                <button type="submit" class="btn-auth">
                    <i class="fas fa-user-plus"></i> Daftar Sekarang
                </button>
            </form>

            <div class="auth-footer">
                <p>Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a></p>
            </div>
        </div>
    </div>

    <script>
        function updateRoleBadge() {
            const roleSelect = document.getElementById('role');
            const badgeDiv = document.getElementById('roleBadge');
            const badgeText = document.getElementById('badgeText');

            if (roleSelect.value) {
                if (roleSelect.value === '0') {
                    badgeText.textContent = '🎓 Siswa (Pelajar)';
                    badgeText.className = 'role-badge siswa';
                } else {
                    badgeText.textContent = '👨‍🏫 Guru (Pendidik)';
                    badgeText.className = 'role-badge guru';
                }
                badgeDiv.style.display = 'block';
            } else {
                badgeDiv.style.display = 'none';
            }
        }
    </script>
@endsection
