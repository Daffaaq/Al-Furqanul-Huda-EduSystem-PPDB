@extends('layouts.app')

@section('content')
    @hasanyrole('super-admin|admin')
        <div class="container-fluid">

            <div class="card shadow mb-4">

                <!-- Header -->
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Dashboard</h6>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                Dashboard
                            </a>
                        </li>
                    </ol>
                </div>
                <!-- Body -->
                <div class="card-body">
                    @if ($jadwal)
                        <div class="alert alert-info alert-dismissible fade show text-center" role="alert">
                            <i class="fas fa-calendar-alt fa-lg mr-2"></i>
                            <strong>Info:</strong> Jadwal <strong>{{ $jadwal->gelombang_pendaftaran }}</strong> saat ini
                            <span class="badge badge-success ml-1">Sedang Berlangsung</span>
                        </div>
                    @else
                        <div class="alert alert-warning alert-dismissible fade show text-center" role="alert">
                            <i class="fas fa-exclamation-circle fa-lg mr-2"></i>
                            <strong>Perhatian:</strong> Belum ada jadwal pendaftaran yang dibuat atau dibuka.
                        </div>
                    @endif
                    {{-- Alert Publish Perangkingan --}}
                    @if ($publishStatus === 'published')
                        <div class="alert alert-info alert-dismissible fade show text-center" role="alert">
                            <i class="fas fa-trophy fa-lg text-warning mr-2"></i>
                            <strong>Info!</strong> Perangkingan sudah dipublikasikan.
                        </div>
                    @elseif($publishStatus === 'not_published')
                        <div class="alert alert-warning alert-dismissible fade show text-center" role="alert">
                            <i class="fas fa-exclamation-circle fa-lg text-warning mr-2"></i>
                            <strong>Info!</strong> Perangkingan belum dipublikasikan.
                        </div>
                    @endif
                    <div class="mb-4 d-flex justify-content-end">
                        <button id="btnPublishRanking" class="btn btn-primary btn-sm shadow-sm mr-3"
                            data-url="{{ route('publish-ranking') }}">
                            <i class="fas fa-bullhorn mr-2"></i> Publish Perangkingan
                        </button>

                        <button id="btnGenerateDummy" class="btn btn-success btn-sm shadow-sm mr-3"
                            data-url="{{ route('generate-dummy-pendaftar') }}">
                            <i class="fas fa-users-cog mr-2"></i> Generate Pendaftar
                        </button>

                        <button id="settingbiodata" class="btn btn-info btn-sm shadow-sm mr-3"
                            data-url="{{ route('set-biodata') }}">
                            <i class="fas fa-cogs mr-2"></i> Setting Biodata
                        </button>

                        <button id="settingUploadNilai" class="btn btn-info btn-sm shadow-sm mr-3"
                            data-url="{{ route('set-upload-nilai') }}">
                            <i class="fas fa-upload mr-2"></i> Setting Upload Nilai
                        </button>

                        <button id="btnMoveNextJadwal" class="btn btn-warning btn-sm shadow-sm"
                            data-url="{{ route('move-next-jadwal') }}">
                            <i class="fas fa-arrow-right mr-2"></i> Move ke Jadwal Berikutnya
                        </button>
                    </div>

                    <h5 class="font-weight-bold text-gray-700 mb-4">Statistik Master PPDB</h5>

                    <div class="row">
                        <!-- Card 1: Total Prestasi -->
                        <div class="col-md-4 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Total Prestasi
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalPrestasi ?? 0 }}</div>
                                    </div>
                                    <div class="icon text-success">
                                        <i class="fas fa-trophy fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card 2: Total Kategori Seleksi -->
                        <div class="col-md-4 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Total Kategori Seleksi
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalKategoriSeleksi ?? 0 }}
                                        </div>
                                    </div>
                                    <div class="icon text-warning">
                                        <i class="fas fa-chalkboard fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card 3: Total Mata Pelajaran Seleksi -->
                        <div class="col-md-4 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                            Total Mata Pelajaran Seleksi
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            {{ $totalSeleksiMataPelajaran ?? 0 }}
                                        </div>
                                    </div>
                                    <div class="icon text-info">
                                        <i class="fas fa-chalkboard-teacher fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end row -->
                    <h5 class="font-weight-bold text-gray-700 mb-3 mt-4">Statistik PPDB</h5>
                    <p class="text-muted mb-4" style="font-size: 0.9rem;">
                        Menampilkan data pendaftaran, hasil seleksi, dan kelulusan peserta PPDB berdasarkan periode aktif.
                    </p>

                    <div class="row">
                        <!-- Card Example: Total Pendaftar -->
                        <div class="col-md-4 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Total Pendaftar
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800 total-pendaftar-count">
                                            {{ $totalPendaftar ?? 0 }}</div>

                                    </div>
                                    <div class="icon text-primary">
                                        <i class="fas fa-users fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Card Example: Total Tahap Seleksi  -->
                        <div class="col-md-4 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Total Tahap Seleksi
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalTahapSeleksi ?? 0 }}
                                        </div>
                                    </div>
                                    <div class="icon text-success">
                                        <i class="fas fa-chalkboard fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Card Example: Total Kelulusan -->
                        <div class="col-md-4 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                            Total Kelulusan
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalKelulusan ?? 0 }}
                                        </div>
                                    </div>
                                    <div class="icon text-info">
                                        <i class="fas fa-graduation-cap fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Tambah lebih banyak card statistik di sini -->
                    </div>
                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div> <!-- end container -->
    @endhasanyrole
    @hasrole('calon-siswa')
        <div class="container-fluid">
            <div class="row justify-content-center mt-5">
                <div class="col-md-8">
                    <div class="card shadow border-left-primary">
                        <div class="card-body text-center py-5">
                            <h2 class="text-success font-weight-bold mb-3">
                                Selamat Datang {{ auth()->user()->name }} 🎉
                            </h2>
                            <p class="lead text-gray-800">
                                Anda berhasil masuk ke sistem <strong>Al-FURQANUL HUDA EDUSYSTEM</strong>.
                            </p>
                            @if ($biodataIncomplete)
                                <p class="text-muted">
                                    Silakan lengkapi biodata dan ikuti petunjuk pendaftaran untuk melanjutkan proses PPDB.
                                </p>

                                <a href="{{ route('biodata-calon-siswa.index') }}" class="btn btn-emerald mt-4 shadow-sm">
                                    <i class="fas fa-user-edit mr-2"></i> Lengkapi Biodata
                                </a>
                            @else
                                <p class="text-muted">
                                    Silakan ikuti petunjuk pendaftaran untuk melanjutkan proses PPDB.
                                </p>
                            @endif
                            @if ($pendaftaran)
                                <div class="mt-5">
                                    <h4 class="text-primary font-weight-bold mb-3">Langkah-langkah Pendaftaran:</h4>

                                    @if (!$pendaftaran->is_final)
                                        {{-- Belum menyelesaikan tahapan seleksi --}}
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item"><span class="badge badge-primary mr-2">1</span>
                                                Lengkapi
                                                biodata diri Anda.</li>
                                            <li class="list-group-item"><span class="badge badge-primary mr-2">2</span> Upload
                                                nilai akademik (SKL / transkrip nilai).</li>
                                            <li class="list-group-item"><span class="badge badge-primary mr-2">3</span> Upload
                                                prestasi non-akademik (jika ada).</li>
                                            <li class="list-group-item"><span class="badge badge-primary mr-2">4</span>
                                                Lakukan
                                                pendaftaran pada jadwal yang tersedia.</li>
                                        </ul>
                                    @elseif ($pendaftaran->status_final === 'Lolos')
                                        {{-- Lolos --}}
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item"><span class="badge badge-success mr-2">1</span> Anda
                                                dinyatakan <strong>LOLOS</strong>.</li>
                                            <li class="list-group-item"><span class="badge badge-success mr-2">2</span> Tekan
                                                tombol <strong>"Accepted"</strong> untuk melakukan daftar ulang.</li>
                                            <li class="list-group-item"><span class="badge badge-success mr-2">3</span> Ikuti
                                                petunjuk daftar ulang dari panitia.</li>
                                        </ul>
                                    @elseif ($pendaftaran->status_final === 'Tidak Lolos' && !$pendaftaran->status_cadangan)
                                        {{-- Tidak lolos --}}
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item"><span class="badge badge-danger mr-2">1</span> Anda
                                                dinyatakan <strong>TIDAK LOLOS</strong>.</li>
                                            <li class="list-group-item"><span class="badge badge-danger mr-2">2</span> Terima
                                                kasih telah mengikuti proses seleksi.</li>
                                        </ul>
                                    @elseif ($pendaftaran->status_final === 'Tidak Lolos' && $pendaftaran->status_cadangan)
                                        {{-- Cadangan --}}
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item"><span class="badge badge-warning mr-2">1</span> Status
                                                Anda: <strong>Cadangan</strong>.</li>
                                            <li class="list-group-item"><span class="badge badge-warning mr-2">2</span> Tekan
                                                tombol <strong>"Accepted"</strong> untuk masuk ke gelombang berikutnya.</li>
                                            <li class="list-group-item"><span class="badge badge-warning mr-2">3</span> Jika
                                                kuota tersedia, Anda akan otomatis terdaftar ke gelombang selanjutnya.</li>
                                            <li class="list-group-item"><span class="badge badge-warning mr-2">4</span> Jika
                                                sudah di gelombang 3/final, Anda akan menunggu calon yang tidak menekan tombol
                                                Accepted.</li>
                                        </ul>
                                    @elseif ($pendaftaran->status_final === 'Pending')
                                        {{-- Masih dalam proses seleksi --}}
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item"><span class="badge badge-secondary mr-2">1</span> Anda
                                                sedang menunggu hasil seleksi.</li>
                                        </ul>
                                    @endif
                                </div>
                            @endif

                            <div class="mt-5">
                                <h4 class="text-primary font-weight-bold mb-3">Bobot Penilaian Seleksi:</h4>
                                <ul class="list-group">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Akademik
                                        <span class="badge badge-primary badge-pill">{{ $bobot->bobot_akademik }}%</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Non-Akademik (Prestasi)
                                        <span class="badge badge-success badge-pill">{{ $bobot->bobot_non_akademik }}%</span>
                                    </li>
                                </ul>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endhasrole
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
    <script src="{{ asset('js/admin-actions.js') }}"></script>
    <script>
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
    </script>
@endpush
