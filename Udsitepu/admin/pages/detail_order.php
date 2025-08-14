<?php
session_start();
include '../db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  echo "ID pesanan tidak ditemukan!";
  exit;
}

$order_id = (int)$_GET['id'];

// Verifikasi pembayaran jika ada aksi
if (isset($_GET['verifikasi']) && $_GET['verifikasi'] === 'true') {
  $conn->query("UPDATE orders SET status = 'Dibayar' WHERE id = $order_id");
  header("Location: detail_order.php?id=$order_id");
  exit;
}

// Ambil data pesanan
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

if (!$order) {
  echo "Pesanan tidak ditemukan!";
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Detail Pesanan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container my-5">
  <h2 class="mb-4 text-success">📄 Detail Pesanan #<?= $order['id'] ?></h2>

  <table class="table table-bordered">
    <tr><th>Nama Pelanggan</th><td><?= htmlspecialchars($order['customer_name']) ?></td></tr>
    <tr><th>Total</th><td>Rp <?= number_format($order['total'], 0, ',', '.') ?></td></tr>
    <tr><th>Status</th><td><?= $order['status'] ?></td></tr>
    <tr><th>Tanggal</th><td><?= $order['created_at'] ?></td></tr>
  </table>

  <h5 class="mt-5 mb-3 text-success">🧾 Bukti Pembayaran</h5>
  <?php
  $bukti = $conn->query("SELECT * FROM payments WHERE order_id = $order_id")->fetch_assoc();
  if ($bukti && !empty($bukti['file_path'])):
    $filePath = "../../uploads/bukti/" . htmlspecialchars($bukti['file_path']);
  ?>
    <p><strong>Waktu Upload:</strong> <?= $bukti['uploaded_at'] ?></p>
    <img src="<?= $filePath ?>" alt="Bukti Transfer" class="img-fluid rounded border mb-3" style="max-width: 400px; display: block;">
    <a href="<?= $filePath ?>" download class="btn btn-primary me-2">⬇️ Download Bukti</a>
  <?php else: ?>
    <p class="text-muted">Belum ada bukti pembayaran yang diunggah.</p>
  <?php endif; ?>
</div>
</body>
</html>
