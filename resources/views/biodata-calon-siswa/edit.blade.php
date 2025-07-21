@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <!-- Page Heading -->
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Pendaftaran Calon Siswa</h6>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('biodata-calon-siswa.index') }}"
                            class="{{ request()->routeIs('biodata-calon-siswa.index') ? 'active' : '' }}">
                            Biodata Calon Siswa
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('biodata-calon-siswa.edit', $biodataCalonSiswa->id) }}"
                            class="{{ request()->routeIs('biodata-calon-siswa.edit') ? 'active' : '' }}">
                            Edit Biodata
                        </a>
                    </li>
                </ol>
            </div>

            <div class="card-body">
                <!-- Form Edit Pendaftaran -->
                <form action="{{ route('biodata-calon-siswa.update', $biodataCalonSiswa->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT') <!-- Menggunakan method PUT untuk update data -->

                    <!-- Nama -->
                    <div class="form-group">
                        <label for="nama_calon_siswa">Nama Lengkap:</label>
                        <input type="text" name="nama_calon_siswa" id="nama_calon_siswa"
                            class="form-control @error('nama_calon_siswa') is-invalid @enderror"
                            value="{{ old('nama_calon_siswa', $biodataCalonSiswa->nama_calon_siswa) }}" required>
                        @error('nama_calon_siswa')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" name="email" id="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $biodataCalonSiswa->email_calon_siswa) }}" required>
                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- agama_calon_siswa --}}
                    {{-- in:Islam,Kristen,Katolik,Hindu,Budha,Konghucu' --}}
                    <div class="form-group">
                        <label for="agama_calon_siswa">Agama:</label>
                        <select name="agama_calon_siswa" id="agama_calon_siswa"
                            class="form-control @error('agama_calon_siswa') is-invalid @enderror" required>
                            <option value="">-- Pilih Agama --</option>
                            <option value="Islam"
                                {{ old('agama_calon_siswa', $biodataCalonSiswa->agama_calon_siswa) == 'Islam' ? 'selected' : '' }}>
                                Islam</option>
                            <option value="Kristen"
                                {{ old('agama_calon_siswa', $biodataCalonSiswa->agama_calon_siswa) == 'Kristen' ? 'selected' : '' }}>
                                Kristen</option>
                            <option value="Katolik"
                                {{ old('agama_calon_siswa', $biodataCalonSiswa->agama_calon_siswa) == 'Katolik' ? 'selected' : '' }}>
                                Katolik</option>
                            <option value="Hindu"
                                {{ old('agama_calon_siswa', $biodataCalonSiswa->agama_calon_siswa) == 'Hindu' ? 'selected' : '' }}>
                                Hindu</option>
                            <option value="Budha"
                                {{ old('agama_calon_siswa', $biodataCalonSiswa->agama_calon_siswa) == 'Budha' ? 'selected' : '' }}>
                                Budha</option>
                            <option value="Konghucu"
                                {{ old('agama_calon_siswa', $biodataCalonSiswa->agama_calon_siswa) == 'Konghucu' ? 'selected' : '' }}>
                                Konghucu</option>
                        </select>
                        @error('agama_calon_siswa')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>


                    <!-- Foto Formal -->
                    <div class="form-group">
                        <label for="foto_formal_calon_siswa">Foto Formal:</label>
                        <input type="file" name="foto_formal_calon_siswa" id="foto_formal_calon_siswa"
                            class="form-control @error('foto_formal_calon_siswa') is-invalid @enderror" accept="image/*">
                        @error('foto_formal_calon_siswa')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        @if ($biodataCalonSiswa->foto_formal_calon_siswa)
                            <div class="mt-2">
                                <label>Foto Saat Ini:</label><br>
                                <img src="{{ asset('storage/' . $biodataCalonSiswa->foto_formal_calon_siswa) }}"
                                    alt="Foto Formal" class="img-thumbnail" width="150">
                            </div>
                        @endif
                    </div>

                    <!-- Jenis Kelamin -->
                    <div class="form-group">
                        <label for="jenis_kelamin_calon_siswa">Jenis Kelamin:</label>
                        <select name="jenis_kelamin_calon_siswa" id="jenis_kelamin_calon_siswa"
                            class="form-control @error('jenis_kelamin_calon_siswa') is-invalid @enderror" required>
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="Laki-laki"
                                {{ old('jenis_kelamin_calon_siswa', $biodataCalonSiswa->jenis_kelamin_calon_siswa) == 'Laki-laki' ? 'selected' : '' }}>
                                Laki-laki</option>
                            <option value="Perempuan"
                                {{ old('jenis_kelamin_calon_siswa', $biodataCalonSiswa->jenis_kelamin_calon_siswa) == 'Perempuan' ? 'selected' : '' }}>
                                Perempuan</option>
                        </select>
                        @error('jenis_kelamin_calon_siswa')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Alamat -->
                    <div class="form-group">
                        <label for="alamat_rumah_calon_siswa">Alamat:</label>
                        <textarea name="alamat_rumah_calon_siswa" id="alamat_rumah_calon_siswa"
                            class="form-control @error('alamat_rumah_calon_siswa') is-invalid @enderror" required>{{ old('alamat_rumah_calon_siswa', $biodataCalonSiswa->alamat_rumah_calon_siswa) }}</textarea>
                        @error('alamat_rumah_calon_siswa')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Tanggal Lahir -->
                    <div class="form-group">
                        <label for="tanggal_lahir_calon_siswa">Tanggal Lahir:</label>
                        <input type="date" name="tanggal_lahir_calon_siswa" id="tanggal_lahir_calon_siswa"
                            class="form-control @error('tanggal_lahir_calon_siswa') is-invalid @enderror" required
                            value="{{ old('tanggal_lahir_calon_siswa', $biodataCalonSiswa->tanggal_lahir_calon_siswa) }}">
                        @error('tanggal_lahir_calon_siswa')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Tempat Lahir -->
                    <div class="form-group">
                        <label for="tempat_tanggal_lahir_calon_siswa">Tempat Lahir:</label>
                        <input type="text" name="tempat_tanggal_lahir_calon_siswa" id="tempat_tanggal_lahir_calon_siswa"
                            class="form-control @error('tempat_tanggal_lahir_calon_siswa') is-invalid @enderror"
                            value="{{ old('tempat_tanggal_lahir_calon_siswa', $biodataCalonSiswa->tempat_tanggal_lahir_calon_siswa) }}"
                            required>
                        @error('tempat_tanggal_lahir_calon_siswa')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Nomor Telepon -->
                    <div class="form-group">
                        <label for="nomor_telepon_calon_siswa">Nomor Telepon:</label>
                        <input type="text" name="nomor_telepon_calon_siswa" id="nomor_telepon_calon_siswa"
                            class="form-control @error('nomor_telepon_calon_siswa') is-invalid @enderror"
                            value="{{ old('nomor_telepon_calon_siswa', $biodataCalonSiswa->nomor_telepon_calon_siswa) }}"
                            required>
                        @error('nomor_telepon_calon_siswa')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <a class="btn btn-secondary" href="{{ route('biodata-calon-siswa.index') }}">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
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
