<?php
// cust/index.php - Halaman Utama (Homepage) untuk Customer
// Menampilkan informasi umum, layanan unggulan, dan alur pemesanan.
include 'partials/header.php'; 
?>

<!-- Hero Section -->
<!-- Bagian banner utama yang menyambut pengunjung dengan judul dan tombol aksi -->
<section class="hero-section text-center text-lg-start">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-6 mb-5 mb-lg-0">
        <h1 class="hero-title">
          Laundry Bersih,<br>
          <span class="text-primary">Wangi & Rapi</span>
        </h1>
        <p class="hero-subtitle mb-4">
          Serahkan urusan cucian kotor Anda kepada kami. Layanan laundry profesional dengan teknologi modern untuk hasil terbaik!
        </p>
        <div class="d-flex gap-3 justify-content-center justify-content-lg-start">
          <a href="/laundry_project/customer/portal.php" class="btn btn-primary btn-lg px-5 shadow-lg">
            Cek Pesanan
          </a>
          <a href="layanan.php" class="btn btn-outline-primary btn-lg px-5">
            Lihat Layanan
          </a>
        </div>
        
        <div class="d-flex gap-4 mt-5 pt-3 border-top justify-content-center justify-content-lg-start">
          <div>
            <h3 class="fw-bold mb-0">1k+</h3>
            <span class="text-muted">Pelanggan</span>
          </div>
          <div>
            <h3 class="fw-bold mb-0">4.9</h3>
            <span class="text-muted">Rating</span>
          </div>
        </div>
      </div>
      
      <div class="col-lg-6">
        <img src="assets/img/laundry.jpg" onerror="this.src='https://placehold.co/600x500/e6f4ff/0099ff?text=Laundry+Illustration'" class="img-fluid rounded-4 shadow-lg animate__animated animate__fadeInRight" alt="Laundry Service">
      </div>
    </div>
  </div>
</section>

<!-- Features Section -->
<section class="py-5">
  <div class="container py-5">
    <div class="row g-4">
      <div class="col-md-3">
        <div class="feature-card text-center p-4">
          <div class="feature-icon mx-auto">
            <i class="bi bi-stopwatch"></i>
          </div>
          <h5 class="fw-bold">Proses Cepat</h5>
          <p class="text-muted small">Layanan ekspres 3 jam selesai untuk kebutuhan mendesak Anda.</p>
        </div>
      </div>
      <div class="col-md-3">
        <div class="feature-card text-center p-4">
          <div class="feature-icon mx-auto">
            <i class="bi bi-flower1"></i>
          </div>
          <h5 class="fw-bold">Wangi Tahan Lama</h5>
          <p class="text-muted small">Menggunakan pewangi premium yang tahan hingga 14 hari.</p>
        </div>
      </div>
      <div class="col-md-3">
        <div class="feature-card text-center p-4">
          <div class="feature-icon mx-auto">
            <i class="bi bi-shield-check"></i>
          </div>
          <h5 class="fw-bold">Jaminan Bersih</h5>
          <p class="text-muted small">Garansi cuci ulang jika hasil cucian kurang bersih.</p>
        </div>
      </div>
      <div class="col-md-3">
        <div class="feature-card text-center p-4">
          <div class="feature-icon mx-auto">
            <i class="bi bi-wallet2"></i>
          </div>
          <h5 class="fw-bold">Harga Terjangkau</h5>
          <p class="text-muted small">Kualitas layanan premium dengan harga yang bersahabat.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Services Section -->
