<!-- Modal Revisi Nilai Akademik -->
<div class="modal fade" id="modalRevisiNilaiAkademik" tabindex="-1" aria-labelledby="modalRevisiLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formRevisiNilaiAkademik" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalRevisiLabel">Revisi Nilai Akademik</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="revisiNilaiId" name="id">
                    <div class="mb-3">
                        <label for="nilai" class="form-label">Nilai</label>
                        <input type="number" step="0.01" class="form-control" name="nilai" id="nilai"
                            required>
                    </div>

                    {{-- Tampilkan Riwayat Status --}}
                    <div id="statusHistoriesContainer">
                        @if (isset($nilaiAkademikPendaftar) && $nilaiAkademikPendaftar->statusHistories->count())
                            <hr>
                            <h6>Riwayat Status</h6>
                            <ul class="list-group">
                                @foreach ($nilaiAkademikPendaftar->statusHistories->sortByDesc('created_at') as $history)
                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <div>
                                            <strong>Status:</strong> {{ $history->status }}<br>
                                            <strong>Catatan:</strong> {{ $history->catatan ?? '-' }}<br>
                                            <strong>Petugas:</strong> {{ $history->nama_petugas ?? '-' }}
                                        </div>
                                        <small
                                            class="text-muted">{{ $history->created_at->format('d M Y H:i') }}</small>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted">Belum ada riwayat status.</p>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
