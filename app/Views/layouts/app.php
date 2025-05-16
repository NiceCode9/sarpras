<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?? 'Sistem Peminjaman'; ?></title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Select2 -->
    <link rel="stylesheet" href="<?= base_url('writable/') ?>assets/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="<?= base_url('writable/') ?>assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="<?= base_url('writable/') ?>assets/plugins/fontawesome-free/css/all.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="<?= base_url('writable/') ?>assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url('writable/') ?>assets/dist/css/adminlte.min.css">

    <style>
        .fc-event {
            cursor: pointer;
            transition: transform 0.2s;
        }

        .fc-event:hover {
            transform: scale(1.02);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        #calendar {
            background-color: white;
            border-radius: 5px;
            padding: 15px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a href="<?= base_url('logout') ?>" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                    <form id="logout-form" action="<?= base_url('logout') ?>" method="post" style="display: none;">
                        <?= csrf_field(); ?>
                    </form>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="<?= base_url('dashboard') ?>" class="brand-link">
                <img src="<?= base_url('writable/') ?>assets/dist/img/AdminLTELogo.png" alt="Logo" class="brand-image img-circle elevation-3">
                <span class="brand-text font-weight-light">SISPINJAM</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="<?= base_url('writable/') ?>assets/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block"><?= session()->get('nama') ?? 'User'; ?></a>
                        <small class="text-success"><?= ucfirst(session()->get('role') ?? 'Role'); ?></small>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <!-- Dashboard -->
                        <li class="nav-item">
                            <a href="<?= base_url('dashboard') ?>" class="nav-link <?= uri_string() == 'dashboard' ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>

                        <?php if (session()->get('role') == 'admin'): ?>
                            <!-- Menu Admin -->
                            <li class="nav-header">ADMINISTRATOR</li>

                            <li class="nav-item <?= strpos(uri_string(), 'sarana') !== false ? 'menu-open' : '' ?>">
                                <a href="#" class="nav-link <?= strpos(uri_string(), 'sarana') !== false ? 'active' : '' ?>">
                                    <i class="nav-icon fas fa-school"></i>
                                    <p>
                                        Sarana Prasarana
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="<?= base_url('sarana') ?>" class="nav-link <?= uri_string() == 'sarana' ? 'active' : '' ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Daftar Sarana</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?= base_url('sarana/create') ?>" class="nav-link <?= uri_string() == 'sarana/create' ? 'active' : '' ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Tambah Baru</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="nav-item <?= strpos(uri_string(), 'peminjaman') !== false ? 'menu-open' : '' ?>">
                                <a href="#" class="nav-link <?= strpos(uri_string(), 'peminjaman') !== false ? 'active' : '' ?>">
                                    <i class="nav-icon fas fa-clipboard-list"></i>
                                    <p>
                                        Peminjaman
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="<?= base_url('peminjaman/admin') ?>" class="nav-link <?= uri_string() == 'peminjaman/admin' ? 'active' : '' ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Kelola Peminjaman</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="nav-item <?= strpos(uri_string(), 'pemeliharaan') !== false ? 'menu-open' : '' ?>">
                                <a href="#" class="nav-link <?= strpos(uri_string(), 'pemeliharaan') !== false ? 'active' : '' ?>">
                                    <i class="nav-icon fas fa-tools"></i>
                                    <p>
                                        Pemeliharaan
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="<?= base_url('pemeliharaan') ?>" class="nav-link <?= uri_string() == 'pemeliharaan' ? 'active' : '' ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Jadwal</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?= base_url('pemeliharaan/calendar') ?>" class="nav-link <?= uri_string() == 'pemeliharaan/calendar' ? 'active' : '' ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Kalender</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="nav-item <?= strpos(uri_string(), 'pengguna') !== false ? 'menu-open' : '' ?>">
                                <a href="#" class="nav-link <?= strpos(uri_string(), 'pengguna') !== false ? 'active' : '' ?>">
                                    <i class="nav-icon fas fa-users"></i>
                                    <p>
                                        Manajemen Pengguna
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="<?= base_url('pengguna') ?>" class="nav-link <?= uri_string() == 'pengguna' ? 'active' : '' ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Daftar Pengguna</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?= base_url('pengguna/create') ?>" class="nav-link <?= uri_string() == 'pengguna/create' ? 'active' : '' ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Tambah Pengguna</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="nav-item <?= strpos(uri_string(), 'laporan') !== false ? 'menu-open' : '' ?>">
                                <a href="#" class="nav-link <?= strpos(uri_string(), 'laporan') !== false ? 'active' : '' ?>">
                                    <i class="nav-icon fas fa-file-alt"></i>
                                    <p>
                                        Laporan
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="<?= base_url('laporan') ?>" class="nav-link <?= uri_string() == 'laporan' ? 'active' : '' ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Laporan Peminjaman</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('setting') ?>" class="nav-link <?= uri_string() == 'setting' ? 'active' : '' ?>">
                                    <i class="nav-icon fas fa-cog"></i>
                                    <p>Pengaturan Sistem</p>
                                </a>
                            </li>

                        <?php else: ?>
                            <!-- Menu Peminjam -->
                            <li class="nav-header">PEMINJAM</li>

                            <li class="nav-item">
                                <a href="<?= base_url('sarana') ?>" class="nav-link <?= uri_string() == 'sarana' ? 'active' : '' ?>">
                                    <i class="nav-icon fas fa-school"></i>
                                    <p>Daftar Sarana</p>
                                </a>
                            </li>

                            <li class="nav-item <?= strpos(uri_string(), 'peminjaman') !== false ? 'menu-open' : '' ?>">
                                <a href="#" class="nav-link <?= strpos(uri_string(), 'peminjaman') !== false ? 'active' : '' ?>">
                                    <i class="nav-icon fas fa-clipboard-list"></i>
                                    <p>
                                        Peminjaman
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="<?= base_url('peminjaman') ?>" class="nav-link <?= uri_string() == 'peminjaman' ? 'active' : '' ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Ajukan Peminjaman</p>
                                        </a>
                                    </li>
                                    <!-- <li class="nav-item">
                                        <a href="<?= base_url('peminjaman/riwayat') ?>" class="nav-link <?= uri_string() == 'peminjaman/riwayat' ? 'active' : '' ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Riwayat Saya</p>
                                        </a>
                                    </li> -->
                                </ul>
                            </li>
                        <?php endif; ?>

                        <!-- Logout -->
                        <li class="nav-item">
                            <a href="<?= base_url('logout') ?>" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i>
                                <p>Logout</p>
                            </a>
                            <form id="logout-form" action="<?= base_url('logout') ?>" method="post" style="display: none;">
                                <?= csrf_field(); ?>
                            </form>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Content Header -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0"><?= $title ?? 'Dashboard'; ?></h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Home</a></li>
                                <li class="breadcrumb-item active"><?= $title ?? 'Dashboard'; ?></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <?= $this->renderSection('content'); ?>
                </div>
            </div>
        </div>

        <!-- Main Footer -->
        <footer class="main-footer">
            <strong>Copyright &copy; <?= date('Y'); ?> SISPINJAM.</strong>
            All rights reserved.
        </footer>
    </div>

    <!-- jQuery -->
    <script src="<?= base_url('writable/') ?>assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="<?= base_url('writable/') ?>assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?= base_url('writable/') ?>assets/dist/js/adminlte.min.js"></script>
    <!-- Select2 -->
    <script src="<?= base_url('writable/') ?>assets/plugins/select2/js/select2.full.min.js"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="<?= base_url('writable/'); ?>assets/plugins/moment/moment.min.js"></script>
    <script src="<?= base_url('writable/'); ?>assets/plugins/inputmask/jquery.inputmask.min.js"></script>
    <script src="<?= base_url('writable/'); ?>assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>

    <script>
        $('.select2').select2({
            theme: 'bootstrap4'
        })
    </script>

    <?= $this->renderSection('scripts'); ?>
</body>

</html>