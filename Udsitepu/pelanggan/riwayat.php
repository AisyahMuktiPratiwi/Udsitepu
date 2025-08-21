<?php
session_start();
date_default_timezone_set('Asia/Jakarta');
include 'inc/db.php';
include 'inc/header.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Silakan login terlebih dahulu.'); window.location.href='login.php';</script>";
    exit;
}
$user_id = (int)$_SESSION['user_id'];

/* ======================
   Query data dari DB
====================== */
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
$stmt->execute();
$result = $stmt->get_result();

/* ======================================================
   CEGAH DOUBLE: tampilkan blok SESSION hanya jika:
   - ada $_SESSION['checkout'] & start_time
   - TIDAK ada record orders yang dibuat >= start_time
====================================================== */
$bolehTampilSession = false;
if (isset($_SESSION['checkout'], $_SESSION['start_time'])) {
    $start = (int)$_SESSION['start_time'];            // unix time
    $limitDt = date('Y-m-d H:i:s', $start);           // untuk dibandingkan dengan created_at di DB

    // cek apakah sdh ada order pasca start_time
    $cek = $conn->prepare("SELECT COUNT(*) AS jml FROM orders WHERE user_id=? AND created_at >= ?");
    $cek->bind_param("is", $user_id, $limitDt);
    $cek->execute();
    $row = $cek->get_result()->fetch_assoc();
    $sudahAdaOrder = ((int)$row['jml'] > 0);

    // hitung sisa waktu 60 menit
    $sisa = ($start + 60*60) - time();

    if (!$sudahAdaOrder && $sisa > 0) {
        $bolehTampilSession = true;
    }
}
?>

<div class="container mt-4">
  <h3>🧾 Riwayat Pesanan</h3>
  <hr>

  <?php if ($bolehTampilSession): 
      $checkout = $_SESSION['checkout'];
      $start_time = (int)$_SESSION['start_time'];
  ?>
    <div class="mb-4 p-3 border rounded shadow-sm">
      <strong>Tanggal:</strong> <?= date('Y-m-d H:i:s', $start_time) ?><br>
      <strong>Status:</strong> <span class="badge bg-secondary">menunggu</span><br>
      <strong>Total:</strong> <span class="text-success">Rp<?= number_format($checkout['total'], 0, ',', '.') ?></span><br>

      <a href="upload_bukti.php?source=session" class="btn btn-sm btn-outline-primary mt-2">
        Upload Bukti Pembayaran
      </a>

      <h5 class="mt-3 mb-2">📦 Rincian Produk:</h5>
      <ul class="list-unstyled">
        <?php foreach ($checkout['keranjang'] as $id => $qty): 
            $id = (int)$id;
            $qty = (int)$qty;
            $res = mysqli_query($conn, "SELECT name, price, image FROM products WHERE id = $id");
            if (!$res) continue;
            $prod = mysqli_fetch_assoc($res);
            if (!$prod) continue;
            $harga_satuan = (int)$prod['price'];
            $subtotal = $harga_satuan * $qty;
        ?>
          <li class="d-flex align-items-center mb-3">
            <?php if (!empty($prod['image'])): ?>
              <img src="../uploads/<?= htmlspecialchars($prod['image']) ?>" width="60" height="60" class="me-3 rounded border">
            <?php else: ?>
              <div style="width:60px;height:60px" class="me-3 bg-secondary rounded"></div>
            <?php endif; ?>
            <div>
              <strong><?= htmlspecialchars($prod['name']) ?></strong><br>
              <small>
                <?= $qty ?> pcs × Rp<?= number_format($harga_satuan, 0, ',', '.') ?>
                = <strong>Rp<?= number_format($subtotal, 0, ',', '.') ?></strong>
              </small>
            </div>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <?php if ($result->num_rows > 0): ?>
    <?php while ($order = $result->fetch_assoc()): ?>
      <div class="mb-4 p-3 border rounded shadow-sm">
        <strong>Tanggal:</strong> <?= date('Y-m-d H:i:s', strtotime($order['created_at'])) ?><br>
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
            <a href="upload_bukti.php?order_id=<?= (int)$order['id'] ?>" class="btn btn-sm btn-outline-primary mt-2">
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
                  $subtotal = (int)$item['price']; // total per item
                  $harga_satuan = $qty ? ($subtotal / $qty) : 0;
            ?>
              <li class="d-flex align-items-center mb-3">
                <?php if (!empty($item['image'])): ?>
                  <img src="../uploads/<?= htmlspecialchars($item['image']) ?>" width="60" height="60" class="me-3 rounded border">
                <?php else: ?>
                  <div style="width:60px;height:60px" class="me-3 bg-secondary rounded"></div>
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
