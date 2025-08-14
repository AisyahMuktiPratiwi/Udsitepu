<?php
session_start();
include '../db.php';
require('fpdf182/fpdf.php');

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../login.php");
    exit();
}

// Ambil parameter
$tanggal_awal = isset($_GET['tanggal_awal']) ? $_GET['tanggal_awal'] : '';
$tanggal_akhir = isset($_GET['tanggal_akhir']) ? $_GET['tanggal_akhir'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Fungsi ambil data laporan
function getLaporan($conn, $tanggal_awal = '', $tanggal_akhir = '', $search = '') {
    $where = [];

    if (!empty($tanggal_awal) && !empty($tanggal_akhir)) {
        $where[] = "DATE(o.created_at) BETWEEN '$tanggal_awal' AND '$tanggal_akhir'";
    }

    if (!empty($search)) {
        $search = mysqli_real_escape_string($conn, $search);
        $where[] = "p.name LIKE '%$search%'";
    }

    $whereSQL = $where ? "WHERE " . implode(" AND ", $where) : "";

    $query = "
        SELECT 
            p.name, 
            SUM(oi.qty) AS total_terjual, 
            SUM(oi.qty * p.price) AS total_pendapatan,
            DATE(o.created_at) AS tanggal_pemesanan
        FROM order_items oi
        JOIN products p ON oi.product_id = p.id
        JOIN orders o ON oi.order_id = o.id
        $whereSQL
        GROUP BY p.id, DATE(o.created_at)
        ORDER BY tanggal_pemesanan DESC
    ";

    $result = mysqli_query($conn, $query);
    if (!$result) {
        die("Query Error: " . mysqli_error($conn));
    }

    return $result;
}

// Hitung total pendapatan
function getTotalPendapatan($items) {
    $total = 0;
    mysqli_data_seek($items, 0);
    while ($row = mysqli_fetch_assoc($items)) {
        $total += $row['total_pendapatan'];
    }
    mysqli_data_seek($items, 0);
    return $total;
}

// Cetak PDF
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['print'])) {
    $items = getLaporan($conn, $tanggal_awal, $tanggal_akhir, $search);
    $totalPendapatan = getTotalPendapatan($items);

    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',14);
    $pdf->Cell(0,10,'Laporan Produk Terjual',0,1,'C');

    if ($tanggal_awal && $tanggal_akhir) {
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(0,10,"Periode: $tanggal_awal s/d $tanggal_akhir", 0, 1);
    }

    if ($search) {
        $pdf->Cell(0,10,"Pencarian: $search", 0, 1);
    }

    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(60,10,'Nama Produk',1);
    $pdf->Cell(30,10,'Terjual',1);
    $pdf->Cell(40,10,'Tanggal',1);
    $pdf->Cell(50,10,'Pendapatan',1);
    $pdf->Ln();

    $pdf->SetFont('Arial','',10);
    while ($row = mysqli_fetch_assoc($items)) {
        $pdf->Cell(60,10,$row['name'],1);
        $pdf->Cell(30,10,$row['total_terjual'],1);
        $pdf->Cell(40,10,$row['tanggal_pemesanan'],1);
        $pdf->Cell(50,10,'Rp '.number_format($row['total_pendapatan'], 0, ',', '.'),1);
        $pdf->Ln();
    }

    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(130,10,'Total Pendapatan',1);
    $pdf->Cell(50,10,'Rp '.number_format($totalPendapatan, 0, ',', '.'),1);
    $pdf->Output();
    exit;
}

$items = getLaporan($conn, $tanggal_awal, $tanggal_akhir, $search);
$totalPendapatan = getTotalPendapatan($items);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
        }
        .sidebar-fixed {
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            background-color: #fff;
            border-right: 1px solid #dee2e6;
            z-index: 1000;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
            min-height: 100vh;
        }
        .card-custom {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            padding: 25px;
        }
        .total-box {
            background: #d1ecf1;
            border-left: 5px solid #0d6efd;
            padding: 20px;
            font-weight: bold;
            color: #0c5460;
            border-radius: 8px;
        }
        .total-box h4 {
            margin: 0;
        }
    </style>
</head>
<body>

<!-- ✅ Sidebar -->
<div class="sidebar-fixed">
    <?php include '../inc/sidebar.php'; ?>
</div>

<!-- ✅ Main Content -->
<div class="main-content">
    <div class="card card-custom">
        <h3 class="text-center mb-4">📊 Laporan Penjualan Produk</h3>

        <!-- Filter -->
        <form class="row g-3 mb-3" method="get">
            <div class="col-md-3">
                <label class="form-label">Dari Tanggal</label>
                <input type="date" name="tanggal_awal" class="form-control" value="<?= htmlspecialchars($tanggal_awal) ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Sampai Tanggal</label>
                <input type="date" name="tanggal_akhir" class="form-control" value="<?= htmlspecialchars($tanggal_akhir) ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Cari Nama Produk</label>
                <input type="text" name="search" class="form-control" placeholder="Contoh: Pupuk Organik" value="<?= htmlspecialchars($search) ?>">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-success me-2">Filter</button>
                <a href="laporan.php" class="btn btn-secondary">Reset</a>
            </div>
        </form>

        <!-- Tabel -->
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle text-center">
                <thead class="table-primary">
                    <tr>
                        <th>📌 Produk</th>
                        <th>📦 Terjual</th>
                        <th>📅 Tanggal</th>
                        <th>💰 Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($items) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($items)) : ?>
                            <tr>
                                <td><?= htmlspecialchars($row['name']) ?></td>
                                <td><span class="badge bg-info text-dark"><?= $row['total_terjual'] ?></span></td>
                                <td><?= $row['tanggal_pemesanan'] ?></td>
                                <td class="text-success fw-bold">Rp <?= number_format($row['total_pendapatan'], 0, ',', '.') ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="4" class="text-muted">Tidak ada data ditemukan.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Total Pendapatan -->
        <div class="mt-4 text-end">
            <div class="total-box d-inline-block">
                <h4>Total Pendapatan: Rp <?= number_format($totalPendapatan, 0, ',', '.') ?></h4>
            </div>
        </div>

        <!-- Tombol Cetak -->
        <form method="post" target="_blank" action="?print=1&tanggal_awal=<?= urlencode($tanggal_awal) ?>&tanggal_akhir=<?= urlencode($tanggal_akhir) ?>&search=<?= urlencode($search) ?>">
            <div class="text-end mt-3">
                <button type="submit" class="btn btn-primary">🖨️ Cetak PDF</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>
