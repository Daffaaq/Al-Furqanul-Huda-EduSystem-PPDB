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
                    <li class="breadcrumb-item">
                        <a href="{{ route('pendaftaran.show', $pendaftaran->id) }}"
                            class="{{ request()->routeIs('pendaftaran.show') ? 'active' : '' }}">
                            Detail Pendaftaran</a>
                    </li>
                </ol>
            </div>

            <!-- Body -->
            <div class="card-body">
                @if ($pendaftaran->status_diskualifikasi)
                    <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                        <i class="fas fa-exclamation-circle fa-lg mr-2"></i>
                        <strong>Perhatian:</strong> Pendaftaran dengan nama
                        {{ $pendaftaran->biodataCalonSiswa->nama_calon_siswa }} <strong>diskualifikasi</strong> dan tidak
                        dapat
                        diproses lebih lanjut.
                    </div>
                @endif

                <!-- BIODATA SINGKAT -->
                <div class="d-flex mb-4">
                    <!-- Foto -->
                    <div class="mr-4">
                        @if ($pendaftaran->biodataCalonSiswa->foto_formal_calon_siswa)
                            <img src="{{ asset('storage/' . $pendaftaran->biodataCalonSiswa->foto_formal_calon_siswa) }}"
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
                                    <td>{{ $pendaftaran->biodataCalonSiswa->nama_calon_siswa ?? 'Data belum diisi' }}</td>
                                </tr>
                                <tr>
                                    <th>Periode</th>
                                    <td>{{ $pendaftaran->biodataCalonSiswa->periode->nama_periode ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $pendaftaran->biodataCalonSiswa->email_calon_siswa ?? 'Data belum diisi' }}</td>
                                </tr>
                                <tr>
                                    <th>Dokumen Pendukung</th>
                                    <td>
                                        @if ($pendaftaran->biodataCalonSiswa->dokumen_nilai_pendukung_calon_siswa)
                                            <a href="{{ asset('storage/' . $pendaftaran->biodataCalonSiswa->dokumen_nilai_pendukung_calon_siswa) }}"
                                                target="_blank" class="btn btn-sm btn-primary">Lihat</a>
                                        @else
                                            <em>Tidak ada dokumen</em>
                                        @endif
                                    </td>
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
                            <tr>
                                <td>1</td>
                                <td>{{ $pendaftaran->nomer_pendaftaran ?? '-' }}</td>
                                <td>
                                    {{ $pendaftaran->tanggal_pendaftaran
                                        ? \Carbon\Carbon::parse($pendaftaran->tanggal_pendaftaran)->translatedFormat('d F Y')
                                        : '-' }}
                                </td>
                                </td>
                                <td>{{ ucfirst($pendaftaran->status_pendaftaran ?? 'belum diproses') }}</td>
                                <td>
                                    @if (!$pendaftaran->status_diskualifikasi)
                                        <button class="btn btn-sm btn-danger btn-disqualify"
                                            data-id="{{ $pendaftaran->id }}" data-toggle="tooltip" title="Disqualify">
                                            <i class="fas fa-times-circle"></i>
                                        </button>
                                    @endif
                                    @if ($pendaftaran->status_diskualifikasi)
                                        <button class="btn btn-sm btn-secondary btn-cancel-disqualify"
                                            data-id="{{ $pendaftaran->id }}" data-toggle="tooltip"
                                            title="Cancel Disqualify">
                                            <i class="fas fa-undo"></i>
                                        </button>

                                        <a href="{{ route('pendaftaran.diskualifikasi-history', $pendaftaran->id) }}"
                                            class="btn btn-sm btn-info" data-toggle="tooltip" title="Detail Disqualify">
                                            <i class="fas fa-clipboard-check"></i>
                                        </a>
                                    @endif

                                    <a href="{{ route('pendaftaran.raport', $pendaftaran->id) }}"
                                        class="btn btn-sm btn-warning" data-toggle="tooltip"
                                        title="Input Raport / Prestasi">
                                        <i class="fas fa-file-alt"></i>
                                    </a>

                                    <button class="btn btn-sm btn-success btn-daftar" data-id="{{ $pendaftaran->id }}"
                                        data-toggle="tooltip" title="Daftar">
                                        <i class="fas fa-check-circle"></i>
                                    </button>
                                </td>
                            </tr>
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
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-4 text-right">
                    <button class="btn btn-primary" id="btn-edit-status">Edit Status Nilai & Prestasi</button>
                    <a href="{{ route('pendaftaran.update-status-seleksi-history', $pendaftaran->id) }}"
                        class="btn btn-success">History Status</a>
                    <a href="{{ route('pendaftaran.index') }}" class="btn btn-secondary">Kembali</a>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-edit"></i> Edit Status Nilai & Prestasi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs" id="statusTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="nilai-tab" data-toggle="tab" href="#nilai" role="tab"
                                aria-controls="nilai" aria-selected="true">Nilai Akademik</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="prestasi-tab" data-toggle="tab" href="#prestasi" role="tab"
                                aria-controls="prestasi" aria-selected="false">Prestasi</a>
                        </li>
                    </ul>
                    <div class="tab-content mt-3" id="statusTabContent">
                        <div class="tab-pane fade show active" id="nilai" role="tabpanel"
                            aria-labelledby="nilai-tab">
                            <table class="table table-bordered" id="modal-table-nilai">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Nilai</th>
                                        <th>Status</th>
                                        <th>Catatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- isi dengan JS -->
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="prestasi" role="tabpanel" aria-labelledby="prestasi-tab">
                            <table class="table table-bordered" id="modal-table-prestasi">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kategori</th>
                                        <th>Nama Prestasi</th>
                                        <th>Jumlah</th>
                                        <th>Status</th>
                                        <th>Catatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- isi dengan JS -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btn-save-status" class="btn btn-success">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Diskualifikasi -->
    <div class="modal fade" id="diskualifikasiModal" tabindex="-1" aria-labelledby="diskualifikasiModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form id="formDiskualifikasi" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Diskualifikasi Pendaftar</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="pendaftaranId">

                        <div class="mb-3">
                            <label for="alasan_diskualifikasi" class="form-label">Alasan Diskualifikasi</label>
                            <textarea name="alasan_diskualifikasi" class="form-control" rows="3" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="bukti_diskualifikasi" class="form-label">Bukti (Opsional)</label>
                            <input type="file" name="bukti_diskualifikasi" class="form-control"
                                accept=".jpg,.jpeg,.png,.pdf">
                            <small class="text-muted">Maksimal 2MB. Format: jpg, png, pdf</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Diskualifikasi</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- modal edit prestasi dan nilai --}}
    <!-- Modal Update Status -->
    <div class="modal fade" id="modalUpdateStatus" tabindex="-1" aria-labelledby="modalUpdateLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form id="form-update-status">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Status</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="update-id">
                        <input type="hidden" id="update-type">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control" id="status" required>
                                <option value="">-- Pilih Status --</option>
                                <option value="Accept">Accept</option>
                                <option value="Reject">Reject</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="catatan" class="form-label">Catatan</label>
                            <textarea class="form-control" id="catatan" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
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
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).on('click', '.btn-cancel-disqualify', function() {
            const id = $(this).data('id');

            Swal.fire({
                title: 'Yakin ingin membatalkan diskualifikasi?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, batalkan!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/pendaftaran-management/pendaftaran/${id}/batal-diskualifikasi`,
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            Swal.fire('Berhasil!', response.message, 'success').then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            let message = xhr.responseJSON?.message || xhr.responseJSON
                                ?.error || 'Terjadi kesalahan.';
                            Swal.fire('Gagal', message, 'error');
                        }
                    });
                }
            });
        });

        $(document).ready(function() {
            let modal = new bootstrap.Modal(document.getElementById('diskualifikasiModal'));

            // Saat tombol klik
            $('.btn-disqualify').on('click', function() {
                const id = $(this).data('id');
                $('#pendaftaranId').val(id);
                $('#formDiskualifikasi')[0].reset();
                modal.show();
            });

            // Submit form
            $('#formDiskualifikasi').on('submit', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Yakin ingin diskualifikasi?',
                    text: "Data akan disimpan dan pendaftar tidak dapat lanjut.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, diskualifikasi!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        let formData = new FormData(this);
                        const id = $('#pendaftaranId').val();

                        $.ajax({
                            url: `/pendaftaran-management/pendaftaran/${id}/diskualifikasi`,
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                modal.hide();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: response.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            },
                            error: function(xhr) {
                                let errorMessage =
                                    'Terjadi kesalahan saat mengirim data.';

                                // Jika error validasi (422)
                                if (xhr.status === 422 && xhr.responseJSON && xhr
                                    .responseJSON.errors) {
                                    let errors = xhr.responseJSON.errors;
                                    let list = '';
                                    Object.values(errors).forEach(function(messages) {
                                        messages.forEach(function(message) {
                                            list += `• ${message}<br>`;
                                        });
                                    });

                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Validasi Gagal!',
                                        html: list,
                                    });

                                    // Jika error khusus dari controller, seperti "sudah diskualifikasi"
                                } else if (xhr.responseJSON && xhr.responseJSON.error) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal!',
                                        text: xhr.responseJSON.error,
                                    });

                                    // Error umum
                                } else if (xhr.responseJSON && xhr.responseJSON
                                    .message) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal!',
                                        text: xhr.responseJSON.message,
                                    });

                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal!',
                                        text: errorMessage,
                                    });
                                }
                            }

                        });
                    }
                });
            });

        });
    </script>

    <script>
        $(document).ready(function() {
            // Ambil ID pendaftaran pertama (atau kamu bisa loop jika ada banyak)
            let pendaftaranId = @json($pendaftaran->id ?? null);

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
                            return `
                                <button class="btn btn-sm btn-outline-primary btn-update-akademik" 
                                        title="Update Status"
                                        data-id="${data}" 
                                        data-status="${row.status}" 
                                        data-catatan="${row.catatan || ''}">
                                    <i class="fas fa-edit"></i>
                                </button>
                            `;
                        }
                    },
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
                    url: `/pendaftaran-management/pendaftaran/${pendaftaranId}/prestasi-seleksi`,
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
                    {
                        data: 'id',
                        name: 'prestasi_pendaftars.id',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                                <button class="btn btn-sm btn-outline-primary btn-update-prestasi" 
                                        title="Update Status"
                                        data-id="${data}" 
                                        data-status="${row.status}" 
                                        data-catatan="${row.catatan || ''}">
                                    <i class="fas fa-edit"></i>
                                </button>
                            `;
                        }
                    }
                ],
                order: [
                    [1, 'asc']
                ]
            });

            $(document).ready(function() {
                let pendaftaranId = @json($pendaftaran->id ?? null);

                // Buka modal dan load data
                $('#btn-edit-status').on('click', function() {
                    if (!pendaftaranId) {
                        alert('Tidak ada data pendaftaran.');
                        return;
                    }

                    // Load data nilai akademik
                    $.ajax({
                        url: `/pendaftaran-management/pendaftaran/${pendaftaranId}/mata-pelajaran-seleksi`,
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function(res) {
                            // res harus dalam format array data
                            let tbody = '';
                            res.data.forEach(function(item, index) {
                                tbody += `<tr>
                        <td>${index+1}</td>
                        <td>${item.nama_mata_pelajaran_seleksi}</td>
                        <td>${item.nilai}</td>
                        <td>
                            <select class="form-control status-select nilai-status" data-id="${item.id}">
                                <option value="Pending" ${item.status == 'Pending' ? 'selected' : ''}>Pending</option>
                                <option value="Accept" ${item.status == 'Accept' ? 'selected' : ''}>Accept</option>
                                <option value="Reject" ${item.status == 'Reject' ? 'selected' : ''}>Reject</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" class="form-control catatan-input nilai-catatan" data-id="${item.id}" value="${item.catatan  || ''}" />
                        </td>
                    </tr>`;
                            });
                            $('#modal-table-nilai tbody').html(tbody);
                        },
                        error: function() {
                            alert('Gagal load data nilai akademik');
                        }
                    });

                    // Load data prestasi
                    $.ajax({
                        url: `/pendaftaran-management/pendaftaran/${pendaftaranId}/prestasi-seleksi`,
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function(res) {
                            let tbody = '';
                            console.log(res.data);
                            res.data.forEach(function(item, index) {
                                tbody += `<tr>
                        <td>${index+1}</td>
                        <td>${item.nama_kategori_prestasi}</td>
                        <td>${item.nama_prestasi}</td>
                        <td>${item.jumlah_prestasi}</td>
                        <td>
                            <select class="form-control status-select prestasi-status" data-id="${item.id}">
                                <option value="Pending" ${item.status == 'Pending' ? 'selected' : ''}>Pending</option>
                                <option value="Accept" ${item.status == 'Accept' ? 'selected' : ''}>Accept</option>
                                <option value="Reject" ${item.status == 'Reject' ? 'selected' : ''}>Reject</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" class="form-control catatan-input prestasi-catatan" data-id="${item.id}" value="${item.catatan  || ''}" />
                        </td>
                    </tr>`;
                            });
                            $('#modal-table-prestasi tbody').html(tbody);
                        },
                        error: function() {
                            alert('Gagal load data prestasi');
                        }
                    });

                    // Tampilkan modal
                    $('#statusModal').modal('show');
                });

                // Simpan data dari modal
                $('#btn-save-status').on('click', function() {
                    const nilai = [];
                    const prestasi = [];

                    $('.nilai-status').each(function() {
                        const id = $(this).data('id');
                        const status = $(this).val();
                        const catatan = $(`.nilai-catatan[data-id="${id}"]`).val();
                        nilai.push({
                            id,
                            status,
                            catatan
                        });
                    });

                    $('.prestasi-status').each(function() {
                        const id = $(this).data('id');
                        const status = $(this).val();
                        const catatan = $(`.prestasi-catatan[data-id="${id}"]`).val();
                        prestasi.push({
                            id,
                            status,
                            catatan
                        });
                    });

                    $.ajax({
                        url: `/pendaftaran-management/pendaftaran/${pendaftaranId}/update-status-seleksi`,
                        type: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify({
                            nilai,
                            prestasi
                        }),
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire('Berhasil', response.message, 'success');
                            $('#statusModal').modal('hide');
                            // Reload tabel utama jika ada
                            $('#table-nilai-akademik').DataTable().ajax.reload();
                            $('#table-prestasi').DataTable().ajax.reload();
                        },
                        error: function() {
                            Swal.fire('Gagal',
                                'Gagal menyimpan data. Periksa input atau server.',
                                'error');
                        }
                    });
                });
            });

        });
        $(document).ready(function() {
            const modal = new bootstrap.Modal(document.getElementById('modalUpdateStatus'));

            // Buka modal untuk update akademik
            $(document).on('click', '.btn-update-akademik', function() {
                $('#update-id').val($(this).data('id'));
                $('#update-type').val('akademik');
                $('#status').val($(this).data('status'));
                $('#catatan').val($(this).data('catatan'));
                modal.show();
            });

            // Buka modal untuk update prestasi
            $(document).on('click', '.btn-update-prestasi', function() {
                $('#update-id').val($(this).data('id'));
                $('#update-type').val('prestasi');
                $('#status').val($(this).data('status'));
                $('#catatan').val($(this).data('catatan'));
                modal.show();
            });

            // Submit form
            $('#form-update-status').submit(function(e) {
                e.preventDefault();

                const id = $('#update-id').val();
                const type = $('#update-type').val();
                const url = type === 'akademik' ?
                    `/pendaftaran-management/pendaftaran/${id}/update-status-nilai-akademik` :
                    `/pendaftaran-management/pendaftaran/${id}/update-status-nilai-prestasi`;

                $.ajax({
                    url: url,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        status: $('#status').val(),
                        catatan: $('#catatan').val()
                    },
                    success: function(res) {
                        if (res.status) {
                            modal.hide();
                            $('#table-nilai-akademik').DataTable().ajax.reload(null, false);
                            $('#table-prestasi').DataTable().ajax.reload(null, false);
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: res.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: res.message
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Terjadi kesalahan. Pastikan semua data diisi dengan benar.'
                        });

                    }
                });
            });
        });
    </script>
@endpush
