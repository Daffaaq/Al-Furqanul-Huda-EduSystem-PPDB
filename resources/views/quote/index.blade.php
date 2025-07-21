@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Quote</h6>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('quote.index') }}" class="{{ request()->routeIs('quote.index') ? 'active' : '' }}">
                            Quote
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
                        @can('quote.create')
                            <div class="d-flex flex-column flex-sm-row align-items-start justify-content-start mt-3 mt-sm-0">
                                <a href="{{ route('quote.create') }}" class="btn btn-sm btn-primary shadow-sm">
                                    <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Quote
                                </a>
                            </div>
                        @endcan
                    </div>
                    <table class="table table-bordered" id="quoteTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Quotes</th>
                                <th>Author</th>
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
            let table = $('#quoteTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('quote.list') }}",
                    type: 'POST',
                    data: function(d) {
                        d._token = '{{ csrf_token() }}';
                        d.periode_id = $('#filter_periode').val();
                    },
                    error: function() {
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
                        data: 'quotes',
                        name: 'quotes'
                    },
                    {
                        data: 'author',
                        name: 'author'
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
                            let editUrl = `/master-management/quote/${data}/edit`;
                            let showUrl = `/master-management/quote/${data}`;
                            let deleteUrl = `/master-management/quote/${data}`;
                            return `
                            <a href="${editUrl}" class="btn btn-sm btn-warning" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="${showUrl}" class="btn btn-sm btn-info" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                            <button class="btn btn-sm btn-danger" onclick="confirmDelete('${deleteUrl}')" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        `;
                        }
                    }
                ]
            });

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
                                $('#quoteTable').DataTable().ajax.reload();
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
