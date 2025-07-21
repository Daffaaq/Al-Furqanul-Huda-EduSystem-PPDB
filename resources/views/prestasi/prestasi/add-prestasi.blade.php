@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Tambah Prestasi</h6>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('kategori-prestasi.index') }}"
                            class="{{ request()->routeIs('kategori-prestasi.index') ? 'active' : '' }}">Prestasi</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <a href="#" class="active">Tambah Prestasi</a>
                    </li>
                </ol>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('kategori-prestasi.prestasi.store', $kategori->id) }}">
                    @csrf

                    <div class="form-group">
                        <label>Nama Kategori:</label>
                        <input type="text" class="form-control" value="{{ $kategori->nama_kategori_prestasi }}" disabled>
                    </div>

                    <hr class="my-4">

                    <h6 class="font-weight-bold text-primary mb-3">Prestasi yang Sudah Ada</h6>

                    @forelse ($kategori->prestasi as $prestasi)
                        <div class="prestasi-group">
                            <div class="form-group">
                                <label>Nama Prestasi:</label>
                                <input type="text" class="form-control" value="{{ $prestasi->nama_prestasi }}" disabled>
                            </div>
                            <div class="form-group">
                                <label>Point Prestasi:</label>
                                <input type="number" class="form-control" value="{{ $prestasi->point_prestasi }}" disabled>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-info">Belum ada prestasi pada kategori ini.</div>
                    @endforelse

                    <hr>

                    <div id="prestasi-wrapper">
                        <div class="prestasi-group">
                            <div class="form-group">
                                <label for="nama_prestasi[]">Nama Prestasi:</label>
                                <input type="text" name="nama_prestasi[]"
                                    class="form-control @error('nama_prestasi.0') is-invalid @enderror"
                                    placeholder="Contoh: Juara 1 Nasional" value="{{ old('nama_prestasi.0') }}">
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

            group.querySelector('.remove-prestasi').addEventListener('click', function() {
                group.remove();
            });

            document.querySelectorAll('.remove-prestasi').forEach(btn => btn.style.display = 'inline-block');
        }

        document.querySelectorAll('.remove-prestasi').forEach(button => {
            button.addEventListener('click', function() {
                this.closest('.prestasi-group').remove();
            });
        });
    </script>
@endpush
