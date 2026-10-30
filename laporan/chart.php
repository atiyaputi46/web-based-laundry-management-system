<?php
// laporan/chart.php - Halaman Visualisasi Grafik Omzet
include '../layout/header.php';
include '../config/koneksi.php';
?>

<!-- Page Header -->
<div class="page-header">
  <div>
    <h2 class="page-title">
      <i class="bi bi-graph-up text-primary"></i> Grafik Performa Omzet
    </h2>
    <p class="page-subtitle">Visualisasi tren pendapatan harian dari transaksi laundry 7 hari terakhir.</p>
  </div>
  <div>
    <a href="index.php" class="btn btn-secondary">
      <i class="bi bi-arrow-left"></i> Kembali ke Laporan
    </a>
  </div>
</div>

<div class="card-modern">
  <div class="card-modern-header">
    <h5 class="card-modern-title">
      <i class="bi bi-bezier2 text-primary"></i> Kurva Pendapatan Harian
    </h5>
    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle px-3 py-2">
      Data Real-time Transaksi
    </span>
  </div>
  <div class="card-modern-body">
    <div style="position: relative; height: 380px; width: 100%;">
      <canvas id="chartOmzet"></canvas>
    </div>
  </div>
</div>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
fetch('chart_data.php')
  .then(res => res.json())
  .then(data => {
    const ctx = document.getElementById('chartOmzet').getContext('2d');

    const gradient = ctx.createLinearGradient(0, 0, 0, 360);
    gradient.addColorStop(0, 'rgba(52, 120, 246, 0.4)');
    gradient.addColorStop(1, 'rgba(52, 120, 246, 0.0)');

    const formattedLabels = (data.labels || []).map(label => {
      const d = new Date(label);
      if (isNaN(d.getTime())) return label;
      return d.toLocaleDateString('id-ID', { weekday: 'short', day: 'numeric', month: 'short' });
    });

    new Chart(ctx, {
      type: 'line',
      data: {
        labels: formattedLabels,
        datasets: [{
          label: 'Omzet Harian (Rp)',
          data: data.data,
          borderColor: '#3478F6',
          backgroundColor: gradient,
          borderWidth: 3,
          fill: true,
          tension: 0.35,
          pointBackgroundColor: '#FFFFFF',
          pointBorderColor: '#3478F6',
          pointBorderWidth: 3,
          pointRadius: 5,
          pointHoverRadius: 7,
          pointHoverBackgroundColor: '#3478F6',
          pointHoverBorderColor: '#FFFFFF'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false
          },
          tooltip: {
            backgroundColor: '#17243A',
            titleFont: { family: 'Plus Jakarta Sans', size: 13, weight: '600' },
            bodyFont: { family: 'Plus Jakarta Sans', size: 14, weight: 'bold' },
            padding: 12,
            cornerRadius: 8,
            displayColors: false,
            callbacks: {
              label: function(context) {
                return 'Omzet: Rp ' + Number(context.raw || 0).toLocaleString('id-ID');
              }
            }
          }
        },
        scales: {
          x: {
            grid: {
              display: false
            },
            ticks: {
              font: { family: 'Plus Jakarta Sans', size: 12 },
              color: '#64748B'
            }
          },
          y: {
            beginAtZero: true,
            grid: {
              color: '#F1F5F9'
            },
            ticks: {
              font: { family: 'Plus Jakarta Sans', size: 12 },
              color: '#64748B',
              callback: function(value) {
                return 'Rp ' + Number(value).toLocaleString('id-ID');
              }
            }
          }
        }
      }
    });
  })
  .catch(err => console.error('Error fetching chart data:', err));
</script>

<?php include '../layout/footer.php'; ?>
