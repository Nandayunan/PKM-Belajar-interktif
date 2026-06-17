@extends('layouts.app')

@section('title', 'Daftar Mata Pelajaran')

@section('content')
    <div style="max-width: 800px; margin: 3rem auto; padding: 2rem; background: white; border-radius: 20px; box-shadow: var(--card-shadow);">
        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem;">
            <div style="font-size: 2.5rem; color: var(--primary-color);">
                <i class="fas fa-book"></i>
            </div>
            <div>
                <h1 style="margin: 0;">Daftar Mata Pelajaran</h1>
                <p style="margin: 0.5rem 0 0 0; color: #555;">Untuk membuka modul dan kuis, Anda harus mendaftar dulu ke mata pelajaran ini.</p>
            </div>
        </div>

        <div style="display: grid; gap: 1.5rem;">
            <div style="padding: 1.5rem; border-radius: 15px; background: #f8f9ff;">
                <h2 style="margin: 0 0 0.5rem 0; color: #1f2937;">{{ $subject->name }}</h2>
                <p style="margin: 0; color: #4b5563;">{{ $subject->description }}</p>
            </div>

            <div style="display: flex; gap: 1rem; align-items: center;">
                    <form method="POST" action="{{ route('siswa.subjects.enroll', $subject->id) }}" style="width:100%;">
                        @csrf
                        @if($subject->access_code)
                            <div style="margin-bottom:0.75rem;">
                                <label style="font-weight:700; display:block; color:#374151; margin-bottom:0.25rem;">Kode Akses</label>
                                <input type="text" name="access_code" placeholder="Masukkan kode akses dari guru" style="width:100%; padding:0.5rem; border:2px solid #e5e7eb; border-radius:8px;">
                            </div>
                        @endif
                        <button type="submit" class="btn-modules" style="width:100%; padding: 1rem; font-size: 1rem;">
                            <i class="fas fa-sign-in-alt"></i> Daftar Sekarang
                        </button>
                    </form>
                <a href="{{ route('siswa.dashboard') }}" class="btn-modules" style="background: #f3f4f6; color: #374151; width: auto; padding: 1rem 1.5rem; text-decoration: none;">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
@endsection
