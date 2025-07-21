@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Manajemen Periode</h6>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('periode.index') }}"
                            class="{{ request()->routeIs('periode.index') ? 'active' : '' }}">Periode</a>
                    </li>
                </ol>
            </div>
            <div class="card-body">
                @can('periode.create')
                    <div class="d-flex justify-content-end mb-3">
                        <a href="{{ route('periode.create') }}"
                            class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                            <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Periode
                        </a>
                    </div>
                @endcan
                <div class="table-responsive">
                    <table class="table table-bordered" id="PeriodeTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Periode</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- DataTables akan mengisi -->
                        </tbody>
                    </table>
                </div>
            </div>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        function reloadPeriodeTopbar() {
            $.get('/periode/active/topbar', function(data) {
                $('#periodeAktifTopbar').html(data);
            });
        }
        $(document).ready(function() {

            let table = $('#PeriodeTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('periode.list') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama_periode',
                        name: 'nama_periode'
                    },
                    {
                        data: 'status_periode',
                        name: 'status_periode',
                        render: function(data) {
                            //badge
                            // 1 aktif, 0 tidak aktif
                            if (data == 1) {
                                return '<span class="badge badge-success">Aktif</span>';
                            } else {
                                return '<span class="badge badge-danger">Tidak Aktif</span>';
                            }
                        }
                    },
                    {
                        data: 'id',
                        name: 'id',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            let editUrl = `/master-management/periode/${data}/edit`;
                            let deleteUrl = `/master-management/periode/${data}`;

                            // Tombol toggle status
                            let statusBtnClass = row.status_periode == 1 ? 'btn-success' :
                                'btn-secondary';
                            let statusBtnText = row.status_periode == 1 ? 'Nonaktifkan' :
                                'Aktifkan';

                            return `
                                <a href="${editUrl}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button class="btn btn-sm btn-danger" onclick="confirmDelete('${deleteUrl}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <button class="btn btn-sm ${statusBtnClass}" onclick="toggleStatus(${data})">
                                    ${statusBtnText}
                                </button>
                            `;
                        }
                    }
                ]
            });

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

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: '{{ session('error') }}',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                });
            @endif
        });

        function confirmDelete(url) {
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Data ini akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire('Berhasil!', response.message, 'success');
                                $('#PeriodeTable').DataTable().ajax.reload();
                                reloadPeriodeTopbar();
                            } else {
                                Swal.fire('Gagal!', response.message || 'Terjadi kesalahan.', 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Gagal!', 'Tidak dapat terhubung ke server.', 'error');
                        }
                    });
                }
            });
        }

        function toggleStatus(id) {
            // Step 1: Cek dulu apakah ada periode lain yang aktif
            $.get(`/master-management/periode/${id}/check-active-except`, function(data) {
                if (data.has_other_active) {
                    // Ada periode lain aktif, kasih konfirmasi tambahan
                    Swal.fire({
                        title: 'Masih ada periode aktif lain',
                        text: "Apakah Anda yakin ingin menonaktifkan periode aktif lain dan mengaktifkan periode ini?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, lanjutkan',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Lanjutkan update status
                            updateStatusAjax(id);
                        }
                    });
                } else {
                    // Tidak ada periode lain aktif, langsung toggle
                    updateStatusAjax(id);
                }
            });
        }

        function updateStatusAjax(id) {
            Swal.fire({
                title: 'Yakin ingin mengganti status periode?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, ganti',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/master-management/periode/${id}/update-status`,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire('Berhasil!', response.message, 'success');
                                $('#PeriodeTable').DataTable().ajax.reload();
                                reloadPeriodeTopbar();
                            } else {
                                Swal.fire('Gagal!', response.message || 'Terjadi kesalahan.', 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Gagal!', 'Tidak dapat terhubung ke server.', 'error');
                        }
                    });
                }
            });
        }
    </script>
@endpush
