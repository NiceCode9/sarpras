<!-- app/Views/peminjaman/index.php -->
<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0"><?= $title ?></h1>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Form Peminjaman</h3>
            </div>
            <form action="<?= base_url('peminjaman/create') ?>" method="post">
                <div class="card-body">
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                    <?php endif; ?>

                    <?php if (isset($errors)): ?>
                        <div class="alert alert-danger">
                            <?php foreach ($errors as $error): ?>
                                <p><?= $error ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <div class="form-group">
                        <label>Sarana</label>
                        <select name="sarana_id" class="form-control" required>
                            <option value="">Pilih Sarana</option>
                            <?php foreach ($saranaTersedia as $sarana): ?>
                                <option value="<?= $sarana['id'] ?>">
                                    <?= $sarana['nama'] ?> (<?= $sarana['kategori'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Tanggal Pinjam</label>
                        <input type="date" name="tgl_pinjam" class="form-control" required
                            min="<?= date('Y-m-d') ?>">
                    </div>

                    <div class="form-group">
                        <label>Tanggal Kembali</label>
                        <input type="date" name="tgl_kembali" class="form-control" required
                            min="<?= date('Y-m-d', strtotime('+1 day')) ?>">
                    </div>

                    <div class="form-group">
                        <label>Alasan Peminjaman</label>
                        <textarea name="alasan" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Ajukan Peminjaman</button>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Riwayat Peminjaman</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Sarana</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($riwayat as $p): ?>
                            <tr>
                                <td><?= $p['nama_sarana'] ?></td>
                                <td>
                                    <?= date('d M Y', strtotime($p['tgl_pinjam'])) ?><br>
                                    s/d <?= date('d M Y', strtotime($p['tgl_kembali'])) ?>
                                </td>
                                <td>
                                    <span class="badge bg-<?=
                                                            $p['status'] == 'disetujui' ? 'success' : ($p['status'] == 'pending' ? 'warning' : 'danger')
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
<?= $this->endSection() ?>