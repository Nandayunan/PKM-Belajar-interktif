@extends('layouts.app')

@section('title', 'Buat Mata Pelajaran')

@section('content')
    <div
        style="background: white; border-radius: 15px; padding: 2rem; box-shadow: 0 10px 30px rgba(99, 102, 241, 0.1); max-width: 600px; margin: 0 auto;">
        <h1 style="color: var(--primary-color); margin-bottom: 2rem;">
            <i class="fas fa-plus"></i> Buat Mata Pelajaran
        </h1>

        <form method="POST" action="{{ route('guru.subjects.store') }}">
            @csrf

            <div style="margin-bottom: 1.5rem;">
                <label style="font-weight: 700; color: #2d3748; display: block; margin-bottom: 0.5rem;">Nama Mata
                    Pelajaran</label>
                <input type="text" name="name"
                    style="width: 100%; padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 10px; font-family: 'Poppins', sans-serif;"
                    required>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="font-weight: 700; color: #2d3748; display: block; margin-bottom: 0.5rem;">Deskripsi</label>
                <textarea name="description"
                    style="width: 100%; padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 10px; font-family: 'Poppins', sans-serif; min-height: 100px;"
                    required></textarea>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="font-weight: 700; color: #2d3748; display: block; margin-bottom: 0.5rem;">Icon (Emoji)</label>
                <input type="text" name="icon"
                    style="width: 100%; padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 10px; font-family: 'Poppins', sans-serif;"
                    placeholder="📚" required>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="font-weight: 700; color: #2d3748; display: block; margin-bottom: 0.5rem;">Warna (#HEX)</label>
                <input type="color" name="color"
                    style="width: 100%; padding: 0.5rem; border: 2px solid #e5e7eb; border-radius: 10px; height: 45px;"
                    required>
            </div>

            <div style="display: flex; gap: 1rem;">
                <button type="submit"
                    style="padding: 0.75rem 2rem; background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)); color: white; border: none; border-radius: 10px; font-weight: 700; cursor: pointer; transition: all 0.3s;">
                    <i class="fas fa-save"></i> Buat Mata Pelajaran
                </button>
                <a href="{{ route('guru.dashboard') }}"
                    style="padding: 0.75rem 2rem; background: #f3f4f6; color: #666; border-radius: 10px; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-arrow-left"></i> Batal
                </a>
            </div>
        </form>
    </div>
@endsection
