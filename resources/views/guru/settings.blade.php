@extends('layouts.app')

@section('title', 'Pengaturan Guru')

@section('content')
    <div style="background: white; border-radius: 15px; padding: 2rem; box-shadow: 0 10px 30px rgba(99, 102, 241, 0.1);">
        <h1 style="color: var(--primary-color); margin-bottom: 2rem;">
            <i class="fas fa-cog"></i> Pengaturan Guru
        </h1>

        <form method="POST" action="{{ route('guru.settings') }}">
            @csrf

            <div style="background: #f8f9ff; padding: 2rem; border-radius: 15px; margin-bottom: 2rem;">
                <h3 style="color: #2d3748; margin-bottom: 1.5rem;">Pengaturan Tampilan Jawaban Siswa</h3>

                <div style="display: grid; gap: 1.5rem;">
                    <!-- Show Correct Answers -->
                    <div
                        style="padding: 1.5rem; background: white; border-radius: 10px; border-left: 4px solid var(--primary-color);">
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <div>
                                <h4 style="margin: 0; color: #2d3748; font-weight: 700;">Tampilkan Jawaban Benar</h4>
                                <p style="margin: 0.5rem 0 0 0; color: #666; font-size: 0.9rem;">
                                    Apakah siswa dapat melihat jawaban yang benar?
                                </p>
                            </div>
                            <label style="cursor: pointer;">
                                <input type="checkbox" name="show_correct_answers" value="1"
                                    style="width: 24px; height: 24px;">
                            </label>
                        </div>
                    </div>

                    <!-- Show Wrong Answers -->
                    <div
                        style="padding: 1.5rem; background: white; border-radius: 10px; border-left: 4px solid var(--primary-color);">
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <div>
                                <h4 style="margin: 0; color: #2d3748; font-weight: 700;">Tampilkan Jawaban Salah</h4>
                                <p style="margin: 0.5rem 0 0 0; color: #666; font-size: 0.9rem;">
                                    Apakah siswa dapat melihat jawaban mereka yang salah?
                                </p>
                            </div>
                            <label style="cursor: pointer;">
                                <input type="checkbox" name="show_wrong_answers" value="1"
                                    style="width: 24px; height: 24px;">
                            </label>
                        </div>
                    </div>

                    <!-- Show Score -->
                    <div
                        style="padding: 1.5rem; background: white; border-radius: 10px; border-left: 4px solid var(--primary-color);">
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <div>
                                <h4 style="margin: 0; color: #2d3748; font-weight: 700;">Tampilkan Skor</h4>
                                <p style="margin: 0.5rem 0 0 0; color: #666; font-size: 0.9rem;">
                                    Apakah siswa dapat melihat skor mereka?
                                </p>
                            </div>
                            <label style="cursor: pointer;">
                                <input type="checkbox" name="show_score" value="1" style="width: 24px; height: 24px;">
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 1rem;">
                <button type="submit"
                    style="padding: 0.75rem 2rem; background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)); color: white; border: none; border-radius: 10px; font-weight: 700; cursor: pointer; transition: all 0.3s;">
                    <i class="fas fa-save"></i> Simpan Pengaturan
                </button>
                <a href="{{ route('guru.dashboard') }}"
                    style="padding: 0.75rem 2rem; background: #f3f4f6; color: #666; border-radius: 10px; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
@endsection
