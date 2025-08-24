<?php
session_start();
include 'inc/db.php';
include 'inc/header.php';

// ========== Cek Login ==========
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Silakan login terlebih dahulu.'); window.location.href='login.php';</script>";
    exit;
}

// ========== Cek Data Checkout ==========
if (!isset($_SESSION['checkout']) || empty($_SESSION['checkout']['keranjang'])) {
    echo "<div class='alert alert-warning'>Data checkout tidak ditemukan.</div>";
    include 'inc/footer.php';
    exit;
}

$checkout    = $_SESSION['checkout'];
$user_id     = $_SESSION['user_id'];
$nama        = $checkout['nama'];
$alamat      = $checkout['alamat'];
$pengiriman  = $checkout['pengiriman'];
$keranjang   = $checkout['keranjang'];
$total       = $checkout['total'];

// Timer
if (!isset($_SESSION['start_time'])) $_SESSION['start_time'] = time();
$waktu_sekarang = time();
$batas_waktu    = $_SESSION['start_time'] + (60 * 60);
$sisa_waktu     = $batas_waktu - $waktu_sekarang;

// Auto Cancel
if ($sisa_waktu <= 0) {
    echo "<div class='alert alert-danger'>Waktu pembayaran telah habis. Pesanan dibatalkan otomatis.</div>";
    unset($_SESSION['checkout'], $_SESSION['start_time']);
    include 'inc/footer.php';
    exit;
}

// Handle Upload Bukti
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['bukti'])) {
    // Cek apakah pesanan sedang diproses (untuk mencegah double processing)
    if (isset($_SESSION['processing_order']) && $_SESSION['processing_order'] === true) {
        echo "<div class='alert alert-warning'>Pesanan sedang diproses. Silakan tunggu...</div>";
        include 'inc/footer.php';
        exit;
    }
    
    // Tandai bahwa pesanan sedang diproses
    $_SESSION['processing_order'] = true;
    
    $file = $_FILES['bukti']['name'] ?? '';
    $tmp  = $_FILES['bukti']['tmp_name'] ?? '';
    $ext  = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'pdf'];

    if (!$file || !in_array($ext, $allowed)) {
        echo "<div class='alert alert-danger'>Format file tidak diperbolehkan.</div>";
        unset($_SESSION['processing_order']);
    } else {
        $uploadDir = '../uploads/bukti/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $fileName = time() . '_' . basename($file);
        $filePath = $uploadDir . $fileName;

        if (move_uploaded_file($tmp, $filePath)) {
            // Mulai transaction
            $conn->begin_transaction();
            
            try {
                // 1. Simpan ke tabel orders
                $stmt = $conn->prepare("INSERT INTO orders 
                    (user_id, customer_name, total, status, created_at, delivery_method, address) 
                    VALUES (?, ?, ?, 'menunggu', NOW(), ?, ?)");
                $stmt->bind_param("isiss", $user_id, $nama, $total, $pengiriman, $alamat);
                
                if (!$stmt->execute()) {
                    throw new Exception("Gagal menyimpan order: " . $stmt->error);
                }
                
                $order_id = $stmt->insert_id;

                // 2. Simpan ke order_items + kurangi stok
                foreach ($keranjang as $id => $qty) {
                    $produk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT price, stock FROM products WHERE id = $id"));
                    if (!$produk) continue;
                    
                    if ($produk['stock'] < $qty) {
                        throw new Exception("Stok produk ID $id tidak cukup.");
                    }
                    
                    $price_total = $produk['price'] * $qty;

                    $stmtItem = $conn->prepare("INSERT INTO order_items (order_id, product_id, qty, price) VALUES (?, ?, ?, ?)");
                    $stmtItem->bind_param("iiid", $order_id, $id, $qty, $price_total);
                    
                    if (!$stmtItem->execute()) {
                        throw new Exception("Gagal menyimpan item order: " . $stmtItem->error);
                    }

                    // Kurangi stok
                    $updateStok = mysqli_query($conn, "UPDATE products SET stock = stock - $qty WHERE id = $id");
                    if (!$updateStok) {
                        throw new Exception("Gagal mengurangi stok produk ID $id.");
                    }
                }

                // 3. Simpan bukti pembayaran
                $stmtPay = $conn->prepare("INSERT INTO payments (order_id, file_path, uploaded_at) VALUES (?, ?, NOW())");
                $stmtPay->bind_param("is", $order_id, $fileName);
                
                if (!$stmtPay->execute()) {
                    throw new Exception("Gagal menyimpan bukti pembayaran: " . $stmtPay->error);
                }

                // Commit transaction
                $conn->commit();

                // 4. Bersihkan session
                unset($_SESSION['checkout'], $_SESSION['start_time'], $_SESSION['processing_order']);

                echo "<div class='alert alert-success'>Bukti berhasil diupload. Pesanan tersimpan ke database dan muncul di riwayat.</div>";
                echo "<script>setTimeout(function(){ window.location.href='riwayat.php'; }, 1500);</script>";
                include 'inc/footer.php';
                exit;

            } catch (Exception $e) {
                // Rollback transaction jika ada error
                $conn->rollback();
                echo "<div class='alert alert-danger'>" . $e->getMessage() . "</div>";
                unset($_SESSION['processing_order']);
            }

        } else {
            echo "<div class='alert alert-danger'>Upload bukti gagal.</div>";
            unset($_SESSION['processing_order']);
        }
    }
}
?>

<div class="container my-5">
  <h4 class="mb-4 text-success">Upload Bukti Transfer</h4>

  <div class="alert alert-warning">
    <strong>Sisa waktu pembayaran:</strong> <span id="timer" class="text-danger fw-bold"></span>
  </div>

  <div class="alert alert-info">
    <strong>1111222089 a.n. UD SITEPU (Bank Mandiri)</strong>
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
        window.location.href = "riwayat.php";
        return;
    }
    let menit = Math.floor(sisa / 60);
    let detik = sisa % 60;
    document.getElementById('timer').innerText =
        String(menit).padStart(2,'0') + ":" + String(detik).padStart(2,'0');
    sisa--;
}
updateTimer();
setInterval(updateTimer, 1000);
</script>

<?php include 'inc/footer.php'; ?>