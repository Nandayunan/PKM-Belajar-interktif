@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
    <div style="max-width: 600px; margin: 0 auto;">
        <div
            style="background: white; border-radius: 15px; padding: 2rem; box-shadow: 0 10px 30px rgba(99, 102, 241, 0.1); margin-bottom: 2rem;">
            <h1 style="color: var(--primary-color); margin-bottom: 2rem; display: flex; align-items: center; gap: 1rem;">
                <i class="fas fa-user-circle"></i> Profil Saya
            </h1>

            <div style="display: flex; align-items: center; gap: 2rem; margin-bottom: 2rem;">
                <div
                    style="width: 120px; height: 120px; border-radius: 50%; background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)); display: flex; align-items: center; justify-content: center; color: white; font-size: 3rem;">
                    <i class="fas fa-user"></i>
                </div>
                <div>
                    <h2 style="margin: 0; color: #2d3748; font-weight: 700;">{{ auth()->user()->name }}</h2>
                    <p style="margin: 0.5rem 0 0 0; color: #666;">
                        <i class="fas fa-envelope"></i> {{ auth()->user()->email }}
                    </p>
                    <p style="margin: 0.5rem 0 0 0; color: #666;">
                        <i class="fas fa-badge"></i>
                        @if (auth()->user()->isStudent())
                            <span
                                style="background: #dbeafe; color: var(--info-color); padding: 0.3rem 0.8rem; border-radius: 20px; font-weight: 600;">Siswa</span>
                        @else
                            <span
                                style="background: #fef3c7; color: #92400e; padding: 0.3rem 0.8rem; border-radius: 20px; font-weight: 600;">Guru</span>
                        @endif
                    </p>
                </div>
            </div>

            <div style="border-top: 1px solid #e5e7eb; padding-top: 2rem;">
                <h3 style="color: #2d3748; font-weight: 700; margin-bottom: 1rem;">Informasi Akun</h3>

                <div style="display: grid; gap: 1rem;">
                    <div style="padding: 1rem; background: #f8f9ff; border-radius: 10px;">
                        <label style="color: #999; font-size: 0.85rem; font-weight: 600;">Nama Lengkap</label>
                        <p style="margin: 0.5rem 0 0 0; color: #2d3748; font-weight: 600;">{{ auth()->user()->name }}</p>
                    </div>

                    <div style="padding: 1rem; background: #f8f9ff; border-radius: 10px;">
                        <label style="color: #999; font-size: 0.85rem; font-weight: 600;">Email</label>
                        <p style="margin: 0.5rem 0 0 0; color: #2d3748; font-weight: 600;">{{ auth()->user()->email }}</p>
                    </div>

                    <div style="padding: 1rem; background: #f8f9ff; border-radius: 10px;">
                        <label style="color: #999; font-size: 0.85rem; font-weight: 600;">Bergabung Sejak</label>
                        <p style="margin: 0.5rem 0 0 0; color: #2d3748; font-weight: 600;">
                            {{ auth()->user()->created_at->format('d F Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 2rem; flex-wrap: wrap;">
                <a href="{{ auth()->user()->isStudent() ? route('siswa.dashboard') : route('guru.dashboard') }}"
                    style="padding: 0.75rem 2rem; background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)); color: white; border-radius: 10px; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>
@endsection
