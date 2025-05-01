<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><?= $title ?></h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <?php if (isset($errors)) : ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errors as $error) : ?>
                            <p><?= $error ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form action="<?= isset($sarana) ? base_url('sarana/update/' . $sarana['id']) : base_url('sarana/store') ?>" method="post">
                    <div class="form-group">
                        <label for="nama">Nama Sarana</label>
                        <input type="text" name="nama" id="nama" class="form-control"
                            value="<?= isset($sarana) ? $sarana['nama'] : old('nama') ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="kategori">Kategori</label>
                        <select name="kategori" id="kategori" class="form-control" required>
                            <option value="">Pilih Kategori</option>
                            <option value="alat" <?= (isset($sarana) && $sarana['kategori'] == 'alat') ? 'selected' : '' ?>>Alat</option>
                            <option value="ruang" <?= (isset($sarana) && $sarana['kategori'] == 'ruang') ? 'selected' : '' ?>>Ruang</option>
                            <option value="buku" <?= (isset($sarana) && $sarana['kategori'] == 'buku') ? 'selected' : '' ?>>Buku</option>
                            <option value="elektronik" <?= (isset($sarana) && $sarana['kategori'] == 'elektronik') ? 'selected' : '' ?>>Elektronik</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="lokasi">Lokasi</label>
                        <input type="text" name="lokasi" id="lokasi" class="form-control"
                            value="<?= isset($sarana) ? $sarana['lokasi'] : old('lokasi') ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control" required>
                            <option value="">Pilih Status</option>
                            <option value="tersedia" <?= (isset($sarana) && $sarana['status'] == 'tersedia') ? 'selected' : '' ?>>Tersedia</option>
                            <option value="dipinjam" <?= (isset($sarana) && $sarana['status'] == 'dipinjam') ? 'selected' : '' ?>>Dipinjam</option>
                            <option value="rusak" <?= (isset($sarana) && $sarana['status'] == 'rusak') ? 'selected' : '' ?>>Rusak</option>
                            <option value="pemeliharaan" <?= (isset($sarana) && $sarana['status'] == 'pemeliharaan') ? 'selected' : '' ?>>Pemeliharaan</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3"><?= isset($sarana) ? $sarana['deskripsi'] : old('deskripsi') ?></textarea>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="<?= base_url('sarana') ?>" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>