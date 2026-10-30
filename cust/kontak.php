<?php 
// cust/kontak.php - Halaman Kontak
// Menampilkan peta lokasi, informasi alamat, dan formulir pesan.
include 'partials/header.php'; 
?>

<div class="container py-5">
  <div class="row g-5">
    <!-- Contact Info & Map -->
    <div class="col-lg-6">
      <h3 class="fw-bold mb-4">Lokasi Kami</h3>
      
      <div class="d-flex gap-3 mb-4">
        <div class="bg-primary bg-opacity-10 p-3 rounded-circle text-primary" style="height: fit-content;">
          <i class="bi bi-geo-alt fs-4"></i>
        </div>
        <div>
          <h5 class="fw-bold">Kinclong Laundry</h5>
          <p class="text-muted mb-0">
            Jl. Raya Cupak Tangah Kec. Pauh<br>
            Padang, Sumatera Barat
          </p>
        </div>
      </div>

      <div class="card border-0 shadow-sm overflow-hidden rounded-4">
        <iframe 
          src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d3989.310046983363!2d100.43094574013745!3d-0.9326751264917517!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zMMKwNTUnNTcuNiJTIDEwMMKwMjUnNTEuNCJF!5e0!3m2!1sen!2sid!4v1710000000000!5m2!1sen!2sid" 
          width="100%" 
          height="300" 
          style="border:0;" 
          allowfullscreen="" 
          loading="lazy" 
          referrerpolicy="no-referrer-when-downgrade">
        </iframe>
      </div>
    </div>

    <!-- Contact Form -->
    <div class="col-lg-6">
      <h3 class="fw-bold mb-4">Kirimi Kami Pesan</h3>
      <p class="text-muted mb-4">Punya pertanyaan atau ingin booking khusus? Hubungi kami langsung.</p>

      <!-- Contact Form -->
      <!-- Formulir untuk pengunjung mengirim pesan (saat ini frontend only/validasi JS) -->
      <form class="row g-3 needs-validation" novalidate>
        <div class="col-12">
          <label class="form-label">Nama Lengkap</label>
          <input type="text" class="form-control form-control-lg bg-light border-0" required>
          <div class="invalid-feedback">Nama wajib diisi</div>
        </div>

        <div class="col-12">
          <label class="form-label">Email / WhatsApp</label>
          <input type="text" class="form-control form-control-lg bg-light border-0" required>
          <div class="invalid-feedback">Kontak wajib diisi</div>
        </div>

        <div class="col-12">
          <label class="form-label">Pesan</label>
          <textarea class="form-control form-control-lg bg-light border-0" rows="5" required></textarea>
          <div class="invalid-feedback">Pesan wajib diisi</div>
        </div>

        <div class="col-12 mt-4">
          <button class="btn btn-primary btn-lg rounded-pill w-100">Kirim Pesan</button>
        </div>
      </form>
    </div>
  </div>

  <script>
  (() => {
    'use strict'
    const forms = document.querySelectorAll('.needs-validation')
    Array.from(forms).forEach(form => {
      form.addEventListener('submit', event => {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }
        form.classList.add('was-validated')
      }, false)
    })
  })()
  </script>
</div>

<?php include 'partials/footer.php'; ?>
