<!-- app/Views/laporan/index.php -->
<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<style>
    /* Ranking List Style */
    .ranking-list {
        padding: 0;
        list-style: none;
    }

    .ranking-item {
        display: flex;
        align-items: center;
        padding: 12px;
        margin-bottom: 10px;
        border-radius: 8px;
        background-color: #f8f9fa;
        transition: all 0.3s ease;
    }

    .ranking-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .ranking-item.gold {
        background: linear-gradient(135deg, rgba(255, 215, 0, 0.1) 0%, rgba(255, 255, 255, 1) 100%);
        border-left: 4px solid #FFD700;
    }

    .ranking-item.silver {
        background: linear-gradient(135deg, rgba(192, 192, 192, 0.1) 0%, rgba(255, 255, 255, 1) 100%);
        border-left: 4px solid #C0C0C0;
    }

    .ranking-item.bronze {
        background: linear-gradient(135deg, rgba(205, 127, 50, 0.1) 0%, rgba(255, 255, 255, 1) 100%);
        border-left: 4px solid #CD7F32;
    }

    .rank {
        font-size: 24px;
        font-weight: bold;
        color: #6c757d;
        margin-right: 15px;
        min-width: 30px;
        text-align: center;
    }

    .gold .rank {
        color: #FFD700;
    }

    .silver .rank {
        color: #C0C0C0;
    }

    .bronze .rank {
        color: #CD7F32;
    }

    /* User List Style */
    .user-list {
        padding: 0;
        list-style: none;
    }

    .user-item {
        display: flex;
        align-items: center;
        padding: 12px;
        margin-bottom: 10px;
        border-radius: 8px;
        background-color: #f8f9fa;
        transition: all 0.3s ease;
    }

    .user-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        margin-right: 15px;
        font-size: 18px;
    }

    .user-details {
        flex: 1;
    }

    /* Chart Container */
    .chart-container {
        position: relative;
        margin: auto;
        height: 250px;
    }
