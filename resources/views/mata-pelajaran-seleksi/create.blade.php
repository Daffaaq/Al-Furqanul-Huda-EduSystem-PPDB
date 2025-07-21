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
                        <a href="{{ route('mata-pelajaran-seleksi.create') }}"
                            class="{{ request()->routeIs('mata-pelajaran-seleksi.create') ? 'active' : '' }}">Create Mata
                            Pelajaran Seleksi</a>
                    </li>
                </ol>
            </div>
            <div class="card-body">
                <!-- Mata Pelajaran Seleksi Create Form -->
                <form method="POST" action="{{ route('mata-pelajaran-seleksi.store') }}">
                    @csrf

                    <div id="mata-pelajaran-fields">
                        <div class="mata-pelajaran-group">
                            <div class="form-group">
                                <label for="nama_mata_pelajaran_seleksi[]">Nama Mata Pelajaran Seleksi:</label>
                                <input type="text" name="nama_mata_pelajaran_seleksi[]" placeholder="Contoh: Matematika"
                                    class="form-control @error('nama_mata_pelajaran_seleksi') is-invalid @enderror"
                                    value="{{ old('nama_mata_pelajaran_seleksi.0') }}">
                                @error('nama_mata_pelajaran_seleksi.*')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="periode_id[]">Periode:</label>
                                <select name="periode_id[]" class="form-control @error('periode_id') is-invalid @enderror">
                                    <option value="">-- Pilih Periode --</option>
                                    @foreach ($periode as $p)
                                        <option value="{{ $p->id }}"
                                            {{ old('periode_id.0') == $p->id ? 'selected' : '' }}>
                                            {{ $p->nama_periode }} -
                                            {{ $p->status_periode == 1 ? 'Aktif' : 'Tidak Aktif' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('periode_id.*')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Remove Button -->
                            <button type="button" class="btn btn-danger remove-field" style="display:none;">Remove</button>
                        </div>
                    </div>

                    <button type="button" onclick="addMataPelajaranField()" class="btn btn-secondary mt-3">Add Another Mata
                        Pelajaran</button>

                    <!-- Submit Button -->
                    <div class="row mt-4">
                        <div class="col-md-12 text-right">
                            <a class="btn btn-secondary" href="{{ route('mata-pelajaran-seleksi.index') }}">Cancel</a>
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

        .mata-pelajaran-group {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .remove-field {
            margin-top: 10px;
            margin-left: 5px;
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

@push('scripts')
    <script>
        function addMataPelajaranField() {
            const container = document.getElementById('mata-pelajaran-fields');
            const newField = document.createElement('div');
            newField.classList.add('mata-pelajaran-group');
            newField.innerHTML = `
                <div class="form-group">
                    <label for="nama_mata_pelajaran_seleksi[]">Nama Mata Pelajaran Seleksi:</label>
                    <input type="text" name="nama_mata_pelajaran_seleksi[]" placeholder="Contoh: Matematika" class="form-control">
                </div>

                <div class="form-group">
                    <label for="periode_id[]">Periode:</label>
                    <select name="periode_id[]" class="form-control">
                        <option value="">-- Pilih Periode --</option>
                        @foreach ($periode as $p)
                            <option value="{{ $p->id }}">
                                {{ $p->nama_periode }} - {{ $p->status_periode == 1 ? 'Aktif' : 'Tidak Aktif' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Remove Button -->
                <button type="button" class="btn btn-danger remove-field">Remove</button>
            `;
            container.appendChild(newField);

            // Add event listener for the remove button
            newField.querySelector('.remove-field').addEventListener('click', function() {
                newField.remove();
            });
        }

        // Event listener for existing remove buttons
        document.querySelectorAll('.remove-field').forEach(button => {
            button.addEventListener('click', function() {
                button.closest('.mata-pelajaran-group').remove();
            });
        });
    </script>
@endpush
