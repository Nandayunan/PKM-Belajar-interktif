@extends('layouts.app')

@section('title', 'Lihat Mata Pelajaran')

@section('content')
    <div style="background: white; border-radius: 15px; padding: 2rem; box-shadow: 0 10px 30px rgba(99, 102, 241, 0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h1 style="color: var(--primary-color); margin: 0;">
                <i class="fas fa-book"></i> Detail Mata Pelajaran
            </h1>
            <a href="{{ route('guru.dashboard') }}"
                style="padding: 0.75rem 1.5rem; background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)); color: white; border-radius: 10px; font-weight: 700; text-decoration: none;">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <div style="background: #f8f9ff; padding: 2rem; border-radius: 15px;">
            <div style="display: grid; gap: 1.5rem;">
                <div>
                    <label style="color: #999; font-size: 0.9rem; font-weight: 600;">Nama Mata Pelajaran</label>
                    <p style="margin: 0.5rem 0 0 0; color: #2d3748; font-weight: 700; font-size: 1.1rem;">Nama Mata
                        Pelajaran</p>
                </div>

                <div>
                    <label style="color: #999; font-size: 0.9rem; font-weight: 600;">Deskripsi</label>
                    <p style="margin: 0.5rem 0 0 0; color: #2d3748;">Deskripsi mata pelajaran akan ditampilkan di sini</p>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div>
                        <label style="color: #999; font-size: 0.9rem; font-weight: 600;">Total Modul</label>
                        <p style="margin: 0.5rem 0 0 0; color: #2d3748; font-weight: 700; font-size: 1.5rem;">0</p>
                    </div>

                    <div>
                        <label style="color: #999; font-size: 0.9rem; font-weight: 600;">Total Soal</label>
                        <p style="margin: 0.5rem 0 0 0; color: #2d3748; font-weight: 700; font-size: 1.5rem;">0</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
