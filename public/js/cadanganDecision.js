document.addEventListener('DOMContentLoaded', function () {
    const btnAccept = document.getElementById('btnAccept');
    const btnReject = document.getElementById('btnReject');

    function handleClick(event) {
        const url = event.target.getAttribute('data-url');
        const decision = event.target.getAttribute('data-decision');

        Swal.fire({
            title: decision === 'accept' ? 'Lanjutkan ke tahap berikutnya?' : 'Tolak kesempatan ini?',
            text: decision === 'accept'
                ? 'Setelah melanjutkan, Anda akan diproses ke tahap selanjutnya.'
                : 'Anda tidak akan melanjutkan proses seleksi.',
            icon: decision === 'accept' ? 'question' : 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, lanjutkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ decision })
                })
                    .then(response => response.json())
                    .then(data => {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: data.message,
                            icon: 'success'
                        }).then(() => {
                            if (data.status === 'lanjut' || data.status === 'tidak_lanjut') {
                                location.reload();
                            }
                        });
                    })
                    .catch(error => {
                        Swal.fire('Error!', 'Terjadi kesalahan: ' + error, 'error');
                    });
            }
        });
    }

    if (btnAccept) btnAccept.addEventListener('click', handleClick);
    if (btnReject) btnReject.addEventListener('click', handleClick);
});
