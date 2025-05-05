<!-- app/Views/laporan/index.php -->
<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0"><?= $title ?></h1>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Filter Laporan</h3>
            </div>
            <div class="card-body">
                <form action="<?= base_url('laporan') ?>" method="get">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tanggal Mulai</label>
                                <input type="date" name="start_date" class="form-control"
                                    value="<?= $start_date ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tanggal Selesai</label>
                                <input type="date" name="end_date" class="form-control"
                                    value="<?= $end_date ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
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
                        <div class="col-md-3">
                            <div class="form-group" style="margin-top: 32px">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="<?= base_url('laporan') ?>" class="btn btn-secondary">Reset</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Grafik Peminjaman Bulanan</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="monthlyChart" height="150"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Sarana Paling Sering Dipinjam</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="topSaranaChart" height="150"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Peminjaman</h3>
                <div class="card-tools">
                    <a href="<?= base_url('laporan/exportPDF?start_date=' . $start_date . '&end_date=' . $end_date . '&status=' . $status) ?>"
                        class="btn btn-danger btn-sm">
                        <i class="fas fa-file-pdf"></i> Export PDF
                    </a>
                    <a href="<?= base_url('laporan/exportExcel?start_date=' . $start_date . '&end_date=' . $end_date . '&status=' . $status) ?>"
                        class="btn btn-success btn-sm ml-2">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Peminjam</th>
                            <th>Sarana</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali</th>
                            <th>Jumlah Pinjam</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($peminjaman as $key => $item): ?>
                            <tr>
                                <td><?= $key + 1 ?></td>
                                <td><?= $item['nama_user'] ?></td>
                                <td><?= $item['nama_sarana'] ?></td>
                                <td><?= date('d M Y', strtotime($item['tgl_pinjam'])) ?></td>
                                <td><?= date('d M Y', strtotime($item['tgl_kembali'])) ?></td>
                                <td>
                                    <?= $item['jumlah_pinjam']; ?>
                                </td>
                                <td>
                                    <span class="badge bg-<?php
                                                            switch ($item['status']) {
                                                                case 'disetujui':
                                                                    echo 'success';
                                                                    break;
                                                                case 'pending':
                                                                    echo 'warning';
                                                                    break;
                                                                case 'selesai':
                                                                    echo 'info';
                                                                    break;
                                                                case 'ditolak':
                                                                    echo 'danger';
                                                                    break;
                                                            }
                                                            ?>">
                                        <?= ucfirst($item['status']) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- ChartJS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Grafik Bulanan
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    const monthlyChart = new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: <?= json_encode($chart_data['monthly_labels']) ?>,
            datasets: [{
                label: 'Jumlah Peminjaman',
                data: <?= json_encode($chart_data['monthly_data']) ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Grafik Top Sarana
    const topSaranaCtx = document.getElementById('topSaranaChart').getContext('2d');
    const topSaranaChart = new Chart(topSaranaCtx, {
        type: 'doughnut',
        data: {
            labels: <?= json_encode(array_column($chart_data['top_sarana'], 'nama')) ?>,
            datasets: [{
                data: <?= json_encode(array_column($chart_data['top_sarana'], 'total')) ?>,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(153, 102, 255, 0.7)'
                ]
            }]
        }
    });
</script>
<?= $this->endSection() ?>