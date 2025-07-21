<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Al-Furqanul Huda EduSystem</title>

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts: Playfair Display (klasik) & Poppins (modern) -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Poppins&display=swap"
        rel="stylesheet">

    <style>
        body {
            background: linear-gradient(to bottom right, #fdfcfb, #e9e4d5);
            background-image: url('https://www.transparenttextures.com/patterns/arabesque.png');
            background-attachment: fixed;
            overflow: hidden;
            font-family: 'Poppins', sans-serif;
        }

        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            border: none;
            box-shadow: 0 0 10px rgba(255, 215, 0, 1);
            border-radius: 12px;
            padding: 2rem;
            background-color: #fff;
            width: 100%;
            max-width: 420px;
        }

        .system-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.6rem;
            font-weight: 600;
            text-align: center;
            color: #b48b3c;
            /* emas */
            margin-bottom: 1.5rem;
        }

        .form-control {
            border-radius: 12px;
            border: 2px solid #ccc;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-control:focus {
            border-color: rgba(255, 215, 0, 1);
            /* warna emas */
            box-shadow: 0 0 8px 2px rgba(255, 215, 0, 1);
            /* glow efek */
            outline: none;
            /* hilangkan garis default biru */
        }


        .form-label {
            font-weight: 500;
        }

        .btn-gold {
            background-color: #b48b3c;
            color: white;
        }

        .btn-gold:hover {
            background-color: rgba(255, 215, 0, 1);
        }

        .btn-gold:hover {
            color: white
        }

        .btn-outline-secondary {
            border-color: #b48b3c;
            color: #b48b3c;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .btn-outline-secondary:hover {
            background-color: #b48b3c;
            color: white;
        }


        .quote {
            font-size: 0.9rem;
            color: rgba(255, 215, 0, 1);
            margin-top: 1.5rem;
            text-align: center;
            font-style: italic;
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.8s ease, transform 0.8s ease;
            min-height: 60px;
        }


        .quote.show {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>

<body>

    <div class="login-container">
        <div class="login-card">
            <div class="system-title">Al-Furqanul Huda EduSystem</div>

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" id="email"
                        placeholder="nama@email.com" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Kata Sandi</label>
                    <input type="password" name="password" class="form-control" id="password" placeholder="••••••••"
                        required>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-gold">Login</button>
                </div>
            </form>
            <div class="text-center mt-3">
                <a href="{{ route('landing-page') }}" class="btn btn-outline-secondary">
                    ← Kembali ke Beranda
                </a>
            </div>


            <div class="quote mt-4" id="quoteText">Memuat quotes...</div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let quotes = [];
        let currentQuote = 0;
        const quoteEl = document.getElementById('quoteText');
        let firstShow = true;

        function showQuote(index) {
            if (firstShow) {
                quoteEl.innerHTML = `“${quotes[index].text}”<br>— ${quotes[index].author}`;
                quoteEl.classList.add('show');
                firstShow = false;
            } else {
                quoteEl.classList.remove('show');
                setTimeout(() => {
                    quoteEl.innerHTML = `“${quotes[index].text}”<br>— ${quotes[index].author}`;
                    quoteEl.classList.add('show');
                }, 900);
            }
        }

        function rotateQuotes() {
            if (quotes.length === 0) return;
            showQuote(currentQuote);
            currentQuote = (currentQuote + 1) % quotes.length;
        }

        // Ganti fetch dengan jQuery AJAX
        $.ajax({
            url: "{{ route('quotes.login') }}",
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                quotes = data;
                rotateQuotes();
                setInterval(rotateQuotes, 9000);
            },
            error: function(xhr, status, error) {
                console.error('Gagal memuat quotes:', error);
                quoteEl.innerHTML = 'Gagal memuat quotes.';
                quoteEl.classList.add('show');
            }
        });
    </script>

</body>

</html>
