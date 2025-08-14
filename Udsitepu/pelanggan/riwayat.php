<?php
session_start();
include 'inc/db.php';
include 'inc/header.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Silakan login terlebih dahulu.'); window.location.href='login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];

/*
 * Ambil riwayat dari:
 * 1) orders (semua status yg ada di enum)
 * 2) orders_temp yang status='batal'
 * Kolom disejajarkan, tambahkan kolom 'source' untuk beda tabel
 */
$sql = "
  (
    SELECT 
      o.id,
      o.customer_name,
      o.total,
      o.status,
      o.created_at,
      'orders' AS source
    FROM orders o
    WHERE o.user_id = ?
  )
  UNION ALL
  (
    SELECT
      ot.id,
      ot.customer_name,
      ot.total,
      ot.status,
      ot.created_at,
      'temp' AS source
    FROM orders_temp ot
    WHERE ot.user_id = ? AND ot.status = 'batal'
  )
  ORDER BY created_at DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $user_id);

if (!$stmt->execute()) {
    echo "Query error: " . $stmt->error;
    exit;
}

$result = $stmt->get_result();
?>

<div class="container mt-4">
  <h3>🧾 Riwayat Pesanan</h3>
  <hr>

  <?php if ($result->num_rows > 0): ?>
    <?php while ($order = $result->fetch_assoc()): ?>
      <div class="mb-4 p-3 border rounded shadow-sm">
        <strong>Tanggal:</strong> <?= htmlspecialchars($order['created_at']) ?><br>
        <strong>Status:</strong>
        <?php if ($order['source'] === 'temp' && $order['status'] === 'batal'): ?>
          <span class="badge bg-danger">Batal (Waktu Habis)</span>
        <?php else: ?>
          <span class="badge bg-secondary"><?= htmlspecialchars($order['status']) ?></span>
        <?php endif; ?>
        <br>

        <strong>Total:</strong>
        <span class="text-success">Rp<?= number_format($order['total'], 0, ',', '.') ?></span><br>

        <?php if ($order['source'] === 'orders'): ?>
          <?php if ($order['status'] === 'menunggu'): ?>
            <a href="upload_bukti.php?order_id=<?= $order['id'] ?>" class="btn btn-sm btn-outline-primary mt-2">
              Upload Bukti Pembayaran
            </a>
          <?php endif; ?>

          <h5 class="mt-3 mb-2">📦 Rincian Produk:</h5>
          <ul class="list-unstyled">
            <?php
              $order_id = (int)$order['id'];
              $items = $conn->query("
                SELECT oi.*, p.name, p.image 
                FROM order_items oi 
                LEFT JOIN products p ON oi.product_id = p.id 
                WHERE oi.order_id = $order_id
              ");
              while ($item = $items->fetch_assoc()):
                  $qty = (int)$item['qty'];
                  $subtotal = (int)$item['price']; // kolom price = qty x harga_satuan
                  $harga_satuan = ($qty != 0) ? ($subtotal / $qty) : 0;
            ?>
            <li class="d-flex align-items-center mb-3">
              <?php if (!empty($item['image'])): ?>
                <img src="../uploads/<?= $item['image'] ?>" width="60" height="60" class="me-3 rounded border">
              <?php else: ?>
                <div style="width:60px; height:60px;" class="me-3 bg-secondary rounded"></div>
              <?php endif; ?>
              <div>
                <strong><?= htmlspecialchars($item['name']) ?></strong><br>
                <small>
                  <?= $qty ?> pcs × Rp<?= number_format($harga_satuan, 0, ',', '.') ?>
                  = <strong>Rp<?= number_format($subtotal, 0, ',', '.') ?></strong>
                </small>
              </div>
            </li>
            <?php endwhile; ?>
          </ul>
        <?php else: ?>
          <!-- Pesanan dari orders_temp (BATAL) — tidak ada rincian item -->
          <div class="mt-3 alert alert-warning mb-0">
            Pesanan dibatalkan karena melewati batas waktu pembayaran. Detail produk tidak tersedia.
          </div>
        <?php endif; ?>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p>Tidak ada riwayat pesanan.</p>
  <?php endif; ?>
</div>

<?php include 'inc/footer.php'; ?>
