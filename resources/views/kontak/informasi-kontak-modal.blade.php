<!-- resources/views/contact/modal.blade.php -->
<div class="modal fade" id="editKontakModal" tabindex="-1" aria-labelledby="editKontakLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('contact.update', $kontak->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editKontakLabel"><i class="fas fa-edit me-1"></i> Edit Kontak</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <input type="text" name="alamat" class="form-control" value="{{ $kontak->alamat }}"
                            required>
                    </div>


                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="telepon" class="form-label">Telepon</label>
                            <input type="text" name="telepon" class="form-control" value="{{ $kontak->telepon }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="whatsapp" class="form-label">WhatsApp</label>
                            <input type="text" name="whatsapp" class="form-control" value="{{ $kontak->whatsapp }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ $kontak->email }}">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="latitude" class="form-label">Latitude</label>
                            <input type="text" id="latInput" name="latitude" class="form-control"
                                value="{{ $kontak->latitude }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="longitude" class="form-label">Longitude</label>
                            <input type="text" id="lngInput" name="longitude" class="form-control"
                                value="{{ $kontak->longitude }}">
                        </div>
                    </div>



                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="facebook" class="form-label">Facebook</label>
                            <input type="text" name="facebook" class="form-control" value="{{ $kontak->facebook }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="instagram" class="form-label">Instagram</label>
                            <input type="text" name="instagram" class="form-control"
                                value="{{ $kontak->instagram }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="youtube" class="form-label">YouTube</label>
                            <input type="text" name="youtube" class="form-control" value="{{ $kontak->youtube }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tiktok" class="form-label">TikTok</label>
                            <input type="text" name="tiktok" class="form-control" value="{{ $kontak->tiktok }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Pilih Lokasi di Peta</label>
                        <div id="editMap" style="height: 300px; border-radius: .5rem;"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let lat = {{ $kontak->latitude ?? 0 }};
            let lng = {{ $kontak->longitude ?? 0 }};

            const editMap = L.map('editMap').setView([lat, lng], 15);
            const marker = L.marker([lat, lng], {
                draggable: true
            }).addTo(editMap);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(editMap);

            function fetchReverseGeocode(lat, lng) {
                fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`)
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.display_name) {
                            document.querySelector('input[name="alamat"]').value = data.display_name;
                        }
                    })
                    .catch(err => console.error('Gagal ambil alamat:', err));
            }

            // Ketika marker digeser
            marker.on('dragend', function(e) {
                const position = marker.getLatLng();
                document.getElementById('latInput').value = position.lat;
                document.getElementById('lngInput').value = position.lng;
                fetchReverseGeocode(position.lat, position.lng); // Isi alamat otomatis
            });

            // Ketika map diklik
            editMap.on('click', function(e) {
                marker.setLatLng(e.latlng);
                document.getElementById('latInput').value = e.latlng.lat;
                document.getElementById('lngInput').value = e.latlng.lng;
                fetchReverseGeocode(e.latlng.lat, e.latlng.lng); // Isi alamat otomatis
            });

            // Resize map saat modal muncul
            const modal = document.getElementById('editKontakModal');
            modal.addEventListener('shown.bs.modal', function() {
                setTimeout(() => editMap.invalidateSize(), 200);
            });
        });
    </script>
@endpush
