$(document).ready(function () {
    $('#settingUploadNilai').on('click', function () {
        const url = $(this).data('url');
        confirmAndPost('Yakin ingin Setting Upload Nilai?', url);
    });

    $('#settingbiodata').on('click', function () {
        const url = $(this).data('url');
        confirmAndPost('Yakin ingin Setting Biodata?', url);
    });

    $('#btnMoveNextJadwal').on('click', function () {
        const url = $(this).data('url');
        confirmAndPost('Yakin ingin pindah ke jadwal berikutnya?', url);
    });

    $('#btnPublishRanking').on('click', function () {
        const url = $(this).data('url');
        confirmAndPost('Yakin ingin publish perangkingan sekarang?', url);
    });

    $('#btnGenerateDummy').on('click', function () {
        const url = $(this).data('url');
        confirmAndPost('Yakin ingin generate dummy pendaftar?', url, function (data) {
            if (data.success) {
                $('.total-pendaftar-count').text(data.totalPendaftar);
            }
        });
    });
});

/**
 * General confirmation + POST + feedback handler
 */
function confirmAndPost(message, url, onSuccess = null) {
    Swal.fire({
        title: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, lanjut!',
        cancelButtonText: 'Batal',
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Sedang memproses...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: url,
                method: 'POST',
                dataType: 'json',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content') // Pastikan meta tag ada
                },
                success: function (data) {
                    Swal.close();
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message || 'Berhasil diproses.'
                    }).then(() => {
                        if (onSuccess) onSuccess(data);
                        else location.reload();
                    });
                },
                error: function (xhr) {
                    Swal.close();
                    let message = 'Terjadi kesalahan pada server.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: message
                    });
                }
            });
        }
    });
}
