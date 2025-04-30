!-- app/Views/dashboard.php -->
<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0">Dashboard</h1>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Small Boxes -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3><?= $total_sarana ?></h3>
                        <p>Total Sarana</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-school"></i>
                    </div>
                    <a href="<?= base_url('sarana') ?>" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3><?= $sarana_rusak ?></h3>
                        <p>Sarana Rusak</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <a href="<?= base_url('sarana?status=rusak') ?>" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3><?= $total_peminjaman ?></h3>
                        <p>Total Peminjaman</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <a href="<?= base_url('peminjaman') ?>" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3><?= $total_users ?></h3>
                        <p>Pengguna Terdaftar</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <a href="<?= base_url('pengguna') ?>" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>

        <!-- Grafik Peminjaman -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Statistik Peminjaman Bulan Ini</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="peminjamanChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Peminjaman Terbaru</h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nama Sarana</th>
                                    <th>Peminjam</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($peminjaman_terbaru as $p) : ?>
                                    <tr>
                                        <td><?= $p['nama_sarana'] ?></td>
                                        <td><?= $p['nama_user'] ?></td>
                                        <td><?= date('d M Y', strtotime($p['tgl_pinjam'])) ?></td>
                                        <td>
                                            <span class="badge bg-<?= $p['status'] == 'disetujui' ? 'success' : ($p['status'] == 'pending' ? 'warning' : 'danger') ?>">
                                                <?= ucfirst($p['status']) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ChartJS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('peminjamanChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($chart_labels) ?>,
            datasets: [{
                label: 'Jumlah Peminjaman',
                data: <?= json_encode($chart_data) ?>,
                backgroundColor: 'rgba(60,141,188,0.9)',
                borderColor: 'rgba(60,141,188,0.8)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
<?= $this->endSection() ?>