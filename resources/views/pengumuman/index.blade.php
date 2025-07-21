@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Hasil Perangkingan Calon Siswa</h6>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('pengumuman.index') }}"
                            class="{{ request()->routeIs('pengumuman.index') ? 'active' : '' }}">
                            Pengumuman
                        </a>
                    </li>
                </ol>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <div class="row align-items-end mb-4 gx-3 gy-2">
                        <div class="col-md-3 col-sm-6">
                            <label for="filter_periode" class="form-label fw-semibold">Filter Periode</label>
                            <select id="filter_periode" class="form-select border-primary shadow-sm">
                                <option value="">-- Semua Periode --</option>
                                @foreach (DB::table('periodes')->get() as $periode)
                                    <option value="{{ $periode->id }}">{{ $periode->nama_periode }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 col-sm-6">
                            <label for="filter_gelombang" class="form-label fw-semibold">Filter Gelombang</label>
                            <select id="filter_gelombang" class="form-select border-primary shadow-sm">
                                <option value="">-- Pilih Periode Dulu --</option>
                            </select>
                        </div>
                    </div>

                    <table class="table table-bordered" id="pendaftaranTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Calon Siswa</th>
                                <th>Gelombang</th>
                                <th>Status Final</th>
                                <th>Status Diterima</th>
                                <th>Status Cadangan</th>
                                <th>Status Aktif</th>
                                <th>Diskualifikasi</th>
                                <th>Finalisasi</th>
                                <th>Total Nilai</th>
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
    <script>
        $(document).ready(function() {
            let table = $('#pendaftaranTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('pengumuman.list') }}",
                    type: 'POST',
                    data: function(d) {
                        d._token = '{{ csrf_token() }}';
                        d.periode_id = $('#filter_periode').val();
                        d.gelombang = $('#filter_gelombang').val();
                    },
                    error: function(xhr) {
                        console.error("AJAX Error: ", xhr.responseText);
                        Swal.fire('Error!', 'Gagal mengambil data dari server.', 'error');
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama_calon_siswa',
                        name: 'biodata_calon_siswas.nama_calon_siswa'
                    },
                    {
                        data: 'gelombang_pendaftaran',
                        name: 'jadwal_pendaftarans.gelombang_pendaftaran'
                    },
                    {
                        data: 'status_final',
                        name: 'status_final',
                        render: function(data) {
                            if (data == 'Lolos') {
                                return '<span class="badge bg-success text-white">' + data +
                                    '</span>';
                            } else if (data == 'Tidak Lolos') {
                                return '<span class="badge bg-danger text-white">' + data +
                                    '</span>';
                            } else {
                                return '<span class="badge bg-warning text-white">' + data +
                                    '</span>';
                            }
                        }
                    },
                    {
                        data: 'status_accept',
                        name: 'status_accept',
                        render: function(data) {
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
                        data: 'status_cadangan',
                        name: 'status_cadangan',
                        render: function(data) {
                            return data ? '<span class="badge bg-success text-white">Ya</span>' :
                                '<span class="badge bg-danger text-white">Tidak</span>';
                        }
                    },
                    {
                        data: 'status_aktif',
                        name: 'status_aktif',
                        render: function(data) {
                            return data ? '<span class="badge bg-success text-white">Ya</span>' :
                                '<span class="badge bg-danger text-white">Tidak</span>';
                        }
                    },
                    {
                        data: 'status_diskualifikasi',
                        name: 'status_diskualifikasi',
                        render: function(data) {
                            return data ? '<span class="badge bg-success text-white">Ya</span>' :
                                '<span class="badge bg-danger text-white">Tidak</span>';
                        }
                    },
                    {
                        data: 'is_final',
                        name: 'is_final',
                        render: function(data) {
                            return data ? '<span class="badge bg-success text-white">Ya</span>' :
                                '<span class="badge bg-danger text-white">Tidak</span>';
                        }
                    },
                    {
                        data: 'total_nilai',
                        name: 'total_nilai',
                        render: function(data) {
                            return data ?? '-';
                        }
                    }
                ]
            });

            $('#filter_periode').change(function() {
                let periodeId = $(this).val();

                $('#filter_gelombang').html('<option value="">-- Semua Gelombang --</option>');

                if (periodeId !== '') {
                    $.ajax({
                        url: "{{ route('filter.gelombang') }}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            periode_id: periodeId
                        },
                        success: function(data) {
                            let options = '<option value="">-- Semua Gelombang --</option>';
                            data.forEach(function(item) {
                                options += `<option value="${item}">${item}</option>`;
                            });
                            $('#filter_gelombang').html(options);
                        }
                    });
                }

                table.ajax.reload();
            });

            $('#filter_gelombang').change(function() {
                table.ajax.reload();
            });
        });
    </script>
@endpush
