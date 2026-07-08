<div id="students-actions-message"></div>

@if (empty($editable))
    <div class="section-header">
        <div>
            <div class="section-title">
                <i class="fas fa-users"></i> Manage Siswa
            </div>
            <p style="margin:0.5rem 0 0; color:#FFFFFF; max-width:760px;">Kelola akun siswa permanen, data pribadi, dan riwayat. Penempatan kelas per tahun ajaran dilakukan di tab Manage Kelas.</p>
        </div>
        <a href="{{ route('guru.students.create', ['class' => $selectedClass ?? null]) }}" class="btn-add">
            <i class="fas fa-user-plus"></i> Tambah Siswa
        </a>
    </div>

    <div style="margin-bottom:2rem; display:flex; gap:0.75rem; flex-wrap:wrap; align-items:flex-end; width:100%;">
        <form id="students-filter-form" method="GET" action="{{ route('guru.dashboard') }}" style="display:flex; gap:0.75rem; flex-wrap:wrap; align-items:flex-end; width:100%;">
            <input type="hidden" name="tab" value="students">

            <div style="min-width:220px; flex:1;">
                <label for="search-q"
        style="font-weight:700; display:block; margin-bottom:0.5rem; color:white;">
        Cari Nama Siswa
    </label>
                <input id="search-q" name="q" placeholder="Cari nama..." value="{{ $searchQ ?? '' }}"
                    style="width:100%; padding:0.75rem; border-radius:10px; border:1px solid #e5e7eb;">
            </div>

            <div style="min-width:220px; flex:1;">
                <label for="class" style="font-weight:700; display:block; margin-bottom:0.5rem; color:white;">Filter Kelas</label>
                <select id="class" name="class"
                    style="width:100%; padding:0.75rem; border-radius:10px; border:1px solid #e5e7eb;">
                    <option value="">Semua Kelas</option>
                    @foreach ($classes as $c)
                        <option value="{{ $c->name }}" @if (isset($selectedClass) && $selectedClass === $c->name) selected @endif>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <div style="min-width:220px; flex:1;">
                <label for="academic_year_filter" style="font-weight:700; display:block; margin-bottom:0.5rem; color:white;">Filter Tahun Ajaran</label>
                <select id="academic_year_filter" name="academic_year"
                    style="width:100%; padding:0.75rem; border-radius:10px; border:1px solid #e5e7eb;">
                    <option value="">Semua Tahun Ajaran</option>
                    @foreach ($academicYears as $y)
                        <option value="{{ $y->name }}" @if (isset($selectedAcademicYear) && $selectedAcademicYear === $y->name) selected @endif>{{ $y->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn-add" style="padding:0.85rem 1.25rem; white-space:nowrap;">
                <i class="fas fa-search"></i> Tampilkan
            </button>

            <button type="button" id="students-reset-btn" class="btn-sm"
                style="background:#eee; padding:0.75rem 1rem; border-radius:10px; color:#333; text-decoration:none;">
                Reset Filter
            </button>
        </form>
    </div>
@else
    <div class="section-header">
        <div class="section-title">
            <i class="fas fa-users"></i> Atur Kelas Siswa
        </div>
    </div>
    <div style="margin-bottom:1rem; font-weight:700;">Kelola kelas siswa untuk tahun ajaran: {{ $selectedAcademicYear ?? '-' }}</div>
    <div style="margin-bottom:1rem; color:#475569;">Perbarui kelas siswa untuk tahun ajaran ini lalu tekan Simpan Perubahan.</div>
@endif

@if ($students->isEmpty())
    <div class="empty-state" style="padding:1.5rem;">
        <i class="fas fa-inbox"></i>
        <p>
            Belum ada siswa yang terdaftar{{ !empty($selectedClass) ? ' di kelas ' . $selectedClass : '' }}.
        </p>
        <a href="{{ route('guru.students.create', ['class' => $selectedClass ?? null]) }}" class="btn-add">
            <i class="fas fa-user-plus"></i> Tambah Siswa
        </a>
    </div>
@else
    <div class="table-responsive-custom">
        <table class="table">
            <thead>
                <tr>
                    <th>Nama Siswa</th>
                    <th>Email</th>
                    <th>No. HP</th>
                    @if (!empty($editable))
                        <th>Kelas Baru</th>
                        <th>Wali Kelas</th>
                    @else
                        <th>Kelas</th>
                        <th>Tahun Ajaran</th>
                        <th>Wali Kelas</th>
                        <th>Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($students as $student)
                    <tr>
                        <td><strong>{{ $student->name }}</strong></td>
                        <td>{{ $student->email }}</td>
                        <td>{{ $student->phone ?? '-' }}</td>
                        @if (!empty($editable))
                            <td>
                                <select class="student-class-select" data-user-id="{{ $student->id }}"
                                    style="width:100%; padding:0.6rem 0.75rem; border-radius:8px; border:1px solid #d1d5eb;">
                                    <option value="">-- Kosong --</option>
                                    @foreach ($classes as $c)
                                        <option value="{{ $c->name }}" @if(($student->class ?? '') === $c->name) selected @endif>{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="text" class="student-homeroom-input" data-user-id="{{ $student->id }}"
                                    value="{{ $student->homeroom_teacher ?? '' }}"
                                    placeholder="Wali Kelas"
                                    style="width:100%; padding:0.6rem 0.75rem; border-radius:8px; border:1px solid #d1d5eb;">
                            </td>
                        @else
                            <td>{{ $student->class ?? '-' }}</td>
                            <td>{{ $student->academic_year ?? '-' }}</td>
                            <td>{{ $student->homeroom_teacher ?? '-' }}</td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('guru.students.show', $student->id) }}" class="btn-sm btn-view">
                                        <i class="fas fa-eye"></i> Lihat
                                    </a>
                                    <a href="{{ route('guru.students.edit', $student->id) }}" class="btn-sm btn-edit">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('guru.students.destroy', $student->id) }}" method="POST" style="display: inline;"
                                        data-ajax-delete="true">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-sm btn-delete">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if ($students->hasPages())
        <div style="margin-top: 2rem;">
            {{ $students->links() }}
        </div>
    @endif
@endif
