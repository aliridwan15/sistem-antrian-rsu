<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>403 - Akses Ditolak</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        :root {
            --primary: #1B9C85;
            --dark: #0f3d34;
            --light: #f4fdfb;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--primary), var(--dark));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .card {
            background: white;
            color: #333;
            border-radius: 20px;
            padding: 50px 40px;
            max-width: 420px;
            width: 100%;
            text-align: center;
            box-shadow: 0 20px 50px rgba(0,0,0,.3);
            animation: fadeIn 0.6s ease;
        }

        .icon {
            font-size: 70px;
            color: var(--primary);
            margin-bottom: 15px;
        }

        h1 {
            font-size: 72px;
            color: var(--primary);
            margin-bottom: 10px;
        }

        h2 {
            font-size: 22px;
            margin-bottom: 10px;
        }

        p {
            font-size: 14px;
            color: #666;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        a {
            display: inline-block;
            padding: 12px 28px;
            border-radius: 50px;
            background: var(--primary);
            color: white;
            text-decoration: none;
            font-weight: 600;
            transition: .3s;
        }

        a:hover {
            background: #14806c;
            transform: translateY(-2px);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 480px) {
            h1 {
                font-size: 56px;
            }
        }
    </style>
</head>
<body>

    <div class="card">
        <div class="icon">🚫</div>
        <h1>403</h1>
        <h2>Akses Ditolak</h2>
        <p>
            Maaf, kamu tidak memiliki izin untuk mengakses halaman ini.
            Jika kamu merasa ini kesalahan, silakan hubungi administrator.
        </p>

        <a href="{{ route('home') }}">Kembali ke Beranda</a>
    </div>

</body>
</html>
