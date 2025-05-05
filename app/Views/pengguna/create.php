<!-- app/Views/pengguna/create.php dan edit.php -->
<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<!-- <div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><?= $title ?></h1>
            </div>
        </div>
    </div>
</div> -->

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <?php if (isset($errors)): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errors as $error): ?>
                            <p><?= $error ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form action="<?= isset($user) ? base_url('pengguna/update/' . $user['id']) : base_url('pengguna/store') ?>" method="post">
                    <div class="form-group">
                        <label for="nama">Nama Lengkap</label>
                        <input type="text" name="nama" id="nama" class="form-control"
                            value="<?= isset($user) ? $user['nama'] : old('nama') ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control"
                            value="<?= isset($user) ? $user['email'] : old('email') ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-control"
                            placeholder="<?= isset($user) ? 'Kosongkan jika tidak ingin mengubah' : '' ?>"
                            <?= !isset($user) ? 'required' : '' ?>>
                    </div>

                    <div class="form-group">
                        <label for="role">Role</label>
                        <select name="role" id="role" class="form-control" required>
                            <option value="">Pilih Role</option>
                            <option value="admin" <?= (isset($user) && $user['role'] == 'admin') ? 'selected' : '' ?>>Admin</option>
                            <option value="peminjam" <?= (isset($user) && $user['role'] == 'peminjam') ? 'selected' : '' ?>>Peminjam</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="<?= base_url('pengguna') ?>" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>