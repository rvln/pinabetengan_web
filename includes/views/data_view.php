<?php
require_once 'includes/header.php';
require_once 'includes/navbar.php';
?>

<!-- HERO -->
<section class="page-hero">
  <div class="container">
    <div class="page-hero-content fade-in">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
          <li class="breadcrumb-item active">Data Statistik</li>
        </ol>
      </nav>
      <h1 class="page-title">Data Statistik Desa</h1>
      <p class="page-subtitle">
        Data terkini mengenai kependudukan, pendidikan, dan pekerjaan masyarakat Desa Pinabetengan Selatan.
      </p>
    </div>
  </div>
</section>

<!-- STATISTIK UTAMA -->
<section class="section">
  <div class="container">
    <div class="section-header">
      <h2 class="section-title">Statistik Utama</h2>
      <p class="section-subtitle">Data pokok kependudukan Desa Pinabetengan Selatan Tahun <?= $data_penduduk['tahun'] ?></p>
    </div>

    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-number"><?= number_format($data_penduduk['total_penduduk']) ?></div>
        <div class="stat-label">Total Penduduk</div>
      </div>
      <div class="stat-card">
        <div class="stat-number"><?= number_format($data_penduduk['kepala_keluarga']) ?></div>
        <div class="stat-label">Kepala Keluarga</div>
      </div>
      <div class="stat-card">
        <div class="stat-number"><?= number_format($data_penduduk['laki_laki']) ?></div>
        <div class="stat-label">Penduduk Laki-laki</div>
      </div>
      <div class="stat-card">
        <div class="stat-number"><?= number_format($data_penduduk['perempuan']) ?></div>
        <div class="stat-label">Penduduk Perempuan</div>
      </div>
    </div>
  </div>
</section>

<!-- DATA PENDUDUK -->
<section class="section section-alt">
  <div class="container">
    <div class="data-section">
      <div class="section-header">
        <h2 class="section-title">Data Kependudukan</h2>
        <p class="section-subtitle">Komposisi dan karakteristik penduduk Desa Pinabetengan Selatan</p>
      </div>

      <div class="row g-4">
        <div class="col-lg-6">
          <div class="data-card">
            <div class="data-header">
              <div class="data-icon icon-population">
                <i class="fas fa-users"></i>
              </div>
              <h3 class="data-title">Komposisi Penduduk</h3>
            </div>

            <ul class="data-list">
              <li class="data-item">
                <span class="data-label">Total Penduduk</span>
                <span class="data-value"><?= number_format($data_penduduk['total_penduduk']) ?> Jiwa</span>
              </li>
              <li class="data-item">
                <span class="data-label">Laki-laki</span>
                <div>
                  <span class="data-value"><?= number_format($data_penduduk['laki_laki']) ?></span>
                  <span class="data-percentage"><?= number_format(($data_penduduk['laki_laki'] / $data_penduduk['total_penduduk']) * 100, 1) ?>%</span>
                </div>
              </li>
              <li class="data-item">
                <span class="data-label">Perempuan</span>
                <div>
                  <span class="data-value"><?= number_format($data_penduduk['perempuan']) ?></span>
                  <span class="data-percentage"><?= number_format(($data_penduduk['perempuan'] / $data_penduduk['total_penduduk']) * 100, 1) ?>%</span>
                </div>
              </li>
              <li class="data-item">
                <span class="data-label">Kepala Keluarga</span>
                <span class="data-value"><?= number_format($data_penduduk['kepala_keluarga']) ?> KK</span>
              </li>
              <li class="data-item">
                <span class="data-label">Kepadatan Penduduk</span>
                <span class="data-value"><?= number_format($data_penduduk['kepadatan_penduduk']) ?> jiwa/km²</span>
              </li>
            </ul>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="chart-container">
            <h4 class="chart-title"><i class="fas fa-chart-pie"></i> Grafik Komposisi Penduduk</h4>
            <div class="chart-wrapper">
              <canvas id="pendudukChart"></canvas>
            </div>
          </div>

          <div class="info-card">
            <h4><i class="fas fa-info-circle"></i> Informasi Data</h4>
            <p>Data kependudukan diperbarui secara berkala berdasarkan hasil pendataan dan administrasi kependudukan desa.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- DATA PENDIDIKAN -->
<section class="section">
  <div class="container">
    <div class="data-section">
      <div class="section-header">
        <h2 class="section-title">Data Pendidikan</h2>
        <p class="section-subtitle">Tingkat pendidikan masyarakat Desa Pinabetengan Selatan</p>
      </div>

      <div class="row g-4">
        <div class="col-lg-6">
          <div class="data-card">
            <div class="data-header">
              <div class="data-icon icon-education">
                <i class="fas fa-graduation-cap"></i>
              </div>
              <h3 class="data-title">Tingkat Pendidikan</h3>
            </div>

            <ul class="data-list">
              <?php foreach($pendidikan as $edu): ?>
              <li class="data-item">
                <span class="data-label"><?= htmlspecialchars($edu['tingkat']) ?></span>
                <div>
                  <span class="data-value"><?= number_format($edu['jumlah']) ?></span>
                  <span class="data-percentage"><?= number_format($edu['persentase'], 1) ?>%</span>
                </div>
              </li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="chart-container">
            <h4 class="chart-title"><i class="fas fa-chart-bar"></i> Grafik Tingkat Pendidikan</h4>
            <div class="chart-wrapper">
              <canvas id="pendidikanChart"></canvas>
            </div>
          </div>

          <div class="info-card">
            <h4><i class="fas fa-chart-line"></i> Trend Pendidikan</h4>
            <p>Terdapat peningkatan jumlah masyarakat dengan pendidikan tinggi dalam 5 tahun terakhir.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- DATA PEKERJAAN -->
