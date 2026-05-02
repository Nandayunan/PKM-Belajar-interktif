@extends('layouts.app')

@section('title', 'Progress Siswa')

@section('content')
    <div class="container mt-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0">Progress Siswa</h4>
                    <small class="text-muted">{{ $student->name }} — {{ $subject->name }}</small>
                </div>
                <div>
                    <a href="{{ route('guru.student-progress.show', ['user' => $student->id, 'subject' => $subject->id]) }}"
                        class="btn btn-outline-secondary">Refresh</a>
                    <a href="{{ route('guru.dashboard') }}" class="btn btn-primary">Kembali</a>
                </div>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Total Soal:</strong> {{ $totals['total_questions'] }} &nbsp;&nbsp;
                    <strong>Terjawab:</strong> {{ $totals['answered_questions'] }} &nbsp;&nbsp;
                    <strong>Benar:</strong> {{ $totals['correct_answers'] }} &nbsp;&nbsp;
                    <strong>Persentase:</strong> {{ $overallPercentage }}%
                </div>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Modul</th>
                                <th class="text-center">Total Soal</th>
                                <th class="text-center">Terjawab</th>
                                <th class="text-center">Benar</th>
                                <th class="text-center">Skor</th>
                                <th class="text-center">%</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rows as $r)
                                <tr>
                                    <td>{{ $r['module']->name }}</td>
                                    <td class="text-center">{{ $r['total_questions'] }}</td>
                                    <td class="text-center">{{ $r['answered_questions'] }}</td>
                                    <td class="text-center">{{ $r['correct_answers'] }}</td>
                                    <td class="text-center">{{ $r['earned_points'] }} / {{ $r['total_points'] }}</td>
                                    <td class="text-center">{{ $r['percentage'] }}%</td>
                                    <td class="text-center text-capitalize">{{ $r['status'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">Belum ada modul atau data progress untuk mata pelajaran ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
