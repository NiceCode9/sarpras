<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0">Dashboard <?= $role == 'admin' ? 'Admin' : 'Peminjam' ?></h1>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Widget Utama -->
        <div class="row">
            <?php if ($role == 'admin'): ?>
                <!-- Widget Admin -->
                <div class="col-lg-3 col-6">
                    <a href="<?= base_url('sarana') ?>" class="small-box-link">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3><?= $total_sarana ?></h3>
                                <p>Total Sarana</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-school"></i>
                            </div>
                            <div class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></div>
                        </div>
                    </a>
                </div>

                <div class="col-lg-3 col-6">
                    <a href="<?= base_url('sarana?status=rusak') ?>" class="small-box-link">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3><?= $sarana_rusak ?></h3>
                                <p>Sarana Rusak</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-times-circle"></i>
                            </div>
                            <div class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></div>
                        </div>
                    </a>
                </div>
            <?php endif; ?>

            <!-- Widget untuk Semua Role -->
            <div class="col-lg-3 col-6">
                <a href="<?= $role == 'admin' ? base_url('peminjaman/admin') : base_url('peminjaman') ?>" class="small-box-link">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3><?= $role == 'admin' ? $total_peminjaman : $total_peminjaman_saya ?></h3>
                            <p><?= $role == 'admin' ? 'Total Peminjaman' : 'Peminjaman Saya' ?></p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <div class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></div>
                    </div>
                </a>
            </div>

            <?php if ($role == 'peminjam'): ?>
                <!-- Widget Khusus Peminjam -->
                <div class="col-lg-3 col-6">
                    <a href="<?= base_url('peminjaman?status=disetujui') ?>" class="small-box-link">
                        <div class="small-box bg-primary">
                            <div class="inner">
                                <h3><?= $peminjaman_aktif ?></h3>
                                <p>Peminjaman Aktif</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></div>
                        </div>
                    </a>
                </div>
            <?php else: ?>
                <!-- Widget Khusus Admin -->
                <div class="col-lg-3 col-6">
                    <a href="<?= base_url('pengguna') ?>" class="small-box-link">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3><?= $total_users ?></h3>
                                <p>Pengguna Terdaftar</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></div>
                        </div>
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Grafik dan Tabel -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Statistik Peminjaman 6 Bulan Terakhir</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="peminjamanChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><?= $role == 'admin' ? 'Peminjaman Terbaru' : 'Aktivitas Terakhir' ?></h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <?php if ($role == 'admin'): ?>
                                        <th>Peminjam</th>
                                    <?php endif; ?>
                                    <th>Sarana</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($peminjaman_terbaru as $p): ?>
                                    <tr onclick="window.location='<?= $role == 'admin' ? base_url('peminjaman/admin') : base_url('peminjaman') ?>'" style="cursor:pointer">
                                        <?php if ($role == 'admin'): ?>
                                            <td><?= $p['nama_user'] ?></td>
                                        <?php endif; ?>
                                        <td><?= $p['nama_sarana'] ?></td>
                                        <td><?= date('d M Y', strtotime($p['tgl_pinjam'])) ?></td>
                                        <td>
                                            <span class="badge bg-<?=
                                                                    $p['status'] == 'disetujui' ? 'info' : ($p['status'] == 'pending' ? 'warning' : ($p['status'] == 'dibatalkan' ? 'danger' : 'success'))
                                                                    ?>">
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
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('peminjamanChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($chart_data['labels']) ?>,
                datasets: [{
                    label: 'Jumlah Peminjaman',
                    data: <?= json_encode($chart_data['data']) ?>,
                    backgroundColor: 'rgba(60,141,188,0.9)',
                    borderColor: 'rgba(60,141,188,0.8)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            afterLabel: function(context) {
                                return 'Klik untuk lihat laporan';
                            }
                        }
                    }
                },
                onClick: function(evt, elements) {
                    if (elements.length > 0) {
                        const index = elements[0].index;
                        const month = this.data.labels[index].split(' ')[0];
                        const year = this.data.labels[index].split(' ')[1];
                        const startDate = `01-${month}-${year}`;
                        const endDate = new Date(year, new Date(`${month} 1, 2012`).getMonth() + 1, 0).getDate();

                        window.location.href = `<?= base_url('laporan') ?>?start_date=${startDate}&end_date=${endDate}-${month}-${year}`;
                    }
                }
            }
        });
    });
</script>

<style>
    .small-box-link {
        display: block;
        color: inherit;
        text-decoration: none;
    }

    .small-box-link:hover {
        color: inherit;
        text-decoration: none;
    }

    .small-box-link:hover .small-box {
        transform: translateY(-5px);
        transition: transform 0.3s ease;
    }

    .info-box:hover {
        transform: translateY(-3px);
        transition: transform 0.3s ease;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
</style>
<?= $this->endSection() ?>