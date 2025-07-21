@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Jadwal Pendaftaran</h6>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('jadwal-pendaftaran.index') }}"
                            class="{{ request()->routeIs('jadwal-pendaftaran.index') ? 'active' : '' }}">
                            Jadwal Pendaftaran
                        </a>
                    </li>
                </ol>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <div class="d-flex flex-wrap justify-content-between align-items-end mb-4">
                        <div class="me-3">
                            <label for="filter-periode" class="form-label fw-bold mb-1">Filter Periode:</label>
                            <select id="filter_periode" class="form-select shadow-sm border-primary"
                                style="min-width: 180px;">
                                <option value="">-- Semua Periode --</option>
                                @foreach (DB::table('periodes')->get() as $periode)
                                    <option value="{{ $periode->id }}">{{ $periode->nama_periode }}</option>
                                @endforeach
                            </select>
                        </div>
                        @can('jadwal-pendaftaran.create')
                            <div class="d-flex flex-column flex-sm-row align-items-start justify-content-start mt-3 mt-sm-0">
                                <a href="{{ route('jadwal-pendaftaran.create') }}" class="btn btn-sm btn-primary shadow-sm">
                                    <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Jadwal Pendaftaran
                                </a>
                            </div>
                        @endcan
                    </div>
                    <table class="table table-bordered" id="jadwalPendaftaranTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Jadwal</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Selesai</th>
                                <th>Periode</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
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
        $(document).ready(function() {
            let table = $('#jadwalPendaftaranTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('jadwal-pendaftaran.list') }}",
                    type: 'POST',
                    data: function(d) {
                        d._token = '{{ csrf_token() }}'; // Ensure CSRF token is sent
                        d.periode_id = $('#filter_periode').val(); // Add filter for periode
                    },
                    error: function(xhr, error, thrown) {
                        console.error("Error fetching data: ", error);
                        Swal.fire('Error!', 'Terjadi kesalahan saat memuat data', 'error');
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama_jadwal_pendaftaran',
                        name: 'nama_jadwal_pendaftaran'
                    },
                    {
                        data: 'tanggal_mulai_jadwal_pendaftaran',
                        name: 'tanggal_mulai_jadwal_pendaftaran'
                    },
                    {
                        data: 'tanggal_selesai_jadwal_pendaftaran',
                        name: 'tanggal_selesai_jadwal_pendaftaran'
                    },
                    {
                        data: 'nama_periode',
                        name: 'periodes.nama_periode'
                    },
                    {
                        data: 'status_jadwal_pendaftaran',
                        name: 'status_jadwal_pendaftaran',
                        render: function(data) {
                            if (data == 'Opened') {
                                return '<span class="badge bg-success text-white">' + data + '</span>';
                            } else if (data == 'Ongoing') {
                                return '<span class="badge bg-warning text-white">' + data + '</span>';
                            } else {
                                return '<span class="badge bg-danger text-white">' + data + '</span>';
                            }
                        }
                    },
                    {
                        data: 'id',
                        name: 'id',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            let editUrl = `/master-management/jadwal-pendaftaran/${data}/edit`;
                            let showUrl = `/master-management/jadwal-pendaftaran/${data}`;
                            let deleteUrl = `/master-management/jadwal-pendaftaran/${data}`;
                            return `
                                <a href="${editUrl}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="${showUrl}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <button class="btn btn-sm btn-danger" onclick="confirmDelete('${deleteUrl}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            `;
                        }
                    }
                ]
            });
            // Trigger reload on filter change
            $('#filter_periode').change(function() {
                table.ajax.reload();
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
                                $('#jadwalPendaftaranTable').DataTable().ajax.reload();
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
