@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Mata Pelajaran Seleksi</h6>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('mata-pelajaran-seleksi.index') }}"
                            class="{{ request()->routeIs('mata-pelajaran-seleksi.index') ? 'active' : '' }}">
                            Mata Pelajaran Seleksi
                        </a>
                    </li>
                </ol>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <div class="d-flex flex-wrap justify-content-between align-items-end mb-4">
                        <div class="me-3">
                            <label for="filter-tipe" class="form-label fw-bold mb-1">Filter Periode:</label>
                            <select id="filter_periode" class="form-select shadow-sm border-primary"
                                style="min-width: 180px;">
                                <option value="">-- Semua Periode --</option>
                                @foreach (DB::table('periodes')->get() as $periode)
                                    <option value="{{ $periode->id }}">{{ $periode->nama_periode }}</option>
                                @endforeach
                            </select>
                        </div>
                        @can('mata-pelajaran-seleksi.create')
                            <div class="d-flex flex-column flex-sm-row align-items-start justify-content-start mt-3 mt-sm-0">
                                <a href="{{ route('mata-pelajaran-seleksi.create') }}" class="btn btn-sm btn-primary shadow-sm">
                                    <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Mata Pelajaran Seleksi
                                </a>
                            </div>
                        @endcan

                    </div>
                    {{-- <div class="row mb-3">
                        <!-- Filter Periode -->
                        <div class="col-md-6">
                            <label for="filter_periode">Filter Periode:</label>
                            <select id="filter_periode" class="form-control">
                                <option value="">-- Semua Periode --</option>
                                @foreach (DB::table('periodes')->get() as $periode)
                                    <option value="{{ $periode->id }}">{{ $periode->nama_periode }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Tombol Tambah Periode -->
                        <div class="col-md-6 d-flex justify-content-end align-items-center">
                            <a href="{{ route('periode.create') }}" class="btn btn-sm btn-primary shadow-sm">
                                <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Periode
                            </a>
                        </div>
                    </div> --}}
                    <table class="table table-bordered" id="mataPelajaranSeleksiTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Mata Pelajaran Seleksi</th>
                                <th>Periode</th>
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
            let table = $('#mataPelajaranSeleksiTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('mata-pelajaran-seleksi.list') }}",
                    type: 'POST',
                    data: function(d) {
                        d._token = '{{ csrf_token() }}'; // Pastikan CSRF token dikirimkan
                        d.periode_id = $('#filter_periode').val(); // Tambahkan filter periode
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
                        data: 'nama_mata_pelajaran_seleksi',
                        name: 'nama_mata_pelajaran_seleksi'
                    },
                    {
                        data: 'nama_periode',
                        name: 'periodes.nama_periode'
                    },
                    {
                        data: 'id',
                        name: 'id',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            let editUrl = `/master-management/mata-pelajaran-seleksi/${data}/edit`;
                            let deleteUrl = `/master-management/mata-pelajaran-seleksi/${data}`;
                            return `
                    <a href="${editUrl}" class="btn btn-sm btn-warning">
                        <i class="bi bi-pencil"></i>
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
                                $('#mataPelajaranSeleksiTable').DataTable().ajax.reload(); // Fix ID
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
