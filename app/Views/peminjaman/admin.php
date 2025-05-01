<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <!-- <h1 class="m-0"><?= $title ?></h1> -->
            </div>
            <div class="col-sm-6">
                <div class="dropdown float-right">
                    <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">
                        Filter: <?= service('request')->getGet('status') ?? 'Semua Status' ?>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="<?= base_url('peminjaman/admin') ?>">Semua</a>
                        <a class="dropdown-item" href="<?= base_url('peminjaman/admin?status=pending') ?>">Pending</a>
                        <a class="dropdown-item" href="<?= base_url('peminjaman/admin?status=disetujui') ?>">Disetujui</a>
                        <a class="dropdown-item" href="<?= base_url('peminjaman/admin?status=ditolak') ?>">Ditolak</a>
                        <a class="dropdown-item" href="<?= base_url('peminjaman/admin?status=selesai') ?>">Selesai</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Peminjam</th>
                            <th>Sarana</th>
                            <th>Tanggal</th>
                            <th>Alasan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($peminjaman as $p): ?>
                            <tr>
                                <td><?= $p['nama_user'] ?></td>
                                <td><?= $p['nama_sarana'] ?></td>
                                <td>
                                    <?= date('d M Y', strtotime($p['tgl_pinjam'])) ?><br>
                                    s/d <?= date('d M Y', strtotime($p['tgl_kembali'])) ?>
                                </td>
                                <td><?= $p['alasan'] ?></td>
                                <td>
                                    <span class="badge bg-<?=
                                                            $p['status'] == 'disetujui' ? 'success' : ($p['status'] == 'pending' ? 'warning' : 'danger')
                                                            ?>">
                                        <?= ucfirst($p['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($p['status'] == 'pending'): ?>
                                        <a href="<?= base_url("peminjaman/action/{$p['id']}/approve") ?>" class="btn btn-sm btn-success">
                                            <i class="fas fa-check"></i> Setujui
                                        </a>
                                        <a href="<?= base_url("peminjaman/action/{$p['id']}/reject") ?>" class="btn btn-sm btn-danger">
                                            <i class="fas fa-times"></i> Tolak
                                        </a>
                                    <?php elseif ($p['status'] == 'disetujui' && $p['tgl_kembali'] >= date('Y-m-d')): ?>
                                        <a href="<?= base_url("peminjaman/action/{$p['id']}/return") ?>" class="btn btn-sm btn-info">
                                            <i class="fas fa-undo"></i> Tandai Kembali
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>