<section class="section section-alt">
  <div class="container">
    <div class="data-section">
      <div class="section-header">
        <h2 class="section-title">Data Pekerjaan</h2>
        <p class="section-subtitle">Jenis pekerjaan dan mata pencaharian masyarakat</p>
      </div>

      <div class="row g-4">
        <div class="col-lg-6">
          <div class="data-card">
            <div class="data-header">
              <div class="data-icon icon-work">
                <i class="fas fa-briefcase"></i>
              </div>
              <h3 class="data-title">Jenis Pekerjaan</h3>
            </div>

            <ul class="data-list">
              <?php foreach($pekerjaan as $job): ?>
              <li class="data-item">
                <span class="data-label"><?= htmlspecialchars($job['jenis']) ?></span>
                <div>
                  <span class="data-value"><?= number_format($job['jumlah']) ?></span>
                  <span class="data-percentage"><?= number_format($job['persentase'], 1) ?>%</span>
                </div>
              </li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="chart-container">
            <h4 class="chart-title"><i class="fas fa-chart-bar"></i> Grafik Distribusi Pekerjaan</h4>
            <div class="chart-wrapper">
              <canvas id="pekerjaanChart"></canvas>
            </div>
          </div>

          <div class="info-card">
            <h4><i class="fas fa-industry"></i> Potensi Ekonomi</h4>
            <p>Sebagian besar masyarakat bergerak di sektor pertanian dan UMKM.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- SCRIPTS FOR CHARTS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Chart.js Configuration
function getChartColors() {
  const isDark = document.body.getAttribute('data-theme') === 'dark';
  return {
    textColor: isDark ? '#E0E0E0' : '#2C2C2C',
    gridColor: isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)',
    borderColor: isDark ? 'rgba(255, 255, 255, 0.2)' : 'rgba(0, 0, 0, 0.1)'
  };
}

// Initialize Charts
function initCharts() {
  const colors = getChartColors();

  // Chart Komposisi Penduduk (Pie Chart)
  const pendudukCtx = document.getElementById('pendudukChart');
  if(pendudukCtx) {
    new Chart(pendudukCtx.getContext('2d'), {
      type: 'pie',
      data: {
        labels: <?= json_encode($penduduk_labels) ?>,
        datasets: [{
          data: <?= json_encode($penduduk_data) ?>,
          backgroundColor: <?= json_encode($penduduk_colors) ?>,
          borderColor: colors.borderColor,
          borderWidth: 2
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'bottom',
            labels: {
              color: colors.textColor,
              font: {
                family: 'Inter, sans-serif'
              }
            }
          },
          tooltip: {
            callbacks: {
              label: function(context) {
                const label = context.label || '';
                const value = context.raw || 0;
                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                const percentage = Math.round((value / total) * 100);
                return `${label}: ${value.toLocaleString()} (${percentage}%)`;
              }
            }
          }
        }
      }
    });
  }

  // Chart Pendidikan (Bar Chart)
  const pendidikanCtx = document.getElementById('pendidikanChart');
  if(pendidikanCtx) {
    new Chart(pendidikanCtx.getContext('2d'), {
      type: 'bar',
      data: {
        labels: <?= json_encode($pendidikan_labels) ?>,
        datasets: [{
          label: 'Jumlah Penduduk',
          data: <?= json_encode($pendidikan_data) ?>,
          backgroundColor: <?= json_encode($pendidikan_colors) ?>,
          borderColor: colors.borderColor,
          borderWidth: 1
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
            callbacks: {
              label: function(context) {
                return `Jumlah: ${context.raw.toLocaleString()}`;
              }
            }
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: {
              color: colors.gridColor
            },
            ticks: {
              color: colors.textColor,
              font: {
                family: 'Inter, sans-serif'
              }
            }
          },
          x: {
            grid: {
              display: false
            },
            ticks: {
              color: colors.textColor,
              font: {
                family: 'Inter, sans-serif'
              }
            }
          }
        }
      }
    });
  }

  // Chart Pekerjaan (Bar Chart)
  const pekerjaanCtx = document.getElementById('pekerjaanChart');
  if(pekerjaanCtx) {
    new Chart(pekerjaanCtx.getContext('2d'), {
      type: 'bar',
      data: {
        labels: <?= json_encode($pekerjaan_labels) ?>,
        datasets: [{
          label: 'Jumlah Pekerja',
          data: <?= json_encode($pekerjaan_data) ?>,
          backgroundColor: <?= json_encode($pekerjaan_colors) ?>,
          borderColor: colors.borderColor,
          borderWidth: 1
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
            callbacks: {
              label: function(context) {
                return `Jumlah: ${context.raw.toLocaleString()}`;
              }
            }
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: {
              color: colors.gridColor
            },
            ticks: {
              color: colors.textColor,
              font: {
                family: 'Inter, sans-serif'
              }
            }
          },
          x: {
            grid: {
              display: false
            },
            ticks: {
              color: colors.textColor,
              font: {
                family: 'Inter, sans-serif'
              }
            }
          }
        }
      }
    });
  }
}

// Re-initialize charts on theme change
document.getElementById('themeToggle').addEventListener('click', function() {
  setTimeout(initCharts, 100);
});

// Initialize on load
document.addEventListener('DOMContentLoaded', initCharts);
</script>

<?php require_once 'includes/footer.php'; ?>
