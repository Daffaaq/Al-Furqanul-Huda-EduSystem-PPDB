@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Detail Jadwal Pendaftaran</h6>
            </div>
            <div class="card-body">
                <!-- Non-Timeline Data (Kuota, Status, etc.) -->
                <div class="row mt-4">
                    {{-- nama_jadwal_pendaftaran --}}
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="m-0 font-weight-bold text-primary">Nama Jadwal Pendaftaran</h6>
                            </div>
                            <div class="card-body">
                                <p>{{ $jadwalPendaftaran->nama_jadwal_pendaftaran }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <!-- Kuota Pendaftaran -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="m-0 font-weight-bold text-primary">Kuota Pendaftaran</h6>
                            </div>
                            <div class="card-body">
                                <p>{{ $jadwalPendaftaran->kuota_pendaftaran }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Kuota Penerimaan -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="m-0 font-weight-bold text-primary">Kuota Penerimaan</h6>
                            </div>
                            <div class="card-body">
                                <p>{{ $jadwalPendaftaran->kuota_penerimaan }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- kuota_akun --}}
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="m-0 font-weight-bold text-primary">Kuota Akun</h6>
                            </div>
                            <div class="card-body">
                                <p>{{ $jadwalPendaftaran->kuota_akun }}</p>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Status Jadwal Pendaftaran -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="m-0 font-weight-bold text-primary">Status Jadwal Pendaftaran</h6>
                            </div>
                            <div class="card-body">
                                <p>{{ $jadwalPendaftaran->status_jadwal_pendaftaran }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Timeline for Dates -->
                <div class="timeline-container">
                    <h5 class="timeline-title">Jadwal Pendaftaran</h5>

                    <div class="timeline">
                        <!-- Timeline Item: Tanggal Mulai Jadwal Pendaftaran -->
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content left">
                                <h6 class="timeline-subtitle">Tanggal Mulai Pendaftaran</h6>
                                <p class="timeline-text">
                                    {{ \Carbon\Carbon::parse($jadwalPendaftaran->tanggal_mulai_jadwal_pendaftaran)->format('d-m-Y') }}
                                </p>
                            </div>
                        </div>

                        <!-- Timeline Item: Deadline Biodata Calon Siswa -->
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content left">
                                <h6 class="timeline-subtitle">Deadline Biodata Calon Siswa</h6>
                                <p class="timeline-text">
                                    {{ \Carbon\Carbon::parse($jadwalPendaftaran->deadline_biodata_calon_siswa)->format('d-m-Y') }}
                                </p>
                            </div>
                        </div>

                        <!-- Timeline Item: Tanggal Mulai Verifikasi -->
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content left">
                                <h6 class="timeline-subtitle">Tanggal Mulai Verifikasi</h6>
                                <p class="timeline-text">
                                    {{ \Carbon\Carbon::parse($jadwalPendaftaran->tanggal_mulai_verifikasi)->format('d-m-Y') }}
                                </p>
                            </div>
                        </div>

                        <!-- Timeline Item: Deadline Upload Pendaftaran -->
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content right">
                                <h6 class="timeline-subtitle">Deadline Upload Pendaftaran</h6>
                                <p class="timeline-text">
                                    {{ \Carbon\Carbon::parse($jadwalPendaftaran->deadline_upload_pendaftaran)->format('d-m-Y') }}
                                </p>
                            </div>
                        </div>

                        <!-- Timeline Item: Tanggal Selesai Verifikasi -->
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content right">
                                <h6 class="timeline-subtitle">Tanggal Selesai Verifikasi</h6>
                                <p class="timeline-text">
                                    {{ \Carbon\Carbon::parse($jadwalPendaftaran->tanggal_selesai_verifikasi)->format('d-m-Y') }}
                                </p>
                            </div>
                        </div>



                        <!-- Timeline Item: Tanggal Selesai Jadwal Pendaftaran -->
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content right">
                                <h6 class="timeline-subtitle">Tanggal Selesai Pendaftaran</h6>
                                <p class="timeline-text">
                                    {{ \Carbon\Carbon::parse($jadwalPendaftaran->tanggal_selesai_jadwal_pendaftaran)->format('d-m-Y') }}
                                </p>
                            </div>
                        </div>

                        <!-- Timeline Item: Pengumuman Hasil Seleksi -->
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content left">
                                <h6 class="timeline-subtitle">Pengumuman Hasil Seleksi</h6>
                                <p class="timeline-text">
                                    {{ \Carbon\Carbon::parse($jadwalPendaftaran->pengumuman_hasil_seleksi)->format('d-m-Y') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('jadwal-pendaftaran.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .timeline-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .timeline {
            position: relative;
            padding: 20px;
            border-left: 3px solid #007bff;
            background-color: #f8f9fc;
            margin-top: 40px;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            width: 100%;
        }

        .timeline-item .timeline-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: #007bff;
            position: absolute;
            left: -7px;
            top: 8px;
        }

        .timeline-content {
            padding: 10px;
            margin-left: 20px;
            margin-right: 20px;
            background-color: #ffffff;
            border-radius: 6px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 80%;
        }

        .timeline-title {
            font-size: 1.2rem;
            color: #343a40;
            font-weight: 600;
        }

        .timeline-subtitle {
            font-size: 1.1rem;
            color: #5a5c69;
            font-weight: 500;
        }

        .timeline-text {
            font-size: 1rem;
            color: #6c757d;
        }

        .timeline-item.left .timeline-content {
            text-align: left;
        }

        .timeline-item.right .timeline-content {
            text-align: right;
        }

        .card {
            margin-bottom: 1.5rem;
        }

        .btn-secondary {
            background-color: #6c757d;
            border: 1px solid #6c757d;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }
    </style>
@endpush
