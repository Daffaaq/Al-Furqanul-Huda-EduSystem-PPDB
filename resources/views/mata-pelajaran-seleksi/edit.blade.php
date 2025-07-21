@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Mata Pelajaran Seleksi Management</h6>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('mata-pelajaran-seleksi.index') }}"
                            class="{{ request()->routeIs('mata-pelajaran-seleksi.index') ? 'active' : '' }}">Mata Pelajaran
                            Seleksi Management</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('mata-pelajaran-seleksi.edit', $mataPelajaranSeleksi->id) }}"
                            class="{{ request()->routeIs('mata-pelajaran-seleksi.edit') ? 'active' : '' }}">Edit Mata
                            Pelajaran Seleksi</a>
                    </li>
                </ol>
            </div>
            <div class="card-body">
                <!-- Mata Pelajaran Seleksi Edit Form -->
                <form method="POST" action="{{ route('mata-pelajaran-seleksi.update', $mataPelajaranSeleksi->id) }}">
                    @csrf
                    @method('PUT')

                    <!-- Nama Mata Pelajaran Seleksi -->
                    <div class="form-group">
                        <label for="nama_mata_pelajaran_seleksi">Nama Mata Pelajaran Seleksi:</label>
                        <input type="text" name="nama_mata_pelajaran_seleksi" id="nama_mata_pelajaran_seleksi"
                            placeholder="Contoh: Matematika"
                            class="form-control @error('nama_mata_pelajaran_seleksi') is-invalid @enderror"
                            value="{{ old('nama_mata_pelajaran_seleksi', $mataPelajaranSeleksi->nama_mata_pelajaran_seleksi) }}">
                        @error('nama_mata_pelajaran_seleksi')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Periode -->
                    <div class="form-group">
                        <label for="periode_id">Periode:</label>
                        <select name="periode_id" id="periode_id"
                            class="form-control @error('periode_id') is-invalid @enderror">
                            <option value="">-- Pilih Periode --</option>
                            @foreach ($periode as $p)
                                <option value="{{ $p->id }}"
                                    {{ old('periode_id', $mataPelajaranSeleksi->periode_id) == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama_periode }} - {{ $p->status_periode == 1 ? 'Aktif' : 'Tidak Aktif' }}
                                </option>
                            @endforeach
                        </select>
                        @error('periode_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <a class="btn btn-secondary" href="{{ route('mata-pelajaran-seleksi.index') }}">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update</button>
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

        .form-group label {
            font-weight: bold;
        }

        .btn-secondary {
            font-size: 14px;
        }

        .btn-primary {
            font-size: 14px;
        }

        .row {
            margin-top: 20px;
        }

        .col-md-12.text-right {
            text-align: right;
        }
    </style>
@endpush
