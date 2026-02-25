@extends('layouts.app')

@section('title', 'Edit Modul')

@section('content')
    <div
        style="background: white; border-radius: 15px; padding: 2rem; box-shadow: 0 10px 30px rgba(99, 102, 241, 0.1); max-width: 700px; margin: 0 auto;">
        <h1 style="color: var(--primary-color); margin-bottom: 2rem;">
            <i class="fas fa-edit"></i> Edit Modul
        </h1>

        <form method="POST" action="{{ route('guru.modules.update', [0, 0]) }}">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 1.5rem;">
                <label style="font-weight: 700; color: #2d3748; display: block; margin-bottom: 0.5rem;">Nama Modul</label>
                <input type="text" name="name"
                    style="width: 100%; padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 10px; font-family: 'Poppins', sans-serif;"
                    required>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="font-weight: 700; color: #2d3748; display: block; margin-bottom: 0.5rem;">Nomor Modul</label>
                <input type="number" name="module_number"
                    style="width: 100%; padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 10px; font-family: 'Poppins', sans-serif;"
                    required>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label
                    style="font-weight: 700; color: #2d3748; display: block; margin-bottom: 0.5rem;">Konten/Materi</label>
                <textarea name="content"
                    style="width: 100%; padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 10px; font-family: 'Poppins', sans-serif; min-height: 150px;"
                    required></textarea>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="font-weight: 700; color: #2d3748; display: block; margin-bottom: 0.5rem;">Link Video
                    YouTube</label>
                <input type="url" name="video_url"
                    style="width: 100%; padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 10px; font-family: 'Poppins', sans-serif;"
                    placeholder="https://youtube.com/watch?v=...">
            </div>

            <div style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 1rem;">
                <input type="checkbox" name="published" id="published" style="width: 20px; height: 20px;">
                <label for="published" style="font-weight: 700; color: #2d3748; cursor: pointer;">Publikasikan modul
                    ini</label>
            </div>

            <div style="display: flex; gap: 1rem;">
                <button type="submit"
                    style="padding: 0.75rem 2rem; background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)); color: white; border: none; border-radius: 10px; font-weight: 700; cursor: pointer; transition: all 0.3s;">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
                <a href="{{ route('guru.dashboard') }}"
                    style="padding: 0.75rem 2rem; background: #f3f4f6; color: #666; border-radius: 10px; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-arrow-left"></i> Batal
                </a>
            </div>
        </form>
    </div>
@endsection
