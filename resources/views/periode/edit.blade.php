@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Periode Management</h6>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('periode.index') }}"
                            class="{{ request()->routeIs('periode.index') ? 'active' : '' }}">Periode Management</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('periode.edit', $periode->id) }}"
                            class="{{ request()->routeIs('periode.edit') ? 'active' : '' }}">Edit Periode</a>
                    </li>
                </ol>
            </div>
            <div class="card-body">
                <!-- Periode Edit Form -->
                <form method="POST" action="{{ route('periode.update', $periode->id) }}">
                    @csrf
                    @method('PUT')

                    <!-- Nama Periode -->
                    <div class="form-group">
                        <label for="nama_periode">Nama Periode:</label>
                        <input type="text" name="nama_periode" id="nama_periode" placeholder="Contoh: Periode 2024/2025"
                            class="form-control @error('nama_periode') is-invalid @enderror"
                            value="{{ old('nama_periode', $periode->nama_periode) }}">
                        @error('nama_periode')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Status Periode -->
                    <div class="form-group">
                        <label for="status_periode">Status Periode:</label>
                        <select name="status_periode" id="status_periode"
                            class="form-control @error('status_periode') is-invalid @enderror">
                            <option value="">-- Pilih Status --</option>
                            <option value="1" {{ old('status_periode', $periode->status_periode) == '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ old('status_periode', $periode->status_periode) == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                        @error('status_periode')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <a class="btn btn-secondary" href="{{ route('periode.index') }}">Cancel</a>
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
