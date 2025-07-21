@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Quotes Management</h6>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('quote.index') }}" class="{{ request()->routeIs('quote.index') ? 'active' : '' }}">
                            Quotes Management
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('quote.create') }}"
                            class="{{ request()->routeIs('quote.create') ? 'active' : '' }}">
                            Create Quote
                        </a>
                    </li>
                </ol>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('quote.store') }}">
                    @csrf

                    <div class="form-group mb-3">
                        <label for="quotes" class="fw-bold">Quote</label>
                        <textarea name="quotes" id="quotes" rows="3" class="form-control @error('quotes') is-invalid @enderror"
                            placeholder="Masukkan kutipan...">{{ old('quotes') }}</textarea>
                        @error('quotes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="author" class="fw-bold">Author</label>
                        <input type="text" name="author" id="author"
                            class="form-control @error('author') is-invalid @enderror" value="{{ old('author') }}"
                            placeholder="Nama pengarang">
                        @error('author')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label for="periode_id" class="fw-bold">Periode</label>
                        <select name="periode_id" id="periode_id"
                            class="form-select @error('periode_id') is-invalid @enderror">
                            <option value="">-- Pilih Periode --</option>
                            @foreach ($periodelist as $periode)
                                <option value="{{ $periode->id }}"
                                    {{ old('periode_id') == $periode->id ? 'selected' : '' }}>
                                    {{ $periode->nama_periode }} -
                                    {{ $periode->status_periode == 1 ? 'Aktif' : 'Tidak Aktif' }}
                                </option>
                            @endforeach
                        </select>
                        @error('periode_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('quote.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
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
