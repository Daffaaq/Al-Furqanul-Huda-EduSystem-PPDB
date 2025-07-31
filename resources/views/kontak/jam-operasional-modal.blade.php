<div class="modal fade" id="editJamModal" tabindex="-1" aria-labelledby="editJamLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('contact.updateJamOperasional') }}" method="POST">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="editJamLabel"><i class="fas fa-edit me-1"></i> Edit Jam Operasional</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-body">
                    @foreach ($jamOperasionals as $index => $jam)
                        <input type="hidden" name="jams[{{ $index }}][id]" value="{{ $jam->id }}">

                        <div class="row align-items-center mb-3">
                            <div class="col-md-2">
                                <label class="form-label fw-bold">{{ ucfirst($jam->hari) }}</label>
                            </div>
                            <div class="col-md-3">
                                <input type="time" name="jams[{{ $index }}][buka]" class="form-control"
                                    value="{{ $jam->buka ? \Carbon\Carbon::parse($jam->buka)->format('H:i') : '' }}">
                            </div>
                            <div class="col-md-3">
                                <input type="time" name="jams[{{ $index }}][tutup]" class="form-control"
                                    value="{{ $jam->tutup ? \Carbon\Carbon::parse($jam->tutup)->format('H:i') : '' }}">
                            </div>
                            <div class="col-md-2 text-center">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox"
                                        name="jams[{{ $index }}][tutup_full]" id="tutup_full_{{ $index }}"
                                        value="1" {{ $jam->tutup_full ? 'checked' : '' }}>


                                    <label class="form-check-label" for="tutup_full_{{ $index }}">Tutup
                                        Penuh</label>
                                </div>
                            </div>
                        </div>
                        <hr>
                    @endforeach
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Cari semua checkbox tutup penuh
        const checkboxes = document.querySelectorAll('input[type="checkbox"][name*="[tutup_full]"]');

        checkboxes.forEach((checkbox) => {
            toggleTimeInputs(checkbox); // inisialisasi saat load

            checkbox.addEventListener('change', function() {
                toggleTimeInputs(this);
            });
        });

        function toggleTimeInputs(checkbox) {
            // Cari container row paling dekat
            const row = checkbox.closest('.row');
            if (!row) return;

            // Cari input buka dan tutup dalam row itu
            const bukaInput = row.querySelector('input[name$="[buka]"]');
            const tutupInput = row.querySelector('input[name$="[tutup]"]');

            if (checkbox.checked) {
                // Kosongkan dan jadikan readonly
                bukaInput.value = '';
                bukaInput.readOnly = true;
                tutupInput.value = '';
                tutupInput.readOnly = true;
            } else {
                // Hapus readonly supaya bisa diedit
                bukaInput.readOnly = false;
                tutupInput.readOnly = false;
            }
        }
    });
</script>
