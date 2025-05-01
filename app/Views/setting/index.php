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
            <div class="card-body">
                <form action="<?= base_url('setting/updateDenda') ?>" method="post">
                    <div class="form-group">
                        <label>Denda Per Hari (Rp)</label>
                        <input type="number" name="denda_per_hari" class="form-control"
                            value="<?= $denda ?>" required>
                        <small class="text-muted">Besaran denda per hari keterlambatan</small>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>