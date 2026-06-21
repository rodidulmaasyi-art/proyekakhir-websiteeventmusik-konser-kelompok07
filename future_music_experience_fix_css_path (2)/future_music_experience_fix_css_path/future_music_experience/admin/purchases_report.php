<?php
include '../config/database.php';
require_admin();

$current_month = (int)date('m');
$current_year = date('Y');
$current_date = date('d M Y, H:i \W\I\B');

// Get purchase data grouped by month
$rows = db_all("SELECT p.*,u.name,e.title,t.ticket_name 
    FROM purchases p 
    JOIN users u ON p.id_user=u.id_user 
    JOIN events e ON p.id_event=e.id_event 
    JOIN ticket_types t ON p.id_ticket=t.id_ticket 
    WHERE MONTH(p.created_at)=$current_month AND YEAR(p.created_at)=$current_year
    ORDER BY p.created_at ASC, u.name");

$month_name = date('F', mktime(0, 0, 0, $current_month, 1));
$month_name_id = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'][$current_month];
$total_sales = 0;
foreach ($rows as $row) {
    $total_sales += (float)$row['total_price'];
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan <?= e($month_name); ?> <?= e($current_year); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="<?= url('assets/css/style.css'); ?>">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: white !important;
                padding: 0 !important;
                margin: 0 !important;
                color: #000 !important;
            }

            .report-page {
                box-shadow: none !important;
                margin: 0 !important;
                page-break-after: always;
                padding: 20px !important;
            }

            table {
                page-break-inside: avoid;
            }

            tr {
                page-break-inside: avoid;
            }

            .sales-table th {
                background-color: #e0e0e0 !important;
                color: #000 !important;
            }

            .sales-table td {
                color: #000 !important;
                background-color: white !important;
            }

            .sales-table tbody tr:nth-child(even) {
                background-color: #ffffff !important;
                color: #000 !important;
            }

            .sales-table tbody tr:nth-child(odd) {
                background-color: #ffffff !important;
                color: #000 !important;
            }

            .total-row {
                background-color: #d0d0d0 !important;
                color: #000 !important;
            }

            .total-row td {
                background-color: #d0d0d0 !important;
                color: #000 !important;
            }

            * {
                color: #000 !important;
            }
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }

        .report-page {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header-title {
            text-align: center;
            margin-bottom: 5px;
            font-size: 20px;
            font-weight: bold;
            letter-spacing: 2px;
            color: #000;
        }

        .header-subtitle {
            text-align: center;
            margin-bottom: 5px;
            font-size: 12px;
            color: #333;
            font-weight: 600;
        }

        .header-address {
            text-align: center;
            margin-bottom: 15px;
            font-size: 11px;
            color: #666;
        }

        .separator {
            border-top: 2px solid #000;
            margin-bottom: 15px;
        }

        .info-box {
            border: 1px solid #000;
            padding: 15px;
            margin-bottom: 15px;
        }

        .info-box-title {
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 10px;
            color: #333;
            text-transform: uppercase;
        }

        .info-box-content h2 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #000;
        }

        .info-box-content p {
            font-size: 12px;
            color: #666;
            margin: 0;
        }

        .table-title {
            font-size: 12px;
            font-weight: bold;
            margin: 20px 0 5px 0;
            color: #333;
            text-transform: uppercase;
        }

        .table-description {
            font-size: 11px;
            color: #666;
            margin-bottom: 10px;
        }

        .sales-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 11px;
            color: #000;
        }

        .sales-table thead {
            background-color: #fff;
        }

        .sales-table th {
            padding: 10px 8px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #000;
            background-color: #d0d0d0;
            font-size: 11px;
            color: #000;
        }

        .sales-table td {
            padding: 8px;
            border: 1px solid #000;
            vertical-align: top;
            font-size: 11px;
            color: #000;
        }

        .sales-table tbody tr:nth-child(odd) {
            background-color: #fff;
            color: #000;
        }

        .sales-table tbody tr:nth-child(even) {
            background-color: #ffffff;
            color: #000;
        }

        .no-column {
            width: 30px;
            text-align: center;
            font-weight: bold;
        }

        .total-row {
            background-color: #e8e8e8;
            font-weight: bold;
        }

        .total-row td {
            padding: 10px 8px;
            border: 1px solid #000;
        }

        .action-buttons {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-print {
            background-color: #333;
            color: white;
        }

        .btn-print:hover {
            background-color: #555;
        }

        .btn-back {
            background-color: #666;
            color: white;
        }

        .btn-back:hover {
            background-color: #888;
        }
    </style>
</head>

<body>
    <div class="action-buttons no-print">
        <button class="btn btn-print" onclick="window.print()">🖨️ Cetak / Download PDF</button>
        <a href="<?= url('admin/purchases.php'); ?>" class="btn btn-back">← Kembali</a>
    </div>

    <div class="report-page">
        <!-- Header -->
        <div class="header-title">FUTURE MUSIC EXPERIENCE</div>
        <div class="header-subtitle">Sistem Informasi Manajemen Pelayanan Registrasi Penjualan Tiket Musik</div>
        <div class="header-address">Madura</div>

        <!-- Separator -->
        <div class="separator"></div>

        <!-- Info Box -->
        <div class="info-box">
            <div class="info-box-title">📄 Jenis Dokumen: Laporan Bulanan</div>
            <div class="info-box-content">
                <h2>Periode Bulan <?= e($month_name_id); ?> <?= e($current_year); ?></h2>
                <p>Berkas diekstrak otomatis pada: <?= e($current_date); ?></p>
            </div>
        </div>

        <!-- Data Section -->
        <div class="table-title">📊 Data Penjualan Tiket</div>
        <div class="table-description">Arsip pendaftaran resmi penjualan tiket didasarkan bulan ini rekapitulasi terpilih</div>

        <!-- Sales Table -->
        <table class="sales-table">
            <thead>
                <tr>
                    <th class="no-column">NO</th>
                    <th>TANGGAL</th>
                    <th>PEMBELI</th>
                    <th>EVENT</th>
                    <th>TIKET</th>
                    <th style="width: 50px; text-align: center;">QTY</th>
                    <th style="width: 120px; text-align: right;">TOTAL HARGA</th>
                    <th style="width: 80px;">STATUS</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($rows)):
                    $no = 1;
                    foreach ($rows as $r):
                ?>
                        <tr>
                            <td class="no-column"><?= $no++; ?></td>
                            <td><?= date('d-m-Y H:i', strtotime($r['created_at'])); ?></td>
                            <td><?= e($r['name']); ?></td>
                            <td><?= e($r['title']); ?></td>
                            <td><?= e($r['ticket_name']); ?></td>
                            <td style="text-align: center;"><?= (int)$r['quantity']; ?></td>
                            <td style="text-align: right;"><?= rupiah($r['total_price']); ?></td>
                            <td>
                                <span style="font-size: 10px; font-weight: 600;">
                                    <?= $r['status'] === 'completed' ? '✓ Selesai' : ($r['status'] === 'pending' ? '⏳ Proses' : e($r['status'])); ?>
                                </span>
                            </td>
                        </tr>
                    <?php
                    endforeach;
                    ?>
                    <tr class="total-row">
                        <td colspan="6" style="text-align: right;"><strong>TOTAL PENJUALAN</strong></td>
                        <td style="text-align: right;"><strong><?= rupiah($total_sales); ?></strong></td>
                        <td></td>
                    </tr>
                <?php else: ?>
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 20px; color: #666;">
                            Tidak ada data penjualan untuk bulan ini.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Footer -->
        <div style="margin-top: 30px; text-align: right; font-size: 11px; color: #666;">
            <p style="margin: 5px 0;">Laporan dihasilkan otomatis pada: <?= date('d M Y \p\u\k\u\l H:i:s'); ?></p>
            <p style="margin: 5px 0;">Dicetak oleh: Sistem Informasi FMX</p>
        </div>
    </div>
</body>

</html>