<section class="py-5 bg-light">
  <div class="container py-5">
    <div class="text-center mb-5">
      <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill mb-3">LAYANAN KAMI</span>
      <h2 class="fw-bold display-6">Solusi Laundry Lengkap</h2>
      <p class="text-muted">Pilih layanan yang sesuai dengan kebutuhan pakaian Anda</p>
    </div>

    <div class="row g-4">
      <!-- Service 1 -->
      <div class="col-md-6 col-lg-3">
        <div class="service-card text-center">
          <div class="service-img-wrapper shadow-sm">
            <img src="assets/img/kiloan.jpg" onerror="this.src='https://placehold.co/400x300/e6f4ff/0099ff?text=Cuci+Kiloan'" class="service-img" alt="Cuci Kiloan">
          </div>
          <h5 class="fw-bold mt-3">Cuci Komplit</h5>
          <p class="text-muted small">Cuci, Kering, Setrika, & Packing Rapi.<br>Mulai <strong>Rp 6.000/kg</strong></p>
        </div>
      </div>

      <!-- Service 2 -->
      <div class="col-md-6 col-lg-3">
        <div class="service-card text-center">
          <div class="service-img-wrapper shadow-sm">
            <img src="assets/img/perawatan.jpg" onerror="this.src='https://placehold.co/400x300/e6f4ff/0099ff?text=Dry+Cleaning'" class="service-img" alt="Dry Cleaning">
          </div>
          <h5 class="fw-bold mt-3">Dry Cleaning</h5>
          <p class="text-muted small">Perawatan khusus untuk jas, gaun, dan kebaya.<br>Mulai <strong>Rp 25.000/pcs</strong></p>
        </div>
      </div>

      <!-- Service 3 -->
      <div class="col-md-6 col-lg-3">
        <div class="service-card text-center">
          <div class="service-img-wrapper shadow-sm">
            <img src="assets/img/sepatu.jpg" onerror="this.src='https://placehold.co/400x300/e6f4ff/0099ff?text=Cuci+Sepatu'" class="service-img" alt="Shoes Care">
          </div>
          <h5 class="fw-bold mt-3">Shoes & Bag Care</h5>
          <p class="text-muted small">Deep cleaning sepatu dan tas kesayangan.<br>Mulai <strong>Rp 35.000/psg</strong></p>
        </div>
      </div>

      <!-- Service 4 -->
      <div class="col-md-6 col-lg-3">
        <div class="service-card text-center">
          <div class="service-img-wrapper shadow-sm">
            <img src="assets/img/setrika.jpg" onerror="this.src='https://placehold.co/400x300/e6f4ff/0099ff?text=Setrika+Saja'" class="service-img" alt="Ironing">
          </div>
          <h5 class="fw-bold mt-3">Setrika Saja</h5>
          <p class="text-muted small">Hanya jasa setrika uap, rapi dan licin.<br>Mulai <strong>Rp 4.000/kg</strong></p>
        </div>
      </div>
    </div>

    <div class="text-center mt-5">
      <a href="layanan.php" class="btn btn-outline-primary rounded-pill px-5">Lihat Semua Layanan</a>
    </div>
  </div>
</section>

<!-- Process Section -->
<section class="py-5">
  <div class="container py-5">
    <div class="row align-items-center">
      <div class="col-lg-5 order-2 order-lg-1">
        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill mb-3">CARA KERJA</span>
        <h2 class="fw-bold display-6 mb-4">Mudah & Praktis</h2>
        
        <div class="d-flex mb-4">
          <div class="me-3">
            <div class="step-number">1</div>
          </div>
          <div>
            <h5 class="fw-bold">Datang ke Outlet</h5>
            <p class="text-muted">Bawa pakaian kotor Anda ke outlet kami.</p>
          </div>
        </div>

        <div class="d-flex mb-4">
          <div class="me-3">
            <div class="step-number">2</div>
          </div>
          <div>
            <h5 class="fw-bold">Timbang & Bayar</h5>
            <p class="text-muted">Petugas akan menimbang cucian dan Anda melakukan pembayaran.</p>
          </div>
        </div>

        <div class="d-flex mb-4">
          <div class="me-3">
            <div class="step-number">3</div>
          </div>
          <div>
            <h5 class="fw-bold">Ambil Bersih</h5>
            <p class="text-muted">Pakaian Anda siap diambil dalam keadaan bersih, wangi, dan rapi.</p>
          </div>
        </div>

        <a href="kontak.php" class="btn btn-primary rounded-pill px-4 mt-2">Cek Lokasi Kami</a>
      </div>
      
      <div class="col-lg-7 order-1 order-lg-2 mb-5 mb-lg-0 text-center">
        <img src="assets/img/alur.jpg" onerror="this.src='https://placehold.co/600x400/e6f4ff/0099ff?text=Process+Flow'" class="img-fluid rounded-4" alt="Working Process">
      </div>
    </div>
  </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-primary text-white text-center">
  <div class="container py-4">
    <h2 class="fw-bold mb-3">Siap Untuk Pakaian yang Lebih Bersih?</h2>
    <p class="lead mb-4 text-white-50">Bergabunglah dengan ribuan pelanggan puas kami hari ini!</p>
  </div>
</section>

<?php include 'partials/footer.php'; ?>
