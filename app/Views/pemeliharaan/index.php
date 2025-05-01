<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><?= $title ?></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Pemeliharaan</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Tambah Jadwal</h3>
                    </div>
                    <form action="<?= base_url('pemeliharaan/store') ?>" method="post">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Sarana</label>
                                <select name="sarana_id" class="form-control" required>
                                    <option value="">Pilih Sarana</option>
                                    <?php foreach ($sarana as $item): ?>
                                        <option value="<?= $item['id'] ?>">
                                            <?= $item['nama'] ?> (<?= $item['kategori'] ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Tanggal Mulai</label>
                                <input type="date" name="tgl_mulai" class="form-control" required
                                    min="<?= date('Y-m-d') ?>">
                            </div>

                            <div class="form-group">
                                <label>Tanggal Selesai</label>
                                <input type="date" name="tgl_selesai" class="form-control" required
                                    min="<?= date('Y-m-d', strtotime('+1 day')) ?>">
                            </div>

                            <div class="form-group">
                                <label>Keterangan</label>
                                <textarea name="keterangan" class="form-control" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Daftar Jadwal Pemeliharaan</h3>
                        <div class="card-tools">
                            <a href="<?= base_url('pemeliharaan/calendar') ?>" class="btn btn-sm btn-info">
                                <i class="fas fa-calendar-alt"></i> Kalender
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Sarana</th>
                                    <th>Tanggal</th>
                                    <th>Keterangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pemeliharaan as $item): ?>
                                    <tr>
                                        <td><?= $item['nama_sarana'] ?> (<?= $item['kategori'] ?>)</td>
                                        <td>
                                            <?= date('d M Y', strtotime($item['tgl_mulai'])) ?><br>
                                            s/d <?= date('d M Y', strtotime($item['tgl_selesai'])) ?>
                                        </td>
                                        <td><?= $item['keterangan'] ?></td>
                                        <td>
                                            <button onclick="confirmDelete('<?= $item['id'] ?>')"
                                                class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
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

<script>
    function confirmDelete(id) {
        if (confirm('Apakah Anda yakin ingin menghapus jadwal ini?')) {
            window.location.href = "<?= base_url('pemeliharaan/delete') ?>/" + id;
        }
    }
</script>
<?= $this->endSection() ?>