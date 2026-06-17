@extends('layouts.app')

@section('title', 'Lihat Siswa')

@section('content')
    <div style="background: white; border-radius: 15px; padding: 2rem; box-shadow: 0 10px 30px rgba(17, 24, 68, 0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h1 style="color: var(--primary-color); margin: 0;"><i class="fas fa-user"></i> Detail Siswa</h1>
            <a href="{{ route('guru.dashboard') }}?tab=students"
                style="padding: 0.6rem 1.25rem; background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)); color: white; border-radius: 10px; font-weight: 700; text-decoration: none;">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <div style="background: #f8f9ff; padding: 2rem; border-radius: 15px;">
            <div style="display: grid; gap: 1.25rem;">
                <div>
                    <label style="color: #999; font-size: 0.9rem; font-weight: 600;">Nama Siswa</label>
                    <p style="margin: 0.5rem 0 0 0; color: #2d3748; font-weight: 700; font-size: 1.1rem;">
                        {{ $student->name }}</p>
                </div>

                <div style="display:flex; gap:2rem;">
                    <div>
                        <label style="color: #999; font-size: 0.9rem; font-weight: 600;">NISN</label>
                        <p style="margin: 0.5rem 0 0 0; color: #2d3748;">{{ $student->nisn ?? '-' }}</p>
                    </div>
                    <div>
                        <label style="color: #999; font-size: 0.9rem; font-weight: 600;">Email</label>
                        <p style="margin: 0.5rem 0 0 0; color: #2d3748;">{{ $student->email }}</p>
                    </div>

                    <div>
                        <label style="color: #999; font-size: 0.9rem; font-weight: 600;">No. HP</label>
                        <p style="margin: 0.5rem 0 0 0; color: #2d3748;">{{ $student->phone ?? '-' }}</p>
                    </div>
                </div>

                <div style="display:flex; gap:2rem;">
                    <div>
                        <label style="color: #999; font-size: 0.9rem; font-weight: 600;">Kelas</label>
                        <p style="margin: 0.5rem 0 0 0; color: #2d3748;">{{ $student->class ?? '-' }}</p>
                    </div>

                    <div>
                        <label style="color: #999; font-size: 0.9rem; font-weight: 600;">Wali Kelas</label>
                        <p style="margin: 0.5rem 0 0 0; color: #2d3748;">{{ $student->homeroom_teacher ?? '-' }}</p>
                    </div>
                </div>

                <div>
                    <label style="color: #999; font-size: 0.9rem; font-weight: 600;">Tanggal Lahir</label>
                    <p style="margin: 0.5rem 0 0 0; color: #2d3748;">
                        {{ optional($student->date_of_birth)->format('d F Y') ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
