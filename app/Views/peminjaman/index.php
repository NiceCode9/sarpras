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

                    <?php if (session()->getFlashdata('errors')): ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                    <li><?= $error; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <div class="form-group">
                        <label>Sarana</label>
                        <select name="sarana_id" class="form-control select2" required>
                            <option value="">Pilih Sarana</option>
                            <?php foreach ($saranaTersedia as $sarana): ?>
                                <option value="<?= $sarana['id'] ?>">
                                    <?= $sarana['nama'] ?> (<?= $sarana['kategori'] ?>) - <?= $sarana['jumlah'] ?> Tersedia
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Tanggal Pinjam:</label>
                        <div class="input-group date" id="tgl_pinjam" data-target-input="nearest">
                            <input type="text" name="tgl_pinjam" class="form-control datetimepicker-input" data-target="#tgl_pinjam" min="<?= date('Y-m-d H:i:s') ?>" />
                            <div class="input-group-append" data-target="#tgl_pinjam" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Tanggal Kembali:</label>
                        <div class="input-group date" id="tgl_kembali" data-target-input="nearest">
                            <input type="text" name="tgl_kembali" class="form-control datetimepicker-input" data-target="#tgl_kembali" min="<?= date('Y-m-d H:i:s') ?>" />
                            <div class="input-group-append" data-target="#tgl_kembali" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="jumlah">Jumlah</label>
                        <input type="number" name="jumlah_pinjam" class="form-control" required
                            min="1">
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
                            <th>Jumlah</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($riwayat as $p): ?>
                            <tr>
                                <td><?= $p['nama_sarana'] ?></td>
                                <td>
                                    <?= date('d M Y H:i:s', strtotime($p['tgl_pinjam'])) ?><br>
                                    s/d <?= date('d M Y H:i:s', strtotime($p['tgl_kembali'])) ?>
                                </td>
                                <td><?= $p['jumlah_pinjam']; ?></td>
                                <td>
                                    <span class="badge bg-<?php
                                                            switch ($p['status']) {
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
<?= $this->endSection('content') ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        $('#tgl_pinjam, #tgl_kembali').datetimepicker({
            icons: {
                time: 'far fa-clock'
            },
            locale: 'ru'
        });
    });
</script>
<?= $this->endSection('scripts'); ?>