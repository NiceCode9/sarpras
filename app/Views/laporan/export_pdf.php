<!-- app/Views/laporan/export_pdf.php -->
<!DOCTYPE html>
<html>

<head>
    <title>Laporan Peminjaman</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
            text-align: left;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .periode {
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>Laporan Peminjaman Sarana</h2>
        <h3>Sekolah XYZ</h3>
    </div>

    <div class="periode">
        <strong>Periode:</strong>
        <?= date('d M Y', strtotime($start_date)) ?> s/d <?= date('d M Y', strtotime($end_date)) ?>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Peminjam</th>
                <th>Sarana</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($peminjaman as $key => $item): ?>
                <tr>
                    <td><?= $key + 1 ?></td>
                    <td><?= $item['nama_user'] ?></td>
                    <td><?= $item['nama_sarana'] ?></td>
                    <td><?= date('d M Y', strtotime($item['tgl_pinjam'])) ?></td>
                    <td><?= date('d M Y', strtotime($item['tgl_kembali'])) ?></td>
                    <td><?= ucfirst($item['status']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>