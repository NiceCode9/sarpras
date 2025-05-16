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
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-nowrap">
                        <thead>
                            <tr>
                                <th>Peminjam</th>
                                <th>Sarana</th>
                                <th>Tanggal</th>
                                <th>Jumlah Pinjam</th>
                                <th>Tanggal Dikembalikan</th>
                                <th>Alasan</th>
                                <th>Status</th>
                                <th>Denda</th>
                                <th>Catatan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($peminjaman as $p): ?>
                                <tr>
                                    <td><?= $p['nama_user'] ?></td>
                                    <td><?= $p['nama_sarana'] ?></td>
                                    <td>
                                        <?= date('d M Y H:i:s', strtotime($p['tgl_pinjam'])) ?><br>
                                        s/d <?= date('d M Y H:i:s', strtotime($p['tgl_kembali'])) ?>
                                    </td>
                                    <td><?= $p['jumlah_pinjam']; ?></td>
                                    <td><?= $p['tgl_dikembalikan'] ? date('d M Y H:i:s', strtotime($p['tgl_dikembalikan'])) : '-' ?></td>
                                    <td><?= $p['alasan'] ?></td>
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
                                    <?php
                                    $denda = 0;
                                    $keterangan = null;
                                    $tglKembali = new DateTime($p['tgl_kembali']);
                                    $now = new DateTime();
                                    if ($tglKembali < $now) {
                                        $selisihHari = $now->diff($tglKembali)->days;
                                        $denda = $selisihHari * ($setting['denda_per_hari'] ?? 5000);
                                        $keterangan = "Terlambat $selisihHari hari (Rp" . number_format($setting['denda_per_hari'] ?? 5000) . "/hari)";
                                    }
                                    ?>
                                    <td class="<?= $denda > 0 ? 'text-danger font-weight-bold' : '' ?>">
                                        <?php if ($p['status'] == 'disetujui'): ?>
                                            <?= $denda > 0 ? 'Rp' . number_format($denda) : '-' ?>
                                            <?php if ($keterangan): ?>
                                                <small class="d-block text-muted"><?= $keterangan ?></small>
                                            <?php endif; ?>
                                        <?php elseif ($p['status'] == 'selesai'): ?>
                                            <?php
                                            $tglDikembalikan = new DateTime($p['tgl_dikembalikan']);
                                            $selisihHari = $tglDikembalikan->diff($tglKembali)->days;
                                            $keterangan = "Terlambat $selisihHari hari (Rp" . number_format($setting['denda_per_hari'] ?? 5000) . "/hari)";
                                            ?>
                                            <?= $p['denda'] > 0 ? 'Rp' . number_format($p['denda']) : '-' ?>
                                            <small class="d-block text-muted"><?= $keterangan ?></small>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $p['catatan'] ?? '-'; ?></td>
                                    <td>
                                        <?php if ($p['status'] == 'pending'): ?>
                                            <a href="<?= base_url("peminjaman/action/{$p['id']}/approve") ?>" class="btn btn-sm btn-success">
                                                <i class="fas fa-check"></i> Setujui
                                            </a>
                                            <a href="<?= base_url("peminjaman/action/{$p['id']}/reject") ?>" class="btn btn-sm btn-danger">
                                                <i class="fas fa-times"></i> Tolak
                                            </a>
                                        <?php elseif ($p['status'] == 'disetujui' && $p['tgl_kembali'] >= date('Y-m-d')): ?>
                                            <!-- <a href="<?= base_url("peminjaman/action/{$p['id']}/return") ?>" class="btn btn-sm btn-info">
                                            <i class="fas fa-undo"></i> Tandai Kembali
                                        </a> -->
                                            <a href="javascript:void(0)" data-url="<?= base_url("peminjaman/return/{$p['id']}") ?>" class=" btn btn-sm btn-info btn-return" data-toggle="modal" data-target="#staticBackdrop">
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
    </div>

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="post" id="form-return">
                    <?= csrf_field(); ?>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="catatan">Catatan</label>
                            <textarea name="catatan" id="catatan" class="form-control" rows="3" placeholder="Masukkan catatan jika ada"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


</section>

<?= $this->endSection() ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        // $('#staticBackdrop').on('show.bs.modal', function(event) {
        //     // var url = $('selector').data('url');
        //     // var form = $('#form-return');
        //     // form.attr('action', url);
        //     // form.find('#staticBackdropLabel').text('Tandai Kembali Peminjaman');
        //     // form.find('#catatan').val('');
        //     // form.off('submit').on('submit', function(e) {
        //     //     e.preventDefault();
        //     //     $.ajax({
        //     //         url: form.attr('action'),
        //     //         type: 'POST',
        //     //         data: form.serialize(),
        //     //         success: function(response) {
        //     //             if (response.success) {
        //     //                 location.reload();
        //     //             } else {
        //     //                 alert(response.error);
        //     //             }
        //     //         },
        //     //         error: function() {
        //     //             alert('Terjadi kesalahan. Silakan coba lagi.');
        //     //         }
        //     //     });
        //     // });
        // });
        $(document).on('click', '.btn-return', function() {
            var url = $(this).data('url');
            var form = $('#form-return');
            form.attr('action', url);
            form.find('#staticBackdropLabel').text('Tandai Kembali Peminjaman');
            form.find('#catatan').val('');
        });
    });
</script>
<?= $this->endSection(); ?>