@extends('layouts.app')

@section('title', 'Daftar Mata Pelajaran')

@section('content')
    <div style="max-width: 1100px; margin: 3rem auto;">
        <h1 style="color: var(--primary-color);">Daftar Mata Pelajaran</h1>
        <p>Daftar seluruh mata pelajaran. Klik "Daftar" untuk mendaftar ke mata pelajaran.</p>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap:1.5rem; margin-top:1.5rem;">
            @foreach($subjects as $subject)
                @php
                    $isEnrolled = in_array($subject->id, $enrolledSubjectIds ?? []);
                    $moduleCount = $subject->publishedModules()->count();
                @endphp
                <div class="card" style="padding:1rem;">
                    <h3 style="margin-top:0;">{{ $subject->name }} @if($subject->class) <small style="color:#6b7280">({{ $subject->class }})</small>@endif</h3>
                    <p style="color:#6b7280;">{{ $subject->description }}</p>
                    <div style="margin-top:1rem; display:flex; gap:0.5rem;">
                        @if($isEnrolled)
                            <a href="{{ route('siswa.modules.index', $subject->id) }}" class="btn-primary" style="display:inline-block; padding:0.5rem 1rem;">Lihat Modul</a>
                        @else
                            <form method="POST" action="{{ route('siswa.subjects.enroll', $subject->id) }}">
                                @csrf
                                @if($subject->access_code)
                                    <input type="text" name="access_code" placeholder="Kode akses" style="padding:0.4rem; border:1px solid #e5e7eb; border-radius:6px; margin-right:0.5rem;">
                                @endif
                                <button type="submit" class="btn-primary" style="padding:0.5rem 1rem;">Daftar</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
