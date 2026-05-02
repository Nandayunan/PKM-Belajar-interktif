@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card">
            <div class="card-header bg-primary text-white">Preview Import Soal</div>
            <div class="card-body">
                <p>Periksa baris yang akan diimpor. Baris dengan <strong>error</strong> tidak akan disimpan.</p>

                <form method="POST" action="{{ route('guru.questions.import.confirm') }}">
                    @csrf

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Baris</th>
                                    <th>Pertanyaan</th>
                                    <th>Tipe</th>
                                    <th>Opsi</th>
                                    <th>Jawaban Benar</th>
                                    <th>Module ID</th>
                                    <th>Errors</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($parsed as $row)
                                    <tr @if (!empty($row['errors'])) class="table-warning" @endif>
                                        <td>{{ $row['row'] }}</td>
                                        <td style="max-width:320px">{{ $row['question'] }}</td>
                                        <td>{{ $row['type'] }}</td>
                                        <td>
                                            @if (is_array($row['options']))
                                                <ul class="mb-0">
                                                    @foreach ($row['options'] as $opt)
                                                        <li>{{ $opt }}</li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $row['correct_answer'] }}</td>
                                        <td>{{ $row['module_id'] }}</td>
                                        <td>
                                            @if (!empty($row['errors']))
                                                <ul class="mb-0">
                                                    @foreach ($row['errors'] as $e)
                                                        <li>{{ $e }}</li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-success">Konfirmasi dan Simpan</button>
                        <a href="{{ route('guru.questions.import') }}" class="btn btn-secondary">Kembali / Edit File</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
