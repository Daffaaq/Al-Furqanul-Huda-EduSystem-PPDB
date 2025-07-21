@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Riwayat Status Seleksi</h6>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('pendaftaran.index') }}">Pendaftaran</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Riwayat Status</li>
                </ol>
            </div>
            <div class="card-body">
                {{-- alert --}}
                
                <div class="mb-4">
                    <h6 class="font-weight-bold">Nama Calon Siswa:</h6>
                    <p class="mb-0">{{ $pendaftaran->biodataCalonSiswa->nama_calon_siswa ?? '-' }}</p>
                </div>

                <h5 class="text-primary">Riwayat Nilai Akademik</h5>
                <div class="table-responsive mb-5">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th>Nama Mata Pelajaran Seleksi</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Catatan</th>
                                <th>Petugas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($nilaiHistories as $history)
                                <tr>
                                    <td>{{ $history->nilai_akademik_pendaftar->mataPelajaranSeleksi->nama_mata_pelajaran_seleksi ?? '-' }}
                                    </td>
                                    <td>{{ $history->created_at->format('d-m-Y H:i') }}</td>
                                    <td>
                                        @if ($history->status === 'Accept')
                                            <span class="badge badge-success">Diterima</span>
                                        @elseif ($history->status === 'Reject')
                                            <span class="badge badge-danger">Ditolak</span>
                                        @else
                                            <span class="badge badge-secondary">Tidak diketahui</span>
                                        @endif
                                    </td>
                                    <td>{{ $history->catatan ?? '-' }}</td>
                                    <td>{{ $history->nama_petugas ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Belum ada riwayat nilai akademik.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <h5 class="text-primary">Riwayat Prestasi</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th>Kategori Prestasi</th>
                                <th>Nama Prestasi</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Catatan</th>
                                <th>Petugas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($prestasiHistories as $history)
                                <tr>
                                    <td>{{ $history->prestasi_pendaftar->prestasi->kategori_prestasi->nama_kategori_prestasi ?? '-' }}
                                    </td>
                                    <td>{{ $history->prestasi_pendaftar->prestasi->nama_prestasi ?? '-' }}</td>
                                    <td>{{ $history->created_at->format('d-m-Y H:i') }}</td>
                                    <td>
                                        @if ($history->status === 'Accept')
                                            <span class="badge badge-success">Diterima</span>
                                        @elseif ($history->status === 'Reject')
                                            <span class="badge badge-danger">Ditolak</span>
                                        @else
                                            <span class="badge badge-secondary">Tidak diketahui</span>
                                        @endif
                                    </td>
                                    <td>{{ $history->catatan ?? '-' }}</td>
                                    <td>{{ $history->nama_petugas ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Belum ada riwayat prestasi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 d-flex justify-content-end">
                    <a href="{{ route('pendaftaran.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .badge {
            font-size: 0.9rem;
        }

        .breadcrumb {
            background-color: transparent;
            padding: 0;
            margin-bottom: 0;
        }

        .breadcrumb-item a {
            color: #464646;
            text-decoration: none;
        }

        .breadcrumb-item a:hover {
            text-decoration: underline;
        }

        .breadcrumb-item.active {
            font-weight: bold;
            color: #007bff;
        }
    </style>
@endpush
