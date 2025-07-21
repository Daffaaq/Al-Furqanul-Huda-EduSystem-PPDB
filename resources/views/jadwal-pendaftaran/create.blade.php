@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="d-flex justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Jadwal Pendaftaran Management</h6>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('jadwal-pendaftaran.index') }}"
                                class="{{ request()->routeIs('jadwal-pendaftaran.index') ? 'active' : '' }}">
                                Jadwal Pendaftaran
                            </a>
                        </li>
                        <li class="breadcrumb-item active">Create Jadwal Pendaftaran</li>
                    </ol>
                </div>
            </div>

            <div class="card-body">
                <!-- Jadwal Pendaftaran Create Form -->
                <form method="POST" action="{{ route('jadwal-pendaftaran.store') }}">
                    @csrf

                    <div class="row">
                        <!-- Nama Jadwal Pendaftaran -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_jadwal_pendaftaran">Nama Jadwal Pendaftaran:</label>
                                <input type="text" name="nama_jadwal_pendaftaran" id="nama_jadwal_pendaftaran"
                                    placeholder="Contoh: Pendaftaran 2024/2025"
                                    class="form-control @error('nama_jadwal_pendaftaran') is-invalid @enderror"
                                    value="{{ old('nama_jadwal_pendaftaran') }}">
                                @error('nama_jadwal_pendaftaran')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Periode -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="periode_id">Periode:</label>
                                <select name="periode_id" id="periode_id"
                                    class="form-control @error('periode_id') is-invalid @enderror">
                                    <option value="">-- Pilih Periode --</option>
                                    @foreach ($periode as $p)
                                        <option value="{{ $p->id }}"
                                            {{ old('periode_id') == $p->id ? 'selected' : '' }}>
                                            {{ $p->nama_periode }} ({{ $p->status_periode == 1 ? 'Aktif' : 'Tidak Aktif' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('periode_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Tanggal Mulai Jadwal Pendaftaran -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tanggal_mulai_jadwal_pendaftaran">Tanggal Mulai Jadwal Pendaftaran:</label>
                                <input type="date" name="tanggal_mulai_jadwal_pendaftaran"
                                    id="tanggal_mulai_jadwal_pendaftaran"
                                    class="form-control @error('tanggal_mulai_jadwal_pendaftaran') is-invalid @enderror"
                                    value="{{ old('tanggal_mulai_jadwal_pendaftaran') }}">
                                @error('tanggal_mulai_jadwal_pendaftaran')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Tanggal Selesai Jadwal Pendaftaran -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tanggal_selesai_jadwal_pendaftaran">Tanggal Selesai Jadwal Pendaftaran:</label>
                                <input type="date" name="tanggal_selesai_jadwal_pendaftaran"
                                    id="tanggal_selesai_jadwal_pendaftaran"
                                    class="form-control @error('tanggal_selesai_jadwal_pendaftaran') is-invalid @enderror"
                                    value="{{ old('tanggal_selesai_jadwal_pendaftaran') }}">
                                @error('tanggal_selesai_jadwal_pendaftaran')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Gelombang Pendaftaran -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="gelombang_pendaftaran">Gelombang Pendaftaran:</label>
                                <input type="text" name="gelombang_pendaftaran" id="gelombang_pendaftaran"
                                    placeholder="Contoh: Gelombang 1"
                                    class="form-control @error('gelombang_pendaftaran') is-invalid @enderror"
                                    value="{{ old('gelombang_pendaftaran') }}">
                                @error('gelombang_pendaftaran')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Deadline Biodata Calon Siswa -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="deadline_biodata_calon_siswa">Deadline Biodata Calon Siswa:</label>
                                <input type="date" name="deadline_biodata_calon_siswa" id="deadline_biodata_calon_siswa"
                                    class="form-control @error('deadline_biodata_calon_siswa') is-invalid @enderror"
                                    value="{{ old('deadline_biodata_calon_siswa') }}">
                                @error('deadline_biodata_calon_siswa')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        {{-- tanggal_mulai_verifikasi --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tanggal_mulai_verifikasi">Tanggal Mulai Verifikasi:</label>
                                <input type="date" name="tanggal_mulai_verifikasi" id="tanggal_mulai_verifikasi"
                                    class="form-control @error('tanggal_mulai_verifikasi') is-invalid @enderror"
                                    value="{{ old('tanggal_mulai_verifikasi') }}">
                                @error('tanggal_mulai_verifikasi')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        {{-- tanggal_selesai_verifikasi --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tanggal_selesai_verifikasi">Tanggal Selesai Verifikasi:</label>
                                <input type="date" name="tanggal_selesai_verifikasi" id="tanggal_selesai_verifikasi"
                                    class="form-control @error('tanggal_selesai_verifikasi') is-invalid @enderror"
                                    value="{{ old('tanggal_selesai_verifikasi') }}">
                                @error('tanggal_selesai_verifikasi')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Deadline Upload Pendaftaran -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="deadline_upload_pendaftaran">Deadline Upload Pendaftaran:</label>
                                <input type="date" name="deadline_upload_pendaftaran" id="deadline_upload_pendaftaran"
                                    class="form-control @error('deadline_upload_pendaftaran') is-invalid @enderror"
                                    value="{{ old('deadline_upload_pendaftaran') }}">
                                @error('deadline_upload_pendaftaran')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Pengumuman Hasil Seleksi -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="pengumuman_hasil_seleksi">Pengumuman Hasil Seleksi:</label>
                                <input type="date" name="pengumuman_hasil_seleksi" id="pengumuman_hasil_seleksi"
                                    class="form-control @error('pengumuman_hasil_seleksi') is-invalid @enderror"
                                    value="{{ old('pengumuman_hasil_seleksi') }}">
                                @error('pengumuman_hasil_seleksi')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Kuota Pendaftaran -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kuota_pendaftaran">Kuota Pendaftaran:</label>
                                <input type="number" name="kuota_pendaftaran" id="kuota_pendaftaran"
                                    class="form-control @error('kuota_pendaftaran') is-invalid @enderror"
                                    value="{{ old('kuota_pendaftaran') }}">
                                @error('kuota_pendaftaran')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Kuota Penerimaan -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kuota_penerimaan">Kuota Penerimaan:</label>
                                <input type="number" name="kuota_penerimaan" id="kuota_penerimaan"
                                    class="form-control @error('kuota_penerimaan') is-invalid @enderror"
                                    value="{{ old('kuota_penerimaan') }}">
                                @error('kuota_penerimaan')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        {{-- kuota_akun --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kuota_akun">Kuota Akun:</label>
                                <input type="number" name="kuota_akun" id="kuota_akun"
                                    class="form-control @error('kuota_akun') is-invalid @enderror"
                                    value="{{ old('kuota_akun') }}">
                                @error('kuota_akun')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <!-- Status Jadwal Pendaftaran (Centered) -->
                        <div class="col-md-6 text-center">
                            <div class="form-group">
                                <label for="status_jadwal_pendaftaran">Status Jadwal Pendaftaran:</label>
                                <select name="status_jadwal_pendaftaran" id="status_jadwal_pendaftaran"
                                    class="form-control @error('status_jadwal_pendaftaran') is-invalid @enderror">
                                    <option value="Opened"
                                        {{ old('status_jadwal_pendaftaran') == 'Opened' ? 'selected' : '' }}>Opened
                                    </option>
                                    <option value="Ongoing"
                                        {{ old('status_jadwal_pendaftaran') == 'Ongoing' ? 'selected' : '' }}>Ongoing
                                    </option>
                                    <option value="Closed"
                                        {{ old('status_jadwal_pendaftaran') == 'Closed' ? 'selected' : '' }}>Closed
                                    </option>
                                </select>
                                @error('status_jadwal_pendaftaran')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="row mt-3">
                        <div class="col-md-12 text-right">
                            <a class="btn btn-secondary" href="{{ route('jadwal-pendaftaran.index') }}">Cancel</a>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </form>
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
