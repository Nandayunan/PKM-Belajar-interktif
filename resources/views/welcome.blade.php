@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    <div style="min-height: 100vh; padding: 2rem 1rem;">
        <!-- Hero Section -->
        <div
            style="background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)); color: white; border-radius: 20px; padding: 3rem 2rem; margin-bottom: 3rem; text-align: center; box-shadow: var(--card-shadow);">
            <h1 style="font-size: 2.5rem; margin: 0; margin-bottom: 1rem; font-weight: 700;">
                <i class="fas fa-graduation-cap"></i> Selamat Datang di SMP 40 Bandung
            </h1>
            <p style="font-size: 1.1rem; margin: 0; opacity: 0.95; max-width: 600px; margin: 0 auto;">
                Platform Pembelajaran Digital Interaktif untuk Semua Siswa dan Guru
            </p>
        </div>

        <!-- Stats Section -->
        <div
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 3rem;">
            <div
                style="background: white; padding: 1.5rem; border-radius: 15px; text-align: center; box-shadow: var(--card-shadow);">
                <div style="font-size: 2.5rem; color: var(--primary-color); margin-bottom: 0.5rem; font-weight: 700;">6</div>
                <div style="color: #666; font-weight: 600;">Mata Pelajaran</div>
                <div style="color: #999; font-size: 0.9rem; margin-top: 0.5rem;">Tersedia untuk dipelajari</div>
            </div>
            <div
                style="background: white; padding: 1.5rem; border-radius: 15px; text-align: center; box-shadow: var(--card-shadow);">
                <div style="font-size: 2.5rem; color: var(--success-color); margin-bottom: 0.5rem; font-weight: 700;">9
                </div>
                <div style="color: #666; font-weight: 600;">Modul Pembelajaran</div>
                <div style="color: #999; font-size: 0.9rem; margin-top: 0.5rem;">Dengan konten berkualitas</div>
            </div>
            <div
                style="background: white; padding: 1.5rem; border-radius: 15px; text-align: center; box-shadow: var(--card-shadow);">
                <div style="font-size: 2.5rem; color: var(--info-color); margin-bottom: 0.5rem; font-weight: 700;">100+
                </div>
                <div style="color: #666; font-weight: 600;">Soal Interaktif</div>
                <div style="color: #999; font-size: 0.9rem; margin-top: 0.5rem;">Untuk menguji pemahaman</div>
            </div>
        </div>

        <!-- Features Section -->
        <div
            style="background: white; border-radius: 15px; padding: 2rem; margin-bottom: 3rem; box-shadow: var(--card-shadow);">
            <h2 style="color: var(--primary-color); margin-top: 0; margin-bottom: 2rem; text-align: center;">
                <i class="fas fa-star"></i> Fitur Unggulan
            </h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem;">
                <div style="text-align: center;">
                    <div style="font-size: 2rem; color: var(--primary-color); margin-bottom: 1rem;">
                        <i class="fas fa-video"></i>
                    </div>
                    <h3 style="color: #2d3748; margin: 0 0 0.5rem 0;">Video Pembelajaran</h3>
                    <p style="color: #666; margin: 0; font-size: 0.95rem;">Video edukatif berkualitas tinggi untuk setiap
                        modul</p>
                </div>
                <div style="text-align: center;">
                    <div style="font-size: 2rem; color: var(--success-color); margin-bottom: 1rem;">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 style="color: #2d3748; margin: 0 0 0.5rem 0;">Pelacakan Progres</h3>
                    <p style="color: #666; margin: 0; font-size: 0.95rem;">Pantau kemajuan belajar secara real-time</p>
                </div>
                <div style="text-align: center;">
                    <div style="font-size: 2rem; color: var(--info-color); margin-bottom: 1rem;">
                        <i class="fas fa-question-circle"></i>
                    </div>
                    <h3 style="color: #2d3748; margin: 0 0 0.5rem 0;">Soal Variatif</h3>
                    <p style="color: #666; margin: 0; font-size: 0.95rem;">Pilihan ganda, benar-salah, dan essay</p>
                </div>
                <div style="text-align: center;">
                    <div style="font-size: 2rem; color: var(--warning-color); margin-bottom: 1rem;">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <h3 style="color: #2d3748; margin: 0 0 0.5rem 0;">Bimbingan Guru</h3>
                    <p style="color: #666; margin: 0; font-size: 0.95rem;">Dukungan langsung dari guru berpengalaman</p>
                </div>
                <div style="text-align: center;">
                    <div style="font-size: 2rem; color: var(--danger-color); margin-bottom: 1rem;">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3 style="color: #2d3748; margin: 0 0 0.5rem 0;">Responsif</h3>
                    <p style="color: #666; margin: 0; font-size: 0.95rem;">Akses dari desktop, tablet, atau smartphone</p>
                </div>
                <div style="text-align: center;">
                    <div style="font-size: 2rem; color: #8b5cf6; margin-bottom: 1rem;">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <h3 style="color: #2d3748; margin: 0 0 0.5rem 0;">Gamifikasi</h3>
                    <p style="color: #666; margin: 0; font-size: 0.95rem;">Sistem poin dan pencapaian untuk motivasi</p>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        @guest
            <div
                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 15px; padding: 2rem; text-align: center; box-shadow: var(--card-shadow);">
                <h2 style="margin-top: 0; margin-bottom: 1rem;">Siap Belajar?</h2>
                <p style="margin: 0 0 1.5rem 0; opacity: 0.95;">Login untuk mengakses platform pembelajaran</p>
                <a href="{{ route('login') }}"
                    style="padding: 1rem 2rem; background: white; color: #667eea; border: none; border-radius: 10px; cursor: pointer; font-weight: 700; text-decoration: none; font-size: 1rem; transition: transform 0.3s; display: inline-block;">
                    <i class="fas fa-sign-in-alt"></i> Login
                </a>
            </div>
        @endguest

        @auth
            <div
                style="background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)); color: white; border-radius: 15px; padding: 2rem; text-align: center; box-shadow: var(--card-shadow);">
                <h2 style="margin-top: 0; margin-bottom: 1rem;">Lanjutkan Belajar</h2>
                <p style="margin: 0 0 1.5rem 0; opacity: 0.95;">Kembali ke dashboard untuk melanjutkan pembelajaran Anda</p>
                <a href="{{ auth()->user()->isTeacher() ? route('guru.dashboard') : route('siswa.dashboard') }}"
                    style="padding: 1rem 2rem; background: white; color: var(--primary-color); border: none; border-radius: 10px; cursor: pointer; font-weight: 700; text-decoration: none; font-size: 1rem; display: inline-block;">
                    <i class="fas fa-arrow-right"></i> Ke Dashboard
                </a>
            </div>
        @endauth
    </div>
@endsection
