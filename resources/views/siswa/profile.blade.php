@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
    <div style="max-width: 900px; margin: 0 auto; padding: 2rem;">
        <div style="background: rgba(255,255,255,0.96); border-radius: 20px; padding: 2rem; box-shadow: 0 20px 50px rgba(15, 23, 42, 0.08);">
            <div style="display: flex; gap: 2rem; flex-wrap: wrap; align-items: center; margin-bottom: 2rem;">
                <div style="width: 120px; height: 120px; border-radius: 50%; background: linear-gradient(135deg, #4338ca, #6366f1); display: flex; align-items: center; justify-content: center; color: white; font-size: 3rem;">
                    <i class="fas fa-user"></i>
                </div>
                <div>
                    <h1 style="margin: 0; color: #111827; font-size: 2rem; font-weight: 700;">Profil Saya</h1>
                    <p style="margin: 0.75rem 0 0; color: #475569; max-width: 600px;">Perbarui data akun dan kata sandi Anda di halaman ini. Email digunakan sebagai username, jadi tidak bisa diubah.</p>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf

                <div style="display: grid; gap: 1.25rem; margin-bottom: 2rem;">
                    <div style="background: #f8fafc; border-radius: 18px; padding: 1.5rem; border: 1px solid #e2e8f0;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 700; color: #334155;">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" class="form-control @error('name') is-invalid @enderror" placeholder="Nama lengkap Anda" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div style="background: #f8fafc; border-radius: 18px; padding: 1.5rem; border: 1px solid #e2e8f0;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 700; color: #334155;">Email</label>
                        <input type="email" value="{{ auth()->user()->email }}" class="form-control" disabled>
                        <small style="color: #475569; margin-top: 0.5rem; display: block;">Email tidak dapat diubah karena digunakan sebagai username.</small>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.25rem;">
                        <div style="background: #f8fafc; border-radius: 18px; padding: 1.5rem; border: 1px solid #e2e8f0;">
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 700; color: #334155;">Nomor HP</label>
                            <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}" class="form-control @error('phone') is-invalid @enderror" placeholder="Contoh: 081234567890">
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div style="background: #f8fafc; border-radius: 18px; padding: 1.5rem; border: 1px solid #e2e8f0;">
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 700; color: #334155;">Tanggal Lahir</label>
                            <input type="date" name="date_of_birth" value="{{ old('date_of_birth', auth()->user()->date_of_birth?->format('Y-m-d')) }}" class="form-control @error('date_of_birth') is-invalid @enderror">
                            @error('date_of_birth')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <div style="background: #ffffff; border-radius: 20px; padding: 2rem; border: 1px solid #e2e8f0; margin-bottom: 2rem;">
                    <h2 style="margin: 0 0 1rem; color: #111827; font-size: 1.25rem; font-weight: 700;">Ubah Kata Sandi</h2>
                    <p style="margin: 0 0 1.25rem; color: #475569;">Isi hanya jika ingin mengganti password. Biarkan kosong jika tidak ingin mengubahnya.</p>

                    <div style="display: grid; gap: 1.25rem;">
                        <div style="background: #f8fafc; border-radius: 18px; padding: 1.5rem; border: 1px solid #e2e8f0;">
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 700; color: #334155;">Kata Sandi Baru</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Minimal 8 karakter, huruf dan angka">
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div style="background: #f8fafc; border-radius: 18px; padding: 1.5rem; border: 1px solid #e2e8f0;">
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 700; color: #334155;">Konfirmasi Kata Sandi</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi kata sandi baru">
                        </div>
                    </div>
                </div>

                <div style="display: flex; flex-wrap: wrap; gap: 1rem; justify-content: flex-end;">
                    <a href="{{ route('siswa.dashboard') }}" class="btn btn-outline-primary" style="padding: 0.85rem 1.5rem;">Kembali</a>
                    <button type="submit" class="btn btn-primary" style="padding: 0.85rem 1.5rem;">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
