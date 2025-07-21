@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Edit Prestasi</h6>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('kategori-prestasi.index') }}"
                            class="{{ request()->routeIs('kategori-prestasi.index') ? 'active' : '' }}">Prestasi</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <a href="{{ route('kategori-prestasi.edit', $kategori->id) }}"
                            class="{{ request()->routeIs('kategori-prestasi.edit', $kategori->id) ? 'active' : '' }}">Edit
                            Prestasi</a>
                    </li>
                </ol>
            </div>
            <div class="card-body">
                <div>
                    <button class="btn btn-info mb-3" id="toggleAlertBtn" onclick="toggleAlert()">
                        <strong>Perhatian:</strong> Klik untuk tampilkan pengumuman
                    </button>

                    <div class="alert alert-info" role="alert" id="alertUpdateInfo" style="display:none;">
                        Anda hanya dapat memperbarui data prestasi yang sudah ada.
                    </div>
                </div>


                <form method="POST" action="{{ route('kategori-prestasi.update', $kategori->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="nama_kategori_prestasi">Nama Kategori Prestasi:</label>
                        <input type="text" name="nama_kategori_prestasi"
                            class="form-control @error('nama_kategori_prestasi') is-invalid @enderror"
                            value="{{ old('nama_kategori_prestasi', $kategori->nama_kategori_prestasi) }}">
                        @error('nama_kategori_prestasi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="periode_id">Periode:</label>
                        <select name="periode_id" class="form-control @error('periode_id') is-invalid @enderror">
                            <option value="">-- Pilih Periode --</option>
                            @foreach ($periodes as $p)
                                <option value="{{ $p->id }}" {{ $kategori->periode_id == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama_periode }} - {{ $p->status_periode ? 'Aktif' : 'Tidak Aktif' }}
                                </option>
                            @endforeach
                        </select>
                        @error('periode_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>

                    <div id="prestasi-wrapper">
                        @foreach ($kategori->prestasi as $i => $prestasi)
                            <div class="prestasi-group">
                                <input type="hidden" name="prestasi_id[]" value="{{ $prestasi->id }}">

                                <div class="form-group">
                                    <label for="nama_prestasi[]">Nama Prestasi:</label>
                                    <input type="text" name="nama_prestasi[]"
                                        value="{{ old('nama_prestasi.' . $i, $prestasi->nama_prestasi) }}"
                                        class="form-control @error("nama_prestasi.$i") is-invalid @enderror">
                                    @error("nama_prestasi.$i")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="point_prestasi[]">Point Prestasi:</label>
                                    <input type="number" name="point_prestasi[]"
                                        value="{{ old('point_prestasi.' . $i, $prestasi->point_prestasi) }}"
                                        class="form-control @error("point_prestasi.$i") is-invalid @enderror">
                                    @error("point_prestasi.$i")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12 text-right">
                            <a href="{{ route('kategori-prestasi.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary ml-2">Simpan Perubahan</button>
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

        .prestasi-group {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        #toggleAlertBtn {
            padding: 0.25rem 0.5rem;
            font-size: 0.85rem;
            line-height: 1.2;
        }
    </style>
@endpush
@push('scripts')
    <script>
        let alertVisible = false;

        function toggleAlert() {
            alertVisible = !alertVisible;
            const alertBox = document.getElementById('alertUpdateInfo');
            const toggleBtn = document.getElementById('toggleAlertBtn');

            if (alertVisible) {
                alertBox.style.display = 'block';
                toggleBtn.innerHTML = '<strong>Perhatian:</strong> Klik untuk sembunyikan pengumuman';
            } else {
                alertBox.style.display = 'none';
                toggleBtn.innerHTML = '<strong>Perhatian:</strong> Klik untuk tampilkan pengumuman';
            }
        }
    </script>
@endpush
