@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <!-- Header -->
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Pendaftaran Calon Siswa</h6>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('pendaftaran.index') }}"
                            class="{{ request()->routeIs('pendaftaran.index') ? 'active' : '' }}">
                            Pendaftaran
                        </a>
                    </li>
                </ol>
            </div>

            <!-- Body -->
            <div class="card-body">
                @if (empty($pendaftaranTerakhir->nomer_pendaftaran) && $nilaibolehdiupdate)
                    <div class="alert alert-success text-center d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-check-circle-fill mr-2"></i>
                        <span>Segera unggah nilai dan prestasi sebelum form ditutup.</span>
                    </div>
                @else
                    <div class="alert alert-danger text-center d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-exclamation-triangle-fill mr-2"></i>
                        <span>
                            Form upload nilai dan prestasi sudah ditutup.
                            @if (!empty($pendaftaranTerakhir->nomer_pendaftaran))
                                Karena sudah mendaftar, Anda tidak dapat mengubah data lagi.
                            @else
                                Anda tidak dapat mengubah data lagi.
                            @endif
                        </span>
                    </div>
                @endif
                <!-- BIODATA SINGKAT -->
                <div class="d-flex mb-4">
                    <!-- Foto -->
                    <div class="mr-4">
                        @if ($biodataCalonSiswa->foto_formal_calon_siswa)
                            <img src="{{ asset('storage/' . $biodataCalonSiswa->foto_formal_calon_siswa) }}"
                                alt="Foto Formal" class="img-thumbnail" width="150">
                        @else
                            <em>Tidak ada foto</em>
                        @endif
                    </div>

                    <!-- Info Biodata -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th width="30%">Nama</th>
                                    <td>{{ $biodataCalonSiswa->nama_calon_siswa ?? 'Data belum diisi' }}</td>
                                </tr>
                                <tr>
                                    <th>Periode</th>
                                    <td>{{ $biodataCalonSiswa->periode->nama_periode ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $biodataCalonSiswa->email_calon_siswa ?? 'Data belum diisi' }}</td>
                                </tr>
                                <tr>
                                    <th>Dokumen Pendukung</th>
                                    <td>
                                        @if ($biodataCalonSiswa->dokumen_nilai_pendukung_calon_siswa)
                                            <a href="{{ asset('storage/' . $biodataCalonSiswa->dokumen_nilai_pendukung_calon_siswa) }}"
                                                target="_blank" class="btn btn-sm btn-primary">Lihat</a>
                                        @else
                                            <em>Tidak ada dokumen</em>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Jumlah Prestasi Non Akademik</th>
                                    <td>{{ $totalPrestasi }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- TABEL PENDAFTARAN -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Nomor Pendaftaran</th>
                                <th>Tanggal Pendaftaran</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pendaftaran as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->nomer_pendaftaran ?? '-' }}</td>
                                    <td>
                                        {{ $item->tanggal_pendaftaran
                                            ? \Carbon\Carbon::parse($item->tanggal_pendaftaran)->translatedFormat('d F Y')
                                            : '-' }}
                                    </td>
                                    <td>
                                        @php
                                            $status = $item->status_final ?? 'Pending';
                                        @endphp

                                        @if ($status == 'Lolos')
                                            <span class="badge bg-success">{{ $status }}</span>
                                        @elseif ($status == 'Tidak Lolos')
                                            <span class="badge bg-danger">{{ $status }}</span>
                                        @else
                                            <span class="badge bg-warning text-dark">{{ $status }}</span>
                                        @endif

                                        @if ($item->status_cadangan)
                                            <br>
                                            <span class="badge bg-info mt-1">Status Cadangan</span>
                                        @endif
                                    </td>


                                    <td>
                                        @if (empty($item->nomer_pendaftaran) && $nilaibolehdiupdate)
                                            <a href="{{ route('pendaftaran.raport', $item->id) }}"
                                                class="btn btn-sm btn-warning" data-toggle="tooltip"
                                                title="Input Raport / Prestasi">
                                                <i class="fas fa-file-alt"></i>
                                            </a>
                                            <button class="btn btn-sm btn-success btn-daftar" data-id="{{ $item->id }}"
                                                data-toggle="tooltip" title="Daftar">
                                                <i class="fas fa-check-circle"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada data pendaftaran.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Tabel Nilai Akademik Seleksi -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h6 class="font-weight-bold text-primary">Nilai Akademik Seleksi</h6>
                    </div>
                    <div class="card-body">
                        <table id="table-nilai-akademik" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Nilai</th>
                                    <th>Gelombang</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                <!-- Tabel Prestasi Seleksi -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h6 class="font-weight-bold text-primary">Prestasi Seleksi</h6>
                    </div>
                    <div class="card-body">
                        <table id="table-prestasi" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kategori Prestasi</th>
                                    <th>Nama Prestasi</th>
                                    <th>Jumlah Prestasi</th>
                                    <th>Dokumen Pendukung</th>
                                    <th>Gelombang</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <a href="{{ route('pendaftaran.update-status-seleksi-history', $pendaftaranTerakhir->id) }}"
                        class="btn btn-success">
                        <i class="fas fa-history"></i> History Status
                    </a>
                </div>
            </div>
        </div>
    </div>
    @include('pendaftaran.calon-siswa.revisi-nilai-akademik')
