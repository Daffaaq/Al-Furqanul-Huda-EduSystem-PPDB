@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Periode Management</h6>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('kategori-prestasi.index') }}"
                            class="{{ request()->routeIs('kategori-prestasi.index') ? 'active' : '' }}">kategori Management</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('kategori-prestasi.prestasi.edit', $kategori->id) }}"
                            class="{{ request()->routeIs('kategori-prestasi.prestasi.edit') ? 'active' : '' }}">Edit kategori</a>
                    </li>
                </ol>
            </div>
            <div class="card-body">
                <!-- Periode Edit Form -->
                <form method="POST" action="{{ route('kategori-prestasi.prestasi.update', $kategori->id) }}">
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

                    <!-- Submit Button -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <a class="btn btn-secondary" href="{{ route('kategori-prestasi.index') }}">Cancel</a>
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
    </style>
@endpush
