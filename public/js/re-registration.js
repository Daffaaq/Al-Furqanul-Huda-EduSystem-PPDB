document.addEventListener('DOMContentLoaded', function () {
    const btnReRegister = document.getElementById('btnReRegister');

    if (btnReRegister) {
        btnReRegister.addEventListener('click', function () {
            const url = btnReRegister.getAttribute('data-url');

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    if (response.ok) {
                        location.reload();
                    }
                })
                .catch(error => alert('Terjadi kesalahan: ' + error));
        });
    }
});
