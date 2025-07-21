@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <!-- Page Heading -->
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Biodata Calon Siswa</h6>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('biodata-calon-siswa.index') }}"
                            class="{{ request()->routeIs('biodata-calon-siswa.index') ? 'active' : '' }}">
                            Biodata Calon Siswa
                        </a>
                    </li>
                </ol>
            </div>
            <div class="card-body">
                <div class="d-flex mb-4">
                    <!-- Foto Formal -->
                    <div class="mr-4">
                        @if ($biodataCalonSiswa->foto_formal_calon_siswa)
                            <img src="{{ asset('storage/' . $biodataCalonSiswa->foto_formal_calon_siswa) }}"
                                alt="Foto Formal" class="img-thumbnail" width="150">
                        @else
                            <em>Tidak ada foto</em>
                        @endif
                    </div>

                    <!-- Tabel Biodata -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th width="30%">Nama</th>
                                    <td>{{ $biodataCalonSiswa->nama_calon_siswa ?? 'Data belum diisi' }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $biodataCalonSiswa->email_calon_siswa ?? 'Data belum diisi' }}</td>
                                </tr>
                                <tr>
                                    <th>Jenis Kelamin</th>
                                    <td>{{ $biodataCalonSiswa->jenis_kelamin_calon_siswa ?? 'Data belum diisi' }}</td>
                                </tr>
                                <tr>
                                    <th>Tempat & Tanggal Lahir</th>
                                    <td>{{ $biodataCalonSiswa->tempat_tanggal_lahir_calon_siswa ?? 'Data belum diisi' }} -
                                        {{ \Carbon\Carbon::parse($biodataCalonSiswa->tanggal_lahir_calon_siswa)->translatedFormat('d F Y') ?? 'Data belum diisi' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Alamat</th>
                                    <td>{{ $biodataCalonSiswa->alamat_rumah_calon_siswa ?? 'Data belum diisi' }}</td>
                                </tr>
                                <tr>
                                    <th>Agama</th>
                                    <td>{{ $biodataCalonSiswa->agama_calon_siswa ?? 'Data belum diisi' }}</td>
                                </tr>
                                <tr>
                                    <th>No. Telepon</th>
                                    <td>{{ $biodataCalonSiswa->nomor_telepon_calon_siswa ?? 'Data belum diisi' }}</td>
                                </tr>
                                <tr>
                                    <th>Periode</th>
                                    <td>{{ $biodataCalonSiswa->periode->nama_periode ?? '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tombol Update -->
                @if (empty($pendaftaran->nomer_pendaftaran) && $biodataBolehDiupdate)
                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('biodata-calon-siswa.edit', $biodataCalonSiswa->id) }}" class="btn btn-primary">
                            Update
                        </a>
                    </div>
                @else
                    <div class="alert alert-danger text-center d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-exclamation-triangle-fill mr-2"></i>
                        <span>
                            Form biodata sudah ditutup.
                            @if (!empty($pendaftaran->nomer_pendaftaran))
                                Anda sudah mendaftar, jadi tidak dapat mengubah data lagi.
                            @else
                                Anda tidak dapat mengubah data lagi.
                            @endif
                        </span>
                    </div>
                @endif
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

        .table th {
            background-color: #f8f9fc;
            color: #495057;
        }

        .table td {
            background-color: #ffffff;
        }

        .table-bordered td,
        .table-bordered th {
            border-color: #dee2e6;
        }
    </style>
@endpush
