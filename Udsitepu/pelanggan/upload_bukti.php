<?php
session_start();
include 'inc/db.php';
include 'inc/header.php';

// Harus login
if (!isset($_SESSION['user_id'])) {
  echo "<script>alert('Silakan login terlebih dahulu.'); window.location.href='login.php';</script>";
  include 'inc/footer.php';
  exit;
}

// Jika data checkout kosong
if (!isset($_SESSION['checkout']) || empty($_SESSION['checkout']['keranjang'])) {
  echo "<div class='alert alert-warning'>Data checkout tidak ditemukan.</div>";
  include 'inc/footer.php';
  exit;
}

// Set waktu mulai jika belum ada (fallback)
if (!isset($_SESSION['start_time'])) {
  $_SESSION['start_time'] = time();
}

$checkout     = $_SESSION['checkout'];
$user_id      = $_SESSION['user_id'];
$nama         = $checkout['nama'];
$alamat       = $checkout['alamat'];
$pengiriman   = $checkout['pengiriman'];
$keranjang    = $checkout['keranjang'];
$total        = $checkout['total'];

// Timer (5 menit contoh)
$waktu_sekarang = time();
$batas_waktu    = $_SESSION['start_time'] + (60 * 60);
$sisa_waktu     = $batas_waktu - $waktu_sekarang;

/* =========================
 * AUTO CANCEL (timeout)
 * ========================= */
if ($sisa_waktu <= 0) {
  // Simpan ke orders_temp status batal (stok TIDAK dikurangi)
  $stmt = $conn->prepare("INSERT INTO orders_temp (user_id, customer_name, total, status, created_at) VALUES (?, ?, ?, 'batal', NOW())");
  $stmt->bind_param("isd", $user_id, $nama, $total);
  $stmt->execute();
  $temp_id = $stmt->insert_id;

  // (Tidak ada order_items_temp; kita tidak membuat detail item untuk pesanan batal)

  // Bersihkan session (reset keranjang/checkout)
  unset($_SESSION['checkout']);
  unset($_SESSION['keranjang']);
  unset($_SESSION['start_time']);

  echo "<div class='alert alert-danger'>Waktu pembayaran telah habis. Pesanan dibatalkan otomatis dan dicatat di riwayat.</div>";
  echo "<script>setTimeout(() => window.location.href = 'riwayat.php', 2000);</script>";
  include 'inc/footer.php';
  exit;
}

/* =========================
 * PROSES UPLOAD BUKTI
 * ========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['bukti'])) {
  $file = $_FILES['bukti']['name'] ?? '';
  $tmp  = $_FILES['bukti']['tmp_name'] ?? '';
  $ext  = strtolower(pathinfo($file, PATHINFO_EXTENSION));
  $allowed = ['jpg', 'jpeg', 'png', 'pdf'];

  if (!$file || !in_array($ext, $allowed)) {
    echo "<div class='alert alert-danger'>Format file tidak diperbolehkan.</div>";
  } else {
    $uploadDir = '../uploads/bukti/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    $fileName = time() . '_' . basename($file);
    $filePath = $uploadDir . $fileName;

    if (move_uploaded_file($tmp, $filePath)) {
      // 1) Simpan order ke tabel orders (status: menunggu)
      $stmt = $conn->prepare("INSERT INTO orders (user_id, customer_name, total, status, created_at, delivery_method, address) VALUES (?, ?, ?, 'menunggu', NOW(), ?, ?)");
      $stmt->bind_param("isiss", $user_id, $nama, $total, $pengiriman, $alamat);
      $stmt->execute();
      $order_id = $stmt->insert_id;

      // 2) Simpan setiap item ke order_items + KURANGI stok
      foreach ($keranjang as $id => $qty) {
        $produk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id = $id"));
        if (!$produk) { continue; }
        $price_total = $produk['price'] * $qty;

        $stmtItem = $conn->prepare("INSERT INTO order_items (order_id, product_id, qty, price) VALUES (?, ?, ?, ?)");
        $stmtItem->bind_param("iiid", $order_id, $id, $qty, $price_total);
        $stmtItem->execute();

        // Kurangi stok
        mysqli_query($conn, "UPDATE products SET stock = stock - $qty WHERE id = $id");
      }

      // 3) Simpan bukti pembayaran
      $stmtPay = $conn->prepare("INSERT INTO payments (order_id, file_path, uploaded_at) VALUES (?, ?, NOW())");
      $stmtPay->bind_param("is", $order_id, $fileName);
      $stmtPay->execute();

      // 4) Bersihkan session
      unset($_SESSION['keranjang']);
      unset($_SESSION['checkout']);
      unset($_SESSION['start_time']);

      echo "<div class='alert alert-success'>Bukti berhasil diupload. Pesanan disimpan.</div>";
      echo "<script>setTimeout(function(){ window.location.href='riwayat.php'; }, 1500);</script>";
      include 'inc/footer.php';
      exit;
    } else {
      echo "<div class='alert alert-danger'>Upload bukti gagal.</div>";
    }
  }
}
?>

<div class="container my-5">
  <h4 class="mb-4 text-success">Upload Bukti Transfer</h4>

  <div class="alert alert-warning">
    <strong>Sisa waktu pembayaran:</strong> <span id="timer" class="text-danger fw-bold"></span>
  </div>

  <div class="mb-3">
    <label class="form-label">Silakan transfer ke rekening berikut:</label>
    <div class="alert alert-info">
      <strong>1111222089 a.n. UD SITEPU (Bank Mandiri)</strong>
    </div>
  </div>

  <form action="" method="POST" enctype="multipart/form-data">
    <div class="mb-3">
      <label for="bukti" class="form-label">Upload Bukti (JPG, PNG, PDF)</label>
      <input type="file" name="bukti" id="bukti" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-success">Upload</button>
  </form>
</div>

<script>
  let sisa = <?= (int)$sisa_waktu ?>;
  function updateTimer() {
    if (sisa <= 0) {
      document.getElementById('timer').innerText = "00:00";
      // reload halaman untuk memicu blok timeout (server-side)
      window.location.href = "upload_bukti.php";
      return;
    }
    let menit = Math.floor(sisa / 60);
    let detik = sisa % 60;
    document.getElementById('timer').innerText =
      String(menit).padStart(2, '0') + ":" + String(detik).padStart(2, '0');
    sisa--;
  }
  updateTimer();
  setInterval(updateTimer, 1000);
</script>

<?php include 'inc/footer.php'; ?>
