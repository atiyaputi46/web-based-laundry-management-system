</main>

<footer>
  <!-- cust/partials/footer.php - Footer Template untuk sisi Customer -->
  <!-- Menampilkan link navigasi bawah, info kontak, dan copyright -->
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-4">
        <h5 class="d-flex align-items-center gap-2">
          <i class="bi bi-droplet-fill text-primary"></i> Kinclong Laundry
        </h5>
        <p>
          Layanan laundry kiloan dan satuan profesional dengan teknologi pencucian terkini dan deterjen ramah lingkungan.
        </p>
        <div class="d-flex gap-3 fs-4 mt-3">
          <a href="#"><i class="bi bi-instagram"></i></a>
          <a href="#"><i class="bi bi-facebook"></i></a>
          <a href="#"><i class="bi bi-twitter-x"></i></a>
        </div>
      </div>
      
      <div class="col-lg-2 col-6">
        <h5>Layanan</h5>
        <ul class="list-unstyled d-flex flex-column gap-2">
          <li><a href="/laundry_project/cust/layanan.php">Cuci Komplit</a></li>
          <li><a href="/laundry_project/cust/layanan.php">Cuci Setrika</a></li>
          <li><a href="/laundry_project/cust/layanan.php">Dry Clean</a></li>
          <li><a href="/laundry_project/cust/layanan.php">Cuci Sepatu</a></li>
        </ul>
      </div>

      <div class="col-lg-2 col-6">
        <h5>Perusahaan</h5>
        <ul class="list-unstyled d-flex flex-column gap-2">
          <li><a href="/laundry_project/cust/tentang.php">Tentang Kami</a></li>
          <li><a href="/laundry_project/cust/kontak.php">Lokasi</a></li>
        </ul>
      </div>

      <div class="col-lg-4">
        <h5>Kontak Kami</h5>
        <ul class="list-unstyled d-flex flex-column gap-3">
           <li class="d-flex gap-3">
             <i class="bi bi-geo-alt mt-1 text-primary"></i>
             <span>Jl. Raya Cupak Tangah<br>Kec. Pauh Kota Padang</span>
           </li>
           <li class="d-flex gap-3">
             <i class="bi bi-whatsapp mt-1 text-primary"></i>
             <span>083189252389</span>
           </li>
           <li class="d-flex gap-3">
             <i class="bi bi-envelope mt-1 text-primary"></i>
             <span>cs@kinclonglaundry.com</span>
           </li>
        </ul>
      </div>
    </div>
    
    <div class="border-top border-secondary mt-5 pt-4 text-center">
      <small>&copy; <?= date('Y') ?> Kinclong Laundry. All rights reserved.</small>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
