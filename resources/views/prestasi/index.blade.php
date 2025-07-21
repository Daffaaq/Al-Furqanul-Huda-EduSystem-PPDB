@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Master Prestasi</h6>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('kategori-prestasi.index') }}"
                            class="{{ request()->routeIs('kategori-prestasi.index') ? 'active' : '' }}">
                            Prestasi
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
                        @can('kategori-prestasi.create')
                            <div class="d-flex flex-column flex-sm-row align-items-start justify-content-start mt-3 mt-sm-0">
                                <a href="{{ route('kategori-prestasi.create') }}" class="btn btn-sm btn-primary shadow-sm">
                                    <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Prestasi
                                </a>
                            </div>
                        @endcan
                    </div>

                    <!-- Tab Pane Navigation -->
                    <ul class="nav nav-tabs border-bottom border-primary mb-3" id="prestasiTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active text-primary fw-bold" id="prestasi-tab" data-bs-toggle="tab"
                                data-bs-target="#prestasi" role="tab">
                                Prestasi
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link text-primary fw-bold" id="kategori-prestasi-tab" data-bs-toggle="tab"
                                data-bs-target="#kategori-prestasi" role="tab">
                                Kategori Prestasi
                            </button>
                        </li>
                    </ul>


                    <!-- Tab Content -->
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="prestasi" role="tabpanel"
                            aria-labelledby="prestasi-tab">
                            <!-- Tabel Prestasi -->
                            <table class="table table-bordered" id="prestasiTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Prestasi</th>
                                        <th>Point</th>
                                        <th>Kategori</th>
                                        <th>Periode</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="kategori-prestasi" role="tabpanel"
                            aria-labelledby="kategori-prestasi-tab">
                            <!-- Tabel Kategori Prestasi -->
                            <table class="table table-bordered" id="kategoriPrestasiTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Kategori Prestasi</th>
                                        <th>Periode</th>
                                        <th>Jumlah Prestasi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
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
    <!-- Ensure you have these in your 'head' or at the end of 'body' section -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            let table = $('#prestasiTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('kategori-prestasi.list') }}",
                    type: 'POST',
                    data: function(d) {
                        d._token = '{{ csrf_token() }}';
                        d.periode_id = $('#filter_periode').val();
                    },
                    error: function(xhr, error) {
                        Swal.fire('Error!', 'Gagal memuat data.', 'error');
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama_prestasi',
                        name: 'nama_prestasi'
                    },
                    {
                        data: 'point_prestasi',
                        name: 'point_prestasi'
                    },
                    {
                        data: 'nama_kategori_prestasi',
                        name: 'kategori_prestasis.nama_kategori_prestasi'
                    },
                    {
                        data: 'nama_periode',
                        name: 'periodes.nama_periode'
                    },
                    {
                        data: 'kategori_prestasi_id',
                        name: 'kategori_prestasi_id',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            let editUrl = `/master-management/kategori-prestasi/${data}/edit`;
                            let deleteUrl = `/master-management/kategori-prestasi/${row.id}`;
                            return `
                                <a href="${editUrl}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                <button class="btn btn-sm btn-danger" onclick="confirmDelete('${deleteUrl}', '${data}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            `;
                        }
                    }
                ]
            });

            let KategoriPrestasitable = $('#kategoriPrestasiTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('kategori-prestasi.prestasi.list') }}",
                    type: 'POST',
                    data: function(d) {
                        d._token = '{{ csrf_token() }}';
                        d.periode_id = $('#filter_periode').val();
                    },
                    error: function(xhr, error) {
                        Swal.fire('Error!', 'Gagal memuat data.', 'error');
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
                        name: 'nama_kategori_prestasi'
                    },
                    {
                        data: 'nama_periode',
                        name: 'periodes.nama_periode'
                    },
                    {
                        data: 'jumlah_prestasi',
                        name: 'jumlah_prestasi'
                    },
                    {
                        data: 'id',
                        name: 'id',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            let addPrestasiUrl =
                                `/master-management/kategori-prestasi/${data}/add-prestasi`;
                            let editUrl = `/master-management/kategori-prestasi/${data}/kategori`;
                            let deleteUrl = `/master-management/kategori-prestasi/${data}/kategori`;
                            return `
                                <a href="${addPrestasiUrl}" class="btn btn-sm btn-info"><i class="bi bi-plus"></i></a>
                                <a href="${editUrl}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                <button class="btn btn-sm btn-danger" onclick="deleteKategori('${deleteUrl}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            `;
                        }
                    }
                ]
            });

            $('#filter_periode').on('change', function() {
                const activeTab = $('#prestasiTab .nav-link.active').attr('id');

                if (activeTab === 'prestasi-tab') {
                    table.ajax.reload();
                } else if (activeTab === 'kategori-prestasi-tab') {
                    KategoriPrestasitable.ajax.reload();
                }
            });

            $('#prestasiTab button[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
                const targetTab = $(e.target).attr('id'); // ID tab aktif

                // Reset filter ke "Semua"
                $('#filter-tipe').val('');

                // Reload DataTable sesuai tab aktif
                if (targetTab === 'prestasi-tab') {
                    table.ajax.reload();
                } else if (targetTab === 'kategori-prestasi-tab') {
                    KategoriPrestasitable.ajax.reload();
                }
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

        function confirmDelete(deleteUrl, kategoriPrestasiId) {
            $.ajax({
                url: `/master-management/kategori-prestasi/${kategoriPrestasiId}/prestasi-count`,
                method: 'GET',
                success: function(data) {
                    if (data.count === 1) {
                        // Hanya 1 prestasi tersisa, tanya user mau hapus prestasi saja atau prestasi + kategori
                        Swal.fire({
                            title: 'Hapus Prestasi Terakhir?',
                            text: "Ini adalah prestasi terakhir dalam kategori ini. Apakah Anda ingin menghapus kategori prestasi juga?",
                            icon: 'warning',
                            showCancelButton: true,
                            showDenyButton: true,
                            confirmButtonText: 'Hapus Prestasi Saja',
                            denyButtonText: 'Hapus Prestasi & Kategori',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Hapus prestasi saja
                                deletePrestasi(deleteUrl);
                            } else if (result.isDenied) {
                                // Hapus prestasi & kategori
                                deletePrestasiAndKategori(deleteUrl, kategoriPrestasiId);
                            }
                            // batal otomatis handle
                        });
                    } else {
                        // Lebih dari 1 prestasi, langsung hapus prestasi
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
                                deletePrestasi(deleteUrl);
                            }
                        });
                    }
                },
                error: function() {
                    Swal.fire('Error!', 'Gagal memeriksa data prestasi.', 'error');
                }
            });
        }

        function deletePrestasi(url) {
            $.ajax({
                url: url,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Berhasil!', response.message, 'success');
                        $('#prestasiTable').DataTable().ajax.reload();
                    } else {
                        Swal.fire('Gagal!', response.message || 'Terjadi kesalahan.', 'error');
                    }
                },
                error: function() {
                    Swal.fire('Gagal!', 'Tidak dapat terhubung ke server.', 'error');
                }
            });
        }

        function deletePrestasiAndKategori(prestasiUrl, kategoriId) {
            $.ajax({
                url: prestasiUrl,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        // Jika berhasil hapus prestasi, lanjut hapus kategori
                        $.ajax({
                            url: `/master-management/kategori-prestasi/${kategoriId}/kategori`,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(res) {
                                if (res.success) {
                                    Swal.fire('Berhasil!',
                                        'Prestasi dan kategori berhasil dihapus.', 'success');
                                    $('#prestasiTable').DataTable().ajax.reload();
                                } else {
                                    Swal.fire('Gagal!', res.message ||
                                        'Terjadi kesalahan saat hapus kategori.', 'error');
                                }
                            },
                            error: function() {
                                Swal.fire('Gagal!', 'Tidak dapat menghapus kategori.', 'error');
                            }
                        });
                    } else {
                        Swal.fire('Gagal!', response.message || 'Terjadi kesalahan saat hapus prestasi.',
                            'error');
                    }
                },
                error: function() {
                    Swal.fire('Gagal!', 'Tidak dapat menghapus prestasi.', 'error');
                }
            });
        }

        function deleteKategori(url) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
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
                                $('#kategoriPrestasiTable').DataTable().ajax.reload();
                            } else {
                                Swal.fire('Gagal!', response.message ||
                                    'Terjadi kesalahan saat hapus prestasi.', 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Gagal!', 'Tidak dapat menghapus prestasi.', 'error');
                        }
                    });
                }
            });
        }
    </script>
@endpush
