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
                    <!-- <a href="<?= base_url('sarana') ?>" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a> -->
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
                    <!-- <a href="<?= base_url('sarana?status=rusak') ?>" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a> -->
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
                    <!-- <a href="<?= base_url('peminjaman') ?>" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a> -->
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
                    <!-- <a href="<?= base_url('pengguna') ?>" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a> -->
                </div>
            </div>
        </div>

        <!-- Grafik Peminjaman -->
        <!-- Di bagian grafik dashboard -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Statistik Peminjaman 6 Bulan Terakhir</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="peminjamanChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Perbandingan Bulanan</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="info-box bg-info">
                                    <span class="info-box-icon"><i class="far fa-calendar-alt"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Bulan Ini</span>
                                        <span class="info-box-number"><?= $current_month_peminjaman ?></span>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: <?=
                                                                                    ($previous_month_peminjaman > 0) ?
                                                                                        ($current_month_peminjaman / $previous_month_peminjaman) * 100 : 100
                                                                                    ?>%"></div>
                                        </div>
                                        <span class="progress-description">
                                            <?=
                                            ($previous_month_peminjaman > 0) ?
                                                round(($current_month_peminjaman / $previous_month_peminjaman) * 100, 2) : 100
                                            ?>% dari bulan lalu
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="info-box bg-success">
                                    <span class="info-box-icon"><i class="far fa-calendar"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Bulan Lalu</span>
                                        <span class="info-box-number"><?= $previous_month_peminjaman ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Bulan'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah'
                        }
                    }
                }
            }
        });
    });
</script>
<?= $this->endSection() ?>