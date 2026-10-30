<?php include '../config/koneksi.php'; ?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Cek Status Laundry | Kinclong Laundry</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #17243A 0%, #101a2b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: #17243A;
        }
        .card-cek {
            background: #FFFFFF;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 440px;
            padding: 36px 32px;
        }
        .icon-circle {
            width: 54px;
            height: 54px;
            border-radius: 14px;
            background: linear-gradient(135deg, #3478F6 0%, #1d4ed8 100%);
            color: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            margin: 0 auto 16px;
            box-shadow: 0 6px 18px rgba(52, 120, 246, 0.35);
        }
        .btn-cek {
            background: linear-gradient(135deg, #3478F6 0%, #2563EB 100%);
            border: none;
            color: #FFFFFF;
            font-weight: 700;
            padding: 12px;
            border-radius: 10px;
            box-shadow: 0 4px 14px rgba(52, 120, 246, 0.3);
            transition: all 0.2s ease;
        }
        .btn-cek:hover {
            background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%);
            color: #FFFFFF;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(52, 120, 246, 0.4);
        }
    </style>
</head>

<body>

<div class="card-cek text-center">
    <div class="icon-circle">
        <i class="bi bi-search"></i>
    </div>
    <h4 class="fw-bold mb-1" style="color: #17243A;">Cek Status Laundry</h4>
    <p class="text-muted small mb-4">Masukkan nomor ID pesanan Anda untuk mengecek proses pengerjaan cucian.</p>

    <form method="post" action="cek.php">
        <div class="mb-3">
            <input type="text"
                   name="kode"
                   class="form-control form-control-lg text-center fw-bold"
                   placeholder="Contoh: 1001"
                   style="border: 2px solid #E2E8F0; border-radius: 10px; font-size: 1.1rem;"
                   required autofocus>
        </div>

        <button type="submit" class="btn btn-cek w-100 fs-6 mb-3">
            <i class="bi bi-arrow-right-circle me-1"></i> Periksa Status Sekarang
        </button>
    </form>

    <div class="border-top pt-3">
        <a href="../cust/index.php" class="text-decoration-none small text-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Halaman Utama
        </a>
    </div>
</div>

</body>
</html>