</style>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><?= $title ?></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Laporan</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-filter mr-1"></i>
                    Filter Laporan
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <form action="<?= base_url('laporan') ?>" method="get">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tanggal Mulai</label>
                                <input type="date" name="start_date" class="form-control" value="<?= $start_date ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tanggal Selesai</label>
                                <input type="date" name="end_date" class="form-control" value="<?= $end_date ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="">Semua Status</option>
                                    <option value="pending" <?= $status == 'pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="disetujui" <?= $status == 'disetujui' ? 'selected' : '' ?>>Disetujui</option>
                                    <option value="ditolak" <?= $status == 'ditolak' ? 'selected' : '' ?>>Ditolak</option>
                                    <option value="selesai" <?= $status == 'selesai' ? 'selected' : '' ?>>Selesai</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Kategori Sarana</label>
                                <select name="kategori" class="form-control">
                                    <option value="">Semua Kategori</option>
                                    <?php foreach ($kategori_list as $kategori): ?>
                                        <option value="<?= $kategori ?>" <?= $selected_kategori == $kategori ? 'selected' : '' ?>>
                                            <?= ucfirst($kategori) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group" style="margin-top: 32px">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                                <a href="<?= base_url('laporan') ?>" class="btn btn-secondary">
                                    <i class="fas fa-sync-alt"></i> Reset
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-line mr-1"></i>
                            Grafik Peminjaman Bulanan
                        </h3>
                        <div class="card-tools">
                            <select id="chartYear" class="form-control form-control-sm" style="width: 100px">
                                <?php for ($i = date('Y'); $i >= date('Y') - 5; $i--): ?>
                                    <option value="<?= $i ?>"><?= $i ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="monthlyChart" height="150"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-pie mr-1"></i>
                            Statistik Peminjaman
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-box bg-info">
                                    <span class="info-box-icon"><i class="fas fa-calendar-check"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Peminjaman</span>
                                        <span class="info-box-number"><?= $total_peminjaman ?? 0 ?></span>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: 100%"></div>
                                        </div>
                                        <span class="progress-description">
                                            Periode <?= date('d M Y', strtotime($start_date)) ?> - <?= date('d M Y', strtotime($end_date)) ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box bg-success">
                                    <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Disetujui</span>
                                        <span class="info-box-number"><?= $status_counts['disetujui'] ?? 0 ?></span>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: <?= $total_peminjaman ? (($status_counts['disetujui'] ?? 0) / $total_peminjaman * 100) : 0 ?>%"></div>
                                        </div>
                                        <span class="progress-description">
                                            <?= $total_peminjaman ? round(($status_counts['disetujui'] ?? 0) / $total_peminjaman * 100, 2) : 0 ?>% dari total
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box bg-warning">
                                    <span class="info-box-icon"><i class="fas fa-clock"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Pending</span>
                                        <span class="info-box-number"><?= $status_counts['pending'] ?? 0 ?></span>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: <?= $total_peminjaman ? ($status_counts['pending'] / $total_peminjaman * 100) : 0 ?>%"></div>
                                        </div>
                                        <span class="progress-description">
                                            <?= $total_peminjaman ? round($status_counts['pending'] / $total_peminjaman * 100, 2) : 0 ?>% dari total
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box bg-danger">
                                    <span class="info-box-icon"><i class="fas fa-times-circle"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Ditolak</span>
                                        <span class="info-box-number"><?= $status_counts['ditolak'] ?? 0 ?></span>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: <?= $total_peminjaman ? ($status_counts['ditolak'] / $total_peminjaman * 100) : 0 ?>%"></div>
                                        </div>
                                        <span class="progress-description">
                                            <?= $total_peminjaman ? round($status_counts['ditolak'] / $total_peminjaman * 100, 2) : 0 ?>% dari total
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Sarana Paling Sering Dipinjam -->
            <div class="col-lg-6">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="card-title mb-0">
                                <i class="fas fa-trophy mr-2"></i>
                                TOP 5 SARANA PALING DIPINJAM
                            </h3>
                            <span class="badge badge-light"><?= date('F Y') ?></span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <canvas id="topSaranaChart" height="250"></canvas>
                            </div>
                            <div class="col-md-6">
                                <div class="ranking-list">
                                    <?php foreach ($chart_data['top_sarana'] as $index => $sarana):
                                        $percentage = $sarana['total'] / array_sum(array_column($chart_data['top_sarana'], 'total')) * 100;
                                    ?>
                                        <div class="ranking-item <?= $index === 0 ? 'gold' : ($index === 1 ? 'silver' : ($index === 2 ? 'bronze' : '')) ?>">
                                            <div class="rank"><?= $index + 1 ?></div>
                                            <div class="info">
                                                <h5 class="mb-1"><?= $sarana['nama'] ?></h5>
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-muted"><?= $sarana['total'] ?>x dipinjam</span>
                                                    <strong><?= number_format($percentage, 1) ?>%</strong>
                                                </div>
                                                <div class="progress mt-1" style="height: 6px;">
                                                    <div class="progress-bar bg-gradient-<?= $index === 0 ? 'warning' : ($index === 1 ? 'secondary' : ($index === 2 ? 'danger' : 'primary')) ?>"
                                                        style="width: <?= $percentage ?>%"
                                                        role="progressbar"></div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Peminjam Teraktif -->
            <div class="col-lg-6">
                <div class="card shadow-lg">
                    <div class="card-header bg-success text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="card-title mb-0">
                                <i class="fas fa-users mr-2"></i>
                                TOP 5 PEMINJAM TERAKTIF
                            </h3>
                            <span class="badge badge-light"><?= date('F Y') ?></span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <canvas id="topUsersChart" height="250"></canvas>
                            </div>
                            <div class="col-md-6">
                                <div class="user-list">
                                    <?php foreach ($chart_data['top_users'] as $index => $user): ?>
                                        <div class="user-item">
                                            <div class="avatar bg-<?= ['info', 'success', 'warning', 'danger', 'primary'][$index % 5] ?>">
                                                <?= substr($user['nama'], 0, 1) ?>
                                            </div>
                                            <div class="user-details">
                                                <h5 class="mb-1"><?= $user['nama'] ?></h5>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="text-muted"><?= $user['total'] ?>x meminjam</span>
                                                    <span class="badge bg-<?= ['info', 'success', 'warning', 'danger', 'primary'][$index % 5] ?>">
                                                        Level <?= min(5, ceil($user['total'] / 3)) ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-table mr-1"></i>
                Data Peminjaman
            </h3>
            <div class="card-tools">
                <div class="btn-group">
                    <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                        <i class="fas fa-download"></i> Export
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" role="menu">
                        <a href="<?= base_url('laporan/exportPDF?start_date=' . $start_date . '&end_date=' . $end_date . '&status=' . $status . '&kategori=' . $selected_kategori) ?>"
                            class="dropdown-item">
                            <i class="fas fa-file-pdf text-danger mr-2"></i> PDF
                        </a>
                        <a href="<?= base_url('laporan/exportExcel?start_date=' . $start_date . '&end_date=' . $end_date . '&status=' . $status . '&kategori=' . $selected_kategori) ?>"
                            class="dropdown-item">
                            <i class="fas fa-file-excel text-success mr-2"></i> Excel
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="<?= base_url('laporan/exportPrint?start_date=' . $start_date . '&end_date=' . $end_date . '&status=' . $status . '&kategori=' . $selected_kategori) ?>"
                            class="dropdown-item" target="_blank">
                            <i class="fas fa-print text-primary mr-2"></i> Print
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="dataTable">
                    <thead class="thead-light">
                        <tr>
                            <th>No</th>
                            <th>Peminjam</th>
                            <th>Sarana (Kategori)</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Denda</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($peminjaman as $key => $item): ?>
                            <tr>
                                <td><?= $key + 1 ?></td>
                                <td><?= $item['nama_user'] ?></td>
                                <td>
                                    <?= $item['nama_sarana'] ?>
                                    <small class="text-muted d-block"><?= $item['kategori_sarana'] ?></small>
                                </td>
                                <td><?= date('d M Y', strtotime($item['tgl_pinjam'])) ?></td>
                                <td>
                                    <?= date('d M Y', strtotime($item['tgl_kembali'])) ?>
                                    <?php if ($item['status'] == 'disetujui' && strtotime($item['tgl_kembali']) < time()): ?>
                                        <span class="badge badge-danger">Terlambat</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= $item['jumlah_pinjam'] ?></td>
                                <td>
                                    <?php
                                    $statusColors = [
                                        'pending' => 'warning',
                                        'disetujui' => 'success',
                                        'ditolak' => 'danger',
                                        'selesai' => 'primary'
                                    ];
                                    $color = $statusColors[$item['status']] ?? 'secondary';
                                    ?>
                                    <span class="badge bg-<?= $color ?>">
                                        <?= ucfirst($item['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($item['denda'] > 0): ?>
                                        <span class="text-danger">Rp <?= number_format($item['denda'], 0, ',', '.') ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer clearfix">
            <div class="float-right">
                <?= $pager->links('default', 'bootstrap_pagination') ?>
            </div>
            <div class="float-left">
                <span class="text-muted">
                    Menampilkan <?= count($peminjaman) ?> dari <?= $total_peminjaman ?> data
                </span>
            </div>
        </div>
    </div>
    </div>
</section>

<!-- ChartJS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<script>
    // Register plugin
    Chart.register(ChartDataLabels);
    // Grafik Bulanan
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    const monthlyChart = new Chart(monthlyCtx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($chart_data['monthly_labels']) ?>,
            datasets: [{
                label: 'Jumlah Peminjaman',
                data: <?= json_encode($chart_data['monthly_data']) ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });


    // Top Sarana Chart
    const saranaCtx = document.getElementById('topSaranaChart').getContext('2d');
    const saranaChart = new Chart(saranaCtx, {
        type: 'doughnut',
        data: {
            labels: <?= json_encode(array_column($chart_data['top_sarana'], 'nama')) ?>,
            datasets: [{
                data: <?= json_encode(array_column($chart_data['top_sarana'], 'total')) ?>,
                backgroundColor: [
                    'rgba(255, 215, 0, 0.7)', // Gold
                    'rgba(192, 192, 192, 0.7)', // Silver
                    'rgba(205, 127, 50, 0.7)', // Bronze
                    'rgba(100, 149, 237, 0.7)', // CornflowerBlue
                    'rgba(144, 238, 144, 0.7)' // LightGreen
                ],
                borderColor: [
                    'rgba(255, 215, 0, 1)',
                    'rgba(192, 192, 192, 1)',
                    'rgba(205, 127, 50, 1)',
                    'rgba(100, 149, 237, 1)',
                    'rgba(144, 238, 144, 1)'
                ],
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
                        font: {
                            size: 12,
                            family: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif"
                        },
                        padding: 20
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const value = context.raw;
                            const percentage = Math.round((value / total) * 100);
                            return `${context.label}: ${value}x (${percentage}%)`;
                        }
                    }
                },
                datalabels: {
                    color: '#fff',
                    font: {
                        weight: 'bold',
                        size: 14
                    },
                    formatter: (value, context) => {
                        return value > 0 ? value + 'x' : '';
                    }
                }
            },
            cutout: '70%',
            animation: {
                animateScale: true,
                animateRotate: true
            }
        }
    });

    // Top Users Chart
    const usersCtx = document.getElementById('topUsersChart').getContext('2d');
    const usersChart = new Chart(usersCtx, {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_column($chart_data['top_users'], 'nama')) ?>,
            datasets: [{
                label: 'Jumlah Peminjaman',
                data: <?= json_encode(array_column($chart_data['top_users'], 'total')) ?>,
                backgroundColor: [
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(153, 102, 255, 0.7)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(153, 102, 255, 1)'
                ],
                borderWidth: 1,
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y',
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `${context.raw}x meminjam`;
                        }
                    }
                },
                datalabels: {
                    anchor: 'end',
                    align: 'end',
                    color: '#343a40',
                    font: {
                        weight: 'bold'
                    },
                    formatter: (value) => {
                        return value > 0 ? value + 'x' : '';
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    grid: {
                        display: false
                    },
                    ticks: {
                        stepSize: 1
                    }
                },
                y: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
    // Filter tahun untuk grafik bulanan
    document.getElementById('chartYear').addEventListener('change', function() {
        const year = this.value;
        window.location.href = `<?= base_url('laporan') ?>?year=${year}`;
    });
</script>

<?= $this->endSection() ?>