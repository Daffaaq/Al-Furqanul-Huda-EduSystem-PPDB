@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-9 mb-4">
                <div class="card shadow-sm border-0" id="kontak-section">
                    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="text-primary m-0">📞 Informasi Kontak</h5>
                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                            data-bs-target="#editKontakModal">
                            <i class="fas fa-edit me-1"></i> Edit
                        </button>
                    </div>
                    <div class="card-body p-4">
                        @if ($kontak)
                            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                                <div class="col">
                                    <div class="border rounded p-3 h-100 bg-light-subtle">
                                        <div class="fw-semibold text-dark mb-1"><i
                                                class="fas fa-map-marker-alt mr-2 text-danger"></i>Alamat</div>
                                        <div class="text-muted small">{{ $kontak->alamat }}</div>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="border rounded p-3 h-100 bg-light-subtle">
                                        <div class="fw-semibold"><i class="fas fa-phone-alt mr-2 text-success"></i>Telepon
                                        </div>
                                        <div class="text-muted small">{{ $kontak->telepon }}</div>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="border rounded p-3 h-100 bg-light-subtle">
                                        <div class="fw-semibold"><i class="fab fa-whatsapp mr-2 text-success"></i>WhatsApp
                                        </div>
                                        <div class="text-muted small">{{ $kontak->whatsapp }}</div>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="border rounded p-3 h-100 bg-light-subtle">
                                        <div class="fw-semibold"><i class="fas fa-envelope mr-2 text-primary"></i>Email
                                        </div>
                                        <div class="text-muted small">{{ $kontak->email }}</div>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="border rounded p-3 h-100 bg-light-subtle">
                                        <div class="fw-semibold"><i class="fab fa-facebook mr-2 text-primary"></i>Facebook
                                        </div>
                                        <div><a href="{{ $kontak->facebook }}" target="_blank"
                                                class="text-muted small text-decoration-none">{{ $kontak->facebook }}</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="border rounded p-3 h-100 bg-light-subtle">
                                        <div class="fw-semibold"><i class="fab fa-instagram mr-2 text-danger"></i>Instagram
                                        </div>
                                        <div><a href="{{ $kontak->instagram }}" target="_blank"
                                                class="text-muted small text-decoration-none">{{ $kontak->instagram }}</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="border rounded p-3 h-100 bg-light-subtle">
                                        <div class="fw-semibold"><i class="fab fa-youtube mr-2 text-danger"></i>YouTube
                                        </div>
                                        <div><a href="{{ $kontak->youtube }}" target="_blank"
                                                class="text-muted small text-decoration-none">{{ $kontak->youtube }}</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="border rounded p-3 h-100 bg-light-subtle">
                                        <div class="fw-semibold"><i class="fab fa-tiktok mr-2 text-dark"></i>TikTok</div>
                                        <div><a href="{{ $kontak->tiktok }}" target="_blank"
                                                class="text-muted small text-decoration-none">{{ $kontak->tiktok }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="my-4">
                            <h6 class="text-primary"><i class="fas fa-map-marked-alt me-2"></i>Lokasi di Peta</h6>
                            <div id="map-wrapper">
                                <div id="map" style="height: 300px; border-radius: .5rem;"></div>
                                <div id="map-coordinates" class="text-muted small text-end mt-2">
                                    📍 Lat: {{ $kontak->latitude }}, Lng: {{ $kontak->longitude }}
                                </div>
                            </div>
                        @else
                            <p class="text-muted">Data kontak belum tersedia.</p>
                        @endif
                    </div>
                </div>


                <div class="card shadow-sm border-0 d-none" id="jam-section">
                    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="text-primary m-0">⏰ Jam Operasional</h5>
                        <a href="#" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                            data-bs-target="#editJamModal">
                            <i class="fas fa-edit me-1"></i> Edit
                        </a>
                    </div>
                    <div class="card-body p-4">
                        @if ($jamOperasionals->count())
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Hari</th>
                                            <th>Jam Buka</th>
                                            <th>Jam Tutup</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($jamOperasionals as $jam)
                                            <tr>
                                                <td>{{ ucfirst($jam->hari) }}</td>
                                                <td>{{ $jam->buka }}</td>
                                                <td>{{ $jam->tutup }}</td>
                                                <td>
                                                    @if ($jam->tutup_full)
                                                        <span class="badge bg-danger text-white">Tutup Penuh</span>
                                                    @else
                                                        <span class="badge bg-success text-white">Buka</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted">Data jam operasional belum tersedia.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar Filter -->
            <div class="col-lg-3">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom">
                        <h6 class="text-primary mb-0">📋 Tampilkan Informasi</h6>
                    </div>
                    <div class="card-body p-3">
                        <button id="btnKontak" class="btn btn-outline-primary w-100 mb-2 active">
                            📞 Informasi Kontak
                        </button>
                        <button id="btnJam" class="btn btn-outline-primary w-100">
                            ⏰ Jam Operasional
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('kontak.informasi-kontak-modal')
    @include('kontak.jam-operasional-modal')
@endsection

@push('styles')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #map-wrapper {
            position: relative;
        }

        #map-coordinates {
            font-size: 13px;
            padding-top: 6px;
            color: #6c757d;
            text-align: right;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: '{{ session('error') }}',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
        @endif
        document.addEventListener("DOMContentLoaded", function() {
            // Toggle
            const kontakSection = document.getElementById('kontak-section');
            const jamSection = document.getElementById('jam-section');
            const btnKontak = document.getElementById('btnKontak');
            const btnJam = document.getElementById('btnJam');

            btnKontak.addEventListener('click', function() {
                kontakSection.classList.remove('d-none');
                jamSection.classList.add('d-none');
                btnKontak.classList.add('active');
                btnJam.classList.remove('active');
            });

            btnJam.addEventListener('click', function() {
                jamSection.classList.remove('d-none');
                kontakSection.classList.add('d-none');
                btnJam.classList.add('active');
                btnKontak.classList.remove('active');
            });

            // Map Leaflet
            const lat = {{ $kontak->latitude ?? 0 }};
            const lng = {{ $kontak->longitude ?? 0 }};

            if (lat && lng) {
                const map = L.map('map').setView([lat, lng], 15);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                L.marker([lat, lng]).addTo(map)
                    .bindPopup("Lokasi Kantor")
                    .openPopup();
            }
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const kontakSection = document.getElementById('kontak-section');
            const jamSection = document.getElementById('jam-section');
            const btnKontak = document.getElementById('btnKontak');
            const btnJam = document.getElementById('btnJam');

            btnKontak.addEventListener('click', function() {
                kontakSection.classList.remove('d-none');
                jamSection.classList.add('d-none');
                btnKontak.classList.add('active');
                btnJam.classList.remove('active');
            });

            btnJam.addEventListener('click', function() {
                jamSection.classList.remove('d-none');
                kontakSection.classList.add('d-none');
                btnJam.classList.add('active');
                btnKontak.classList.remove('active');
            });
        });
    </script>
@endpush
