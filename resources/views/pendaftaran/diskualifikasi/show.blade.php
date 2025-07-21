@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Detail Diskualifikasi</h6>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('pendaftaran.index') }}">Pendaftaran</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Diskualifikasi</li>
                </ol>
            </div>

            <div class="card-body">
                @if ($diskualifikasiPendaftar)
                    <div class="mb-4">
                        <h6 class="font-weight-bold">Nama Calon Siswa:</h6>
                        <p class="mb-0">
                            {{ $diskualifikasiPendaftar->pendaftaran->biodataCalonSiswa->nama_calon_siswa ?? '-' }}</p>
                    </div>

                    <div class="mb-4">
                        <h6 class="font-weight-bold">Alasan Diskualifikasi:</h6>
                        <p class="mb-0">{{ $diskualifikasiPendaftar->alasan_diskualifikasi }}</p>
                    </div>

                    <div class="mb-4">
                        <h6 class="font-weight-bold">Tanggal Diskualifikasi:</h6>
                        <p class="mb-0">
                            {{ \Carbon\Carbon::parse($diskualifikasiPendaftar->tanggal_diskualifikasi)->translatedFormat('d F Y') }}
                            -
                            {{ $diskualifikasiPendaftar->created_at->format('H:i:s') }}
                        </p>
                    </div>

                    <div class="mb-4">
                        <h6 class="font-weight-bold">Petugas:</h6>
                        <p class="mb-0">{{ $diskualifikasiPendaftar->petugas->name ?? '-' }}</p>
                    </div>

                    @if ($diskualifikasiPendaftar->bukti_diskualifikasi)
                        <div class="mb-4">
                            <h6 class="font-weight-bold">Bukti Diskualifikasi:</h6>
                            @if (Str::startsWith($diskualifikasiPendaftar->file_type, 'image'))
                                <img src="{{ asset('storage/' . $diskualifikasiPendaftar->bukti_diskualifikasi) }}"
                                    alt="Bukti Diskualifikasi" class="img-fluid" style="max-width: 400px;">
                            @else
                                <a href="{{ asset('storage/' . $diskualifikasiPendaftar->bukti_diskualifikasi) }}"
                                    target="_blank" class="btn btn-outline-primary">
                                    <i class="fas fa-file-download"></i> Lihat/Dowload File
                                </a>
                            @endif
                        </div>
                    @endif
                @else
                    <div class="alert alert-warning">
                        Data diskualifikasi tidak ditemukan.
                    </div>
                @endif

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

        img.img-fluid {
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 4px;
            background-color: #fff;
        }
    </style>
@endpush
