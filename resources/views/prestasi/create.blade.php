@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Manajemen Prestasi</h6>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('kategori-prestasi.index') }}"
                            class="{{ request()->routeIs('kategori-prestasi.index') ? 'active' : '' }}">Prestasi</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('kategori-prestasi.create') }}"
                            class="{{ request()->routeIs('kategori-prestasi.create') ? 'active' : '' }}">Tambah Prestasi</a>
                    </li>
                </ol>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('kategori-prestasi.store') }}">
                    @csrf

                    <div class="form-group">
                        <label for="nama_kategori_prestasi">Nama Kategori Prestasi:</label>
                        <input type="text" name="nama_kategori_prestasi"
                            class="form-control @error('nama_kategori_prestasi') is-invalid @enderror"
                            value="{{ old('nama_kategori_prestasi') }}" placeholder="Contoh: Nasional, Internasional">
                        @error('nama_kategori_prestasi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="periode_id">Periode:</label>
                        <select name="periode_id" class="form-control @error('periode_id') is-invalid @enderror">
                            <option value="">-- Pilih Periode --</option>
                            @foreach ($periode as $p)
                                <option value="{{ $p->id }}" {{ old('periode_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama_periode }} - {{ $p->status_periode == 1 ? 'Aktif' : 'Tidak Aktif' }}
                                </option>
                            @endforeach
                        </select>
                        @error('periode_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>

                    <div id="prestasi-wrapper">
                        <div class="prestasi-group">
                            <div class="form-group">
                                <label for="nama_prestasi[]">Nama Prestasi:</label>
                                <input type="text" name="nama_prestasi[]"
                                    class="form-control @error('nama_prestasi.0') is-invalid @enderror"
                                    placeholder="Contoh: Juara 1, Finalis Olimpiade" value="{{ old('nama_prestasi.0') }}">
                                @error('nama_prestasi.0')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="point_prestasi[]">Point Prestasi:</label>
                                <input type="number" name="point_prestasi[]"
                                    class="form-control @error('point_prestasi.0') is-invalid @enderror"
                                    placeholder="Contoh: 100" value="{{ old('point_prestasi.0') }}">
                                @error('point_prestasi.0')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="button" class="btn btn-danger remove-prestasi"
                                style="display: none;">Hapus</button>
                        </div>
                    </div>

                    <button type="button" class="btn btn-secondary mt-3" onclick="addPrestasiField()">Tambah
                        Prestasi</button>

                    <div class="row mt-4">
                        <div class="col-md-12 text-right">
                            <a href="{{ route('kategori-prestasi.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary ml-2">Simpan</button>
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

        .remove-prestasi {
            margin-top: 10px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        function addPrestasiField() {
            const container = document.getElementById('prestasi-wrapper');
            const group = document.createElement('div');
            group.classList.add('prestasi-group');
            group.innerHTML = `
                <div class="form-group">
                    <label for="nama_prestasi[]">Nama Prestasi:</label>
                    <input type="text" name="nama_prestasi[]" class="form-control" placeholder="Contoh: Juara 1">
                </div>

                <div class="form-group">
                    <label for="point_prestasi[]">Point Prestasi:</label>
                    <input type="number" name="point_prestasi[]" class="form-control" placeholder="Contoh: 100">
                </div>

                <button type="button" class="btn btn-danger remove-prestasi">Hapus</button>
            `;

            container.appendChild(group);

            // Add event listener to remove button
            group.querySelector('.remove-prestasi').addEventListener('click', function() {
                group.remove();
            });

            // Show remove buttons
            document.querySelectorAll('.remove-prestasi').forEach(btn => btn.style.display = 'inline-block');
        }

        // Remove handler for existing button if there's more than one group
        document.querySelectorAll('.remove-prestasi').forEach(button => {
            button.addEventListener('click', function() {
                this.closest('.prestasi-group').remove();
            });
        });
    </script>
@endpush
