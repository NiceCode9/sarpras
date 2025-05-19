<!DOCTYPE html>
<html>

<head>
    <title>Laporan Peminjaman</title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            margin: 0;
            padding: 20px;
        }

        /* KOP SURAT PROFESIONAL */
        .kop-surat {
            border-bottom: 3px solid #000;
            padding-bottom: 15px;
            margin-bottom: 20px;
            position: relative;
        }

        .logo {
            position: absolute;
            left: 0;
            top: 0;
        }

        .logo img {
            width: 80px;
            height: auto;
        }

        .kop-text {
            text-align: center;
            margin-left: 90px;
            /* Sesuaikan dengan lebar logo */
            margin-right: 90px;
            /* Untuk balance */
        }

        .kop-text h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .kop-text h2 {
            margin: 5px 0;
            font-size: 16px;
            font-weight: normal;
        }

        .kop-text p {
            margin: 2px 0;
            font-size: 12px;
            line-height: 1.4;
        }

        .header-laporan {
            text-align: center;
            margin: 25px 0 15px 0;
        }

        .header-laporan h2 {
            margin: 0;
            font-size: 16px;
            text-decoration: underline;
            font-weight: bold;
        }

        .header-laporan h3 {
            margin: 5px 0 0 0;
            font-size: 14px;
            font-weight: normal;
        }

        .periode {
            text-align: center;
            margin-bottom: 15px;
            font-size: 13px;
        }

        /* TABEL DATA */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            page-break-inside: auto;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        /* FOOTER */
        .footer {
            margin-top: 20px;
            font-size: 11px;
            text-align: right;
        }

        .ttd {
            margin-top: 50px;
            width: 300px;
            float: right;
            text-align: center;
        }

        .ttd div {
            height: 60px;
            margin-bottom: 5px;
        }

        .ttd p {
            margin: 0;
            padding: 0;
        }

        .ttd .nama {
            font-weight: bold;
            text-decoration: underline;
        }

        .ttd .jabatan {
            font-size: 11px;
        }
    </style>
</head>

<body>
    <!-- KOP SURAT YANG DIPERBAIKI -->
    <div class="kop-surat">
        <div class="logo">
            <img src="data:image/jpg;base64,<?= base64_encode(file_get_contents(WRITEPATH . 'assets/logo.jpg')) ?>" alt="Logo Sekolah">
        </div>
        <div class="kop-text">
            <h1>SEKOLAH MENENGAH PERTAMA NEGERI 1</h1>
            <h2>KOTA BANDUNG</h2>
            <p>Jl. Pendidikan No. 123, Bandung, Jawa Barat 40123</p>
            <p>Telp: (022) 1234567 | Email: smpn1bdg@sch.id | Website: www.smpn1bdg.sch.id</p>
            <p>NPSN: 12345678 | Akreditasi: A</p>
        </div>
    </div>

    <div class="header-laporan">
        <h2>LAPORAN PEMINJAMAN SARANA DAN PRASARANA</h2>
        <h3>Tahun Ajaran 2023/2024</h3>
    </div>

    <div class="periode">
        <strong>PERIODE: </strong>
        <?= date('d F Y', strtotime($start_date)) ?> s/d <?= date('d F Y', strtotime($end_date)) ?>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">Peminjam</th>
                <th width="20%">Sarana</th>
                <th width="15%">Tgl Pinjam</th>
                <th width="15%">Tgl Kembali</th>
                <th width="10%">Jumlah</th>
                <th width="15%">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($peminjaman as $key => $item): ?>
                <tr>
                    <td style="text-align: center;"><?= $key + 1 ?></td>
                    <td><?= $item['nama_user'] ?></td>
                    <td><?= $item['nama_sarana'] ?></td>
                    <td><?= date('d-m-Y', strtotime($item['tgl_pinjam'])) ?></td>
                    <td><?= date('d-m-Y', strtotime($item['tgl_kembali'])) ?></td>
                    <td style="text-align: center;"><?= $item['jumlah_pinjam'] ?></td>
                    <td style="text-align: center;"><?= ucfirst($item['status']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- FOOTER DAN TTD -->
    <div class="footer">
        <p>Dicetak pada: <?= date('d F Y H:i:s') ?></p>
    </div>

    <div class="ttd">
        <div><!-- Space for signature --></div>
        <p>Bandung, <?= date('d F Y') ?></p>
        <p class="nama">Dra. Siti Nurhayati, M.Pd.</p>
        <p class="jabatan">Kepala Sekolah</p>
        <p class="nip">NIP. 19651231 198803 2 001</p>
    </div>
</body>

</html>