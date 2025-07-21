@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Edit Bobot Pendaftaran</h6>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('bobot-pendaftaran.index') }}"
                            class="{{ request()->routeIs('bobot-pendaftaran.index') ? 'active' : '' }}">
                            Bobot Pendaftaran
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('bobot-pendaftaran.edit', $bobotPendaftaran->id) }}"
                            class="{{ request()->routeIs('bobot-pendaftaran.edit') ? 'active' : '' }}">
                            Edit Bobot Pendaftaran
                        </a>
                    </li>
                </ol>
            </div>

            <div class="card-body">
                <!-- Bobot Pendaftaran Edit Form -->
                <form id="bobotForm">
                    @csrf
                    @method('PUT') <!-- Metode PUT untuk update -->

                    <!-- Periode -->
                    <div class="form-group">
                        <label for="periode_id">Periode:</label>
                        <select name="periode_id" id="periode_id"
                            class="form-control @error('periode_id') is-invalid @enderror">
                            <option value="">-- Pilih Periode --</option>
                            @foreach ($periodelist as $periode)
                                <option value="{{ $periode->id }}"
                                    {{ $bobotPendaftaran->periode_id == $periode->id ? 'selected' : '' }}>
                                    {{ $periode->nama_periode }}
                                    ({{ $periode->status_periode == 1 ? 'Aktif' : 'Tidak Aktif' }})
                                </option>
                            @endforeach
                        </select>
                        @error('periode_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Bobot Akademik -->
                    <div class="form-group">
                        <label for="bobot_akademik">Bobot Akademik:</label>
                        <input type="number" step="0.1" min="0" max="100" name="bobot_akademik"
                            id="bobot_akademik" placeholder="Contoh: 60 atau 0.6"
                            class="form-control @error('bobot_akademik') is-invalid @enderror"
                            value="{{ old('bobot_akademik', $bobotPendaftaran->bobot_akademik) }}">
                        @error('bobot_akademik')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Bobot Non Akademik -->
                    <div class="form-group">
                        <label for="bobot_non_akademik">Bobot Non Akademik:</label>
                        <input type="number" step="0.1" min="0" max="100" name="bobot_non_akademik"
                            id="bobot_non_akademik" placeholder="Contoh: 40 atau 0.4"
                            class="form-control @error('bobot_non_akademik') is-invalid @enderror"
                            value="{{ old('bobot_non_akademik', $bobotPendaftaran->bobot_non_akademik) }}">
                        @error('bobot_non_akademik')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="row mt-3">
                        <div class="col-md-12 text-right">
                            <a class="btn btn-secondary" href="{{ route('bobot-pendaftaran.index') }}">Batal</a>
                            <button type="button" class="btn btn-primary" id="submitButton">Simpan</button>
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

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        document.getElementById('submitButton').onclick = function(event) {
            event.preventDefault(); // Prevent the form from submitting initially

            const bobotAkademikInput = document.getElementById('bobot_akademik').value;
            const bobotNonAkademikInput = document.getElementById('bobot_non_akademik').value;
            const periodeIdInput = document.getElementById('periode_id').value;

            // pengecekan apakah kosong
            if (bobotAkademikInput === '' && bobotNonAkademikInput === '' && periodeIdInput === '') {
                Swal.fire('Peringatan!', 'Semua field harus diisi.', 'warning');
                return;
            }

            let bobotAkademik = parseFloat(bobotAkademikInput);
            let bobotNonAkademik = parseFloat(bobotNonAkademikInput);

            // Cek apakah input sudah dalam bentuk persentase atau desimal
            if (bobotAkademik >= 1) {
                bobotAkademik = bobotAkademik / 100; // Jika lebih dari 1, anggap sebagai persentase
            }
            if (bobotNonAkademik >= 1) {
                bobotNonAkademik = bobotNonAkademik / 100; // Jika lebih dari 1, anggap sebagai persentase
            }

            // Calculate the total
            const totalBobot = bobotAkademik + bobotNonAkademik;
            console.log(bobotAkademik);
            console.log(bobotNonAkademik);
            console.log(totalBobot);

            // Check if the total does not equal 1 (100%)
            if (totalBobot !== 1) {
                Swal.fire({
                    title: 'Jumlah bobot tidak valid',
                    text: `Jumlah bobot akademik dan non akademik harus 1. Sistem akan menyesuaikan bobot non-akademik.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Simpan',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Adjust bobot non-akademik to make the sum 1
                        bobotNonAkademik = (1 - bobotAkademik).toFixed(2);
                        // Send the data using AJAX
                        sendFormData(bobotAkademik, bobotNonAkademik);
                    }
                });
            } else {
                // If the sum is correct, send the data
                sendFormData(bobotAkademik, bobotNonAkademik);
            }
        };

        // Send form data using AJAX
        function sendFormData(bobotAkademik, bobotNonAkademik) {
            const formData = {
                periode_id: document.getElementById('periode_id').value,
                bobot_akademik: bobotAkademik,
                bobot_non_akademik: bobotNonAkademik,
            };

            fetch('{{ route('bobot-pendaftaran.update', $bobotPendaftaran->id) }}', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // Pastikan CSRF token dikirimkan di header
                    },
                    body: JSON.stringify(formData),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Berhasil!', data.message, 'success').then(() => {
                            // Redirect to the index page
                            window.location.href = '{{ route('bobot-pendaftaran.index') }}';
                        });
                    } else {
                        Swal.fire('Gagal!', data.message || 'Terjadi kesalahan.', 'error');
                    }
                })
                .catch(error => {
                    Swal.fire('Gagal!', 'Tidak dapat terhubung ke server.', 'error');
                });
        }
    </script>
@endpush