@endsection

@push('styles')
    <style>
        .breadcrumb {
            background-color: transparent;
            padding: 0;
            margin-bottom: 0;
        }

        .breadcrumb-item {
            font-size: 0.875rem;
        }

        .breadcrumb-item a {
            color: #464646;
            text-decoration: none;
        }

        .breadcrumb-item a:hover {
            text-decoration: underline;
        }

        .breadcrumb-item a.active {
            font-weight: bold;
            color: #007bff;
            pointer-events: none;
        }

        .table th {
            background-color: #f8f9fc;
            color: #495057;
        }

        .table td {
            background-color: #ffffff;
        }

        .table-bordered td,
        .table-bordered th {
            border-color: #dee2e6;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Ambil ID pendaftaran pertama (atau kamu bisa loop jika ada banyak)
            let pendaftaranId = @json($biodataCalonSiswa->id ?? null);

            if (!pendaftaranId) {
                console.warn('Tidak ada data pendaftaran untuk load DataTables');
                return;
            }

            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: '{{ session('success') }}',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                });
            @endif

            // DataTable Nilai Akademik
            $('#table-nilai-akademik').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: `/pendaftaran-management/pendaftaran/${pendaftaranId}/mata-pelajaran-seleksi/list/all`,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama_mata_pelajaran_seleksi',
                        name: 'mata_pelajaran_seleksis.nama_mata_pelajaran_seleksi'
                    },
                    {
                        data: 'nilai',
                        name: 'nilai_akademik_pendaftars.nilai'
                    },
                    {
                        data: 'gelombang_pendaftaran',
                        name: 'jadwal_pendaftarans.gelombang_pendaftaran'
                    },
                    {
                        data: 'status',
                        name: 'nilai_akademik_pendaftars.status',
                        render: function(data) {
                            //badge Accept hijau, badge Reject merah, badge Pending kuning
                            if (data == 'Accept') {
                                return '<span class="badge bg-success text-white">' + data +
                                    '</span>';
                            } else if (data == 'Reject') {
                                return '<span class="badge bg-danger text-white">' + data +
                                    '</span>';
                            } else {
                                return '<span class="badge bg-warning text-white">' + data +
                                    '</span>';
                            }
                        }
                    },
                    {
                        data: 'id',
                        name: 'nilai_akademik_pendaftars.id',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            if (row.status === 'Reject') {
                                return `
                <button class="btn btn-sm btn-primary btn-revisi-nilai"
            data-id="${data}">
            Revisi
        </button>
            `;
                            } else {
                                return ''; // kosongkan kalau status bukan Reject
                            }
                        }
                    }

                ],
                order: [
                    [1, 'asc']
                ]
            });

            // DataTable Prestasi
            $('#table-prestasi').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: `/pendaftaran-management/pendaftaran/${pendaftaranId}/prestasi-seleksi/list/all`,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama_kategori_prestasi',
                        name: 'kategori_prestasis.nama_kategori_prestasi'
                    },
                    {
                        data: 'nama_prestasi',
                        name: 'prestasis.nama_prestasi'
                    },
                    {
                        data: 'jumlah_prestasi',
                        name: 'prestasi_pendaftars.jumlah_prestasi'
                    },
                    {
                        data: 'dokumen_prestasi_pendukung',
                        name: 'prestasi_pendaftars.dokumen_prestasi_pendukung',
                        render: function(data, type, row) {
                            if (data) {
                                return `<a href="{{ asset('storage') }}/${data}" target="_blank">Lihat Dokumen</a>`;
                            } else {
                                return '-';
                            }
                        },
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'gelombang_pendaftaran',
                        name: 'jadwal_pendaftarans.gelombang_pendaftaran'
                    },
                    {
                        data: 'status',
                        name: 'prestasi_pendaftars.status',
                        render: function(data) {
                            //badge Accept hijau, badge Reject merah, badge Pending kuning
                            if (data == 'Accept') {
                                return '<span class="badge bg-success text-white">' + data +
                                    '</span>';
                            } else if (data == 'Reject') {
                                return '<span class="badge bg-danger text-white">' + data +
                                    '</span>';
                            } else {
                                return '<span class="badge bg-warning text-white">' + data +
                                    '</span>';
                            }
                        }
                    },
                ],
                order: [
                    [1, 'asc']
                ]
            });

            // Nomor 4: Tangani klik tombol "Revisi"
            $(document).on('click', '.btn-revisi-nilai', function() {
                let id = $(this).data('id');

                // Kosongkan isi histori dulu
                $('#statusHistoriesContainer').html('<p class="text-muted">Memuat data...</p>');
                $('#revisiNilaiId').val(id);

                // Ambil data nilai & histori via AJAX
                $.get(`/pendaftaran-management/pendaftaran/${id}/show-revisi-nilai-akademik`, function(
                    response) {
                    // Isi input nilai
                    $('#nilai').val(response.nilai);

                    // Tampilkan histori
                    let histories = response.status_histories;
                    if (histories.length > 0) {
                        let html = '<ul class="list-group">';
                        histories.forEach(function(item) {
                            html += `
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                        <div>
                            <strong>Status:</strong> ${item.status}<br>
                            <strong>Catatan:</strong> ${item.catatan || '-'}<br>
                            <strong>Petugas:</strong> ${item.nama_petugas || '-'}
                        </div>
                        <small class="text-muted">${item.created_at}</small>
                    </li>
                `;
                        });
                        html += '</ul>';
                        $('#statusHistoriesContainer').html(html);
                    } else {
                        $('#statusHistoriesContainer').html(
                            '<p class="text-muted">Belum ada riwayat status.</p>');
                    }

                    // Tampilkan modal
                    $('#modalRevisiNilaiAkademik').modal('show');
                });
            });


            // Nomor 5: Tangani submit form revisi via AJAX
            $('#formRevisiNilaiAkademik').submit(function(e) {
                e.preventDefault();

                let id = $('#revisiNilaiId').val();
                let nilai = $('#nilai').val();

                $.ajax({
                    url: `/pendaftaran-management/pendaftaran/${id}/update-revisi-nilai-akademik`,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        nilai: nilai
                    },
                    success: function(response) {
                        if (response.status) {
                            $('#modalRevisiNilaiAkademik').modal('hide');
                            $('#table-nilai-akademik').DataTable().ajax.reload(null, false);

                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: response.message || 'Terjadi kesalahan.',
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Gagal memperbarui nilai.',
                        });
                    }

                });
            });

        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('.btn-daftar');

            buttons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.dataset.id;

                    Swal.fire({
                        title: 'Yakin ingin daftar?',
                        text: "Setelah daftar, biodata tidak bisa diubah lagi.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, daftar sekarang',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Kirim request ke route daftar via POST (AJAX)
                            fetch(`/pendaftaran-management/pendaftaran/daftar/${id}`, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json'
                                    },
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        Swal.fire('Berhasil!', data.message, 'success')
                                            .then(() => {
                                                // reload halaman agar biodata terkunci (tidak bisa diubah)
                                                location.reload();
                                            });
                                    } else {
                                        Swal.fire('Gagal!', data.message ||
                                            'Terjadi kesalahan.', 'error');
                                    }
                                })
                                .catch(() => {
                                    Swal.fire('Gagal!',
                                        'Terjadi kesalahan saat menghubungi server.',
                                        'error');
                                });
                        }
                    });
                });
            });
        });
    </script>
@endpush
