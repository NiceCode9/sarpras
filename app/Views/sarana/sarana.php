<!-- app/Views/sarana/index.php -->
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
                    <li class="breadcrumb-item active">Sarana</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Sarana Prasarana</h3>
                <div class="card-tools">
                    <?php if (session()->get('role') == 'admin'): ?>
                        <a href="<?= base_url('sarana/create') ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Tambah Sarana
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <form action="<?= base_url('sarana') ?>" method="get">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Cari sarana..." value="<?= $searchKeyword ?>">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <div class="dropdown float-right">
                            <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">
                                Filter Status: <?= $statusFilter ? ucfirst($statusFilter) : 'Semua' ?>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="<?= base_url('sarana') ?>">Semua</a>
                                <a class="dropdown-item" href="<?= base_url('sarana?status=baik') ?>">Baik</a>
                                <a class="dropdown-item" href="<?= base_url('sarana?status=rusak') ?>">Rusak</a>
                                <a class="dropdown-item" href="<?= base_url('sarana?status=pemeliharaan') ?>">Pemeliharaan</a>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if (session()->getFlashdata('success')) : ?>
                    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                <?php endif; ?>

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Sarana</th>
                            <th>Kategori</th>
                            <th>Lokasi</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $currentPage = $pager->getCurrentPage('sarana');
                        $perPage = $pager->getPerPage('sarana');
                        $startNumber = ($currentPage - 1) * $perPage + 1;
                        ?>
                        <?php foreach ($sarana as $key => $item) : ?>
                            <tr>
                                <td><?= $startNumber++ ?></td>
                                <td><?= $item['nama'] ?></td>
                                <td><?= ucfirst($item['kategori']) ?></td>
                                <td><?= $item['lokasi'] ?></td>
                                <td><?= $item['jumlah']; ?></td>
                                <td>
                                    <span class="badge bg-<?php
                                                            switch ($item['status']) {
                                                                case 'baik':
                                                                    echo 'success';
                                                                    break;
                                                                case 'dipinjam':
                                                                    echo 'info';
                                                                    break;
                                                                case 'rusak':
                                                                    echo 'danger';
                                                                    break;
                                                                default:
                                                                    echo 'warning';
                                                                    break;
                                                            }
                                                            ?>">
                                        <?= ucfirst($item['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php
                                    if (session()->get('role') == 'admin') : ?>
                                        <a href="<?= base_url('sarana/edit/' . $item['id']) ?>" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?= $item['id'] ?>)">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    <?php else : ?>
                                        -
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="mt-3">
                    <?= $pager->links('sarana', 'bootstrap_pagination') ?>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function confirmDelete(id) {
        if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
            window.location.href = "<?= base_url('sarana/delete') ?>/" + id;
        }
    }
</script>
<?= $this->endSection() ?>