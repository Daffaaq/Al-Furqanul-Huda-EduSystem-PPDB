@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <form method="POST" action="{{ route('pendaftaran.raport.store', $pendaftaran->id) }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="pendaftaran_id" value="{{ $pendaftaran->id }}">

            {{-- Judul --}}
            <div class="mb-4">
                <h4 class="font-weight-bold text-primary">📝 Input Seleksi Nilai Raport & Prestasi</h4>
                <p class="mb-0 text-muted">Silakan isi nilai akademik dan prestasi calon siswa dengan lengkap.</p>
            </div>

            {{-- Siswa --}}
            <div class="card mb-4">
                <div class="card-body">
                    <h6 class="font-weight-bold text-secondary mb-3">👤 Data Calon Siswa</h6>
                    <p class="mb-1"><strong>Nama:</strong> {{ $pendaftaran->biodataCalonSiswa->nama_calon_siswa ?? '-' }}
                    </p>
                </div>
            </div>

            {{-- Akademik --}}
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <strong>📚 Nilai Akademik</strong>
                </div>
                <div class="card-body">
                    @foreach ($mataPelajaranSeleksi as $mata)
                        <div class="form-group">
                            <label>{{ $mata->nama_mata_pelajaran_seleksi }}</label>
                            <input type="number" step="0.01" min="0" max="100"
                                name="nilai_akademik[{{ $mata->id }}]" class="form-control"
                                value="{{ old('nilai_akademik.' . $mata->id, $nilaiAkademik[$mata->id] ?? '') }}"
                                placeholder="Masukkan nilai">
                        </div>
                    @endforeach
                    <div class="form-group mt-4">
                        <label for="dokumen_nilai_pendukung">📎 Upload Dokumen Nilai (SKL atau Raport):</label>
                        <input type="file" name="dokumen_nilai_pendukung" class="form-control-file">
                        @error('dokumen_nilai_pendukung')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    @if ($pendaftaran->biodataCalonSiswa->dokumen_pendukung ?? false)
                        <div class="mt-2">
                            <a href="{{ asset('storage/' . $pendaftaran->biodataCalonSiswa->dokumen_pendukung) }}"
                                target="_blank">📄 Lihat Dokumen Nilai Sebelumnya</a>
                        </div>
                    @endif

                </div>
            </div>

            {{-- Prestasi --}}
            {{-- Alert Informasi Prestasi --}}
            <div class="alert alert-warning d-flex align-items-center" role="alert">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <div>
                    <strong>Perhatian:</strong> Setiap prestasi yang Anda masukkan <u>tidak dapat diperbarui</u> setelah
                    <strong>ditolak</strong> oleh panitia seleksi. Hal ini bertujuan untuk meminimalkan risiko manipulasi
                    data
                    dan menjaga keabsahan dokumen yang telah diverifikasi.
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <strong>🏅 Prestasi Non-Akademik</strong>
                </div>
                <div class="card-body">
                    @forelse($prestasi->groupBy('kategori_prestasi.nama_kategori_prestasi') as $kategori => $items)
                        <h6 class="text-muted mt-3">{{ $kategori }}</h6>
                        @foreach ($items as $p)
                            <div class="form-row align-items-center mb-3">
                                <div class="col-md-5">
                                    <label class="mb-1">{{ $p->nama_prestasi }} ({{ $p->point_prestasi }} poin)</label>
                                    <br>
                                    <small class="text-muted">Status:
                                        {{ $prestasiPendaftar[$p->id]->status ?? 'Belum ada' }}</small>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" name="jumlah_prestasi[{{ $p->id }}]"
                                        value="{{ old('jumlah_prestasi.' . $p->id, $prestasiPendaftar[$p->id]->jumlah_prestasi ?? '') }}"
                                        class="form-control" placeholder="Jumlah" min="0"
                                        data-status="{{ $prestasiPendaftar[$p->id]->status ?? 'Belum ada' }}">

                                </div>
                                <div class="col-md-4">
                                    <input type="file" name="dokumen_prestasi_pendukung[{{ $p->id }}]"
                                        class="form-control-file"
                                        data-status="{{ $prestasiPendaftar[$p->id]->status ?? 'Belum ada' }}">
                                    @if (!empty($prestasiPendaftar[$p->id]) && $prestasiPendaftar[$p->id]->dokumen_prestasi_pendukung)
                                        <div class="mt-1">
                                            <a href="{{ asset('storage/' . $prestasiPendaftar[$p->id]->dokumen_prestasi_pendukung) }}"
                                                target="_blank">
                                                📎 Lihat File Sebelumnya
                                            </a>
                                        </div>
                                    @endif
                                </div>

                            </div>
                        @endforeach
                    @empty
                        <p class="text-muted">Tidak ada data prestasi tersedia.</p>
                    @endforelse
                </div>
            </div>

            {{-- Submit --}}
            <div class="text-right">
                <a href="{{ route('pendaftaran.index') }}" class="btn btn-secondary px-4 mr-2">❌ Batal</a>
                <button type="submit" class="btn btn-primary px-4">💾 Simpan Semua</button>
            </div>
        </form>
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const jumlahInputs = document.querySelectorAll('input[name^="jumlah_prestasi"]');

            function cekTotalJumlah() {
                let total = 0;
                let statusMap = {};

                jumlahInputs.forEach(input => {
                    const val = parseInt(input.value) || 0;
                    const status = input.dataset.status || '';
                    const id = input.name.match(/\d+/)?.[0];
                    if (!id) return;

                    statusMap[id] = {
                        val,
                        status
                    };
                    total += val;
                });

                jumlahInputs.forEach(input => {
                    const id = input.name.match(/\d+/)?.[0];
                    if (!id) return;

                    const {
                        status,
                        val
                    } = statusMap[id];
                    const fileInput = document.querySelector(
                        `input[name="dokumen_prestasi_pendukung[${id}]"]`);

                    if (status === 'Accept' || status === 'Reject') {
                        input.disabled = true;
                        input.readOnly = true;
                        if (fileInput) fileInput.disabled = true;
                    } else if (status === 'Pending') {
                        if (total >= 3 && val === 0) {
                            // Disable input yang kosong jika total sudah 3
                            input.disabled = true;
                            input.readOnly = true;
                            if (fileInput) fileInput.disabled = true;
                            input.removeAttribute('max');
                        } else {
                            // Enable input yang sudah ada nilai dan batasi max supaya total tidak lebih dari 3
                            input.disabled = false;
                            input.readOnly = false;
                            if (fileInput) fileInput.disabled = false;

                            const maxAllowed = 3 - (total - val);
                            input.max = maxAllowed >= 0 ? maxAllowed : 0;

                            // Jika input value melebihi maxAllowed, set ulang ke maxAllowed
                            if (val > maxAllowed) {
                                input.value = maxAllowed;
                            }
                        }
                    } else {
                        // Jika status kosong atau undefined, treat seperti Pending:
                        if (total >= 3 && val === 0) {
                            input.disabled = true;
                            input.readOnly = true;
                            if (fileInput) fileInput.disabled = true;
                            input.removeAttribute('max');
                        } else {
                            input.disabled = false;
                            input.readOnly = false;
                            if (fileInput) fileInput.disabled = false;

                            const maxAllowed = 3 - (total - val);
                            input.max = maxAllowed >= 0 ? maxAllowed : 0;

                            if (val > maxAllowed) {
                                input.value = maxAllowed;
                            }
                        }
                    }
                });
            }


            cekTotalJumlah(); // Jalankan saat page load

            // Pasang listener input
            jumlahInputs.forEach(input => {
                input.addEventListener('input', cekTotalJumlah);
            });
        });
    </script>
@endpush
