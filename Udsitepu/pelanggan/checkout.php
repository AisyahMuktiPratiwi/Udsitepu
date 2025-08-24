<?php
session_start();
include 'inc/db.php';
include 'inc/header.php';

$produkLangsung = null;
$totalBayar = 0;
$jumlahLangsung = 1;

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Silakan login terlebih dahulu.'); window.location.href='login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id = $user_id"));

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = mysqli_query($conn, "SELECT * FROM products WHERE id = '$id'");
    $produkLangsung = mysqli_fetch_assoc($result);

    if ($produkLangsung) {
        $jumlahLangsung = isset($_POST['qty']) ? (int)$_POST['qty'] : 1;
        $totalBayar = $produkLangsung['price'] * $jumlahLangsung;
    }
} elseif (!empty($_SESSION['keranjang'])) {
    foreach ($_SESSION['keranjang'] as $id => $qty) {
        $q = mysqli_query($conn, "SELECT * FROM products WHERE id = '$id'");
        $p = mysqli_fetch_assoc($q);
        $totalBayar += $p['price'] * $qty;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nama'])) {
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $pengiriman = $_POST['pengiriman'];
    $metode = 'Transfer Bank';
    $bank = 'Mandiri';

    if (isset($_POST['qty']) && isset($produkLangsung)) {
        $jumlahLangsung = (int)$_POST['qty'];
        $totalBayar = $produkLangsung['price'] * $jumlahLangsung;
        $checkoutData = [$produkLangsung['id'] => $jumlahLangsung];
    } else {
        $totalBayar = (int)$_POST['total'];
        $checkoutData = $_SESSION['keranjang'];
    }

    // Simpan data checkout ke SESSION (belum ke DB!)
    $_SESSION['checkout'] = [
        'keranjang' => $checkoutData,
        'total' => $totalBayar,
        'nama' => $nama,
        'alamat' => $alamat,
        'pengiriman' => $pengiriman,
        'bank' => $bank,
        'metode' => $metode,
    ];

    // Set start time untuk timer 60 menit
    $_SESSION['start_time'] = time();

    // Hapus keranjang jika checkout dari keranjang
    if (!isset($_GET['id'])) {
        unset($_SESSION['keranjang']);
    }

    // Arahkan ke halaman upload bukti
    header("Location: upload_bukti.php");
    exit;
}
?>

<div class="container my-5">
    <h3 class="mb-4 text-success">Checkout</h3>

    <?php if ($produkLangsung): ?>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="card mb-3" style="max-width: 500px;">
                <div class="row g-0">
                    <div class="col-md-4">
                        <img src="../uploads/<?= $produkLangsung['image']; ?>" class="img-fluid rounded-start" alt="Produk">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title"><?= $produkLangsung['name']; ?></h5>
                            <p class="card-text text-success">Rp <?= number_format($produkLangsung['price'], 0, ',', '.'); ?> / kg</p>
                            <div class="mb-3">
                                <?php $stokTersedia = $produkLangsung['stock']; ?>
                                <label for="qty" class="form-label">Jumlah (kg)</label>
                                <input type="number"
                                       name="qty"
                                       id="qty"
                                       class="form-control"
                                       value="<?= $jumlahLangsung ?>"
                                       min="1"
                                       max="<?= $stokTersedia ?>"
                                       required
                                       oninput="cekStok(this)"
                                       data-stok="<?= $stokTersedia ?>">

                                <!-- Pesan peringatan -->
                                <small id="stok-warning" class="text-danger d-none">Jumlah melebihi stok tersedia (<?= $stokTersedia ?> kg)</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <h5 class="mb-3 text-end">Total Bayar: <span class="text-success" id="total-display">Rp <?= number_format($totalBayar, 0, ',', '.'); ?></span></h5>
            <input type="hidden" name="total" id="total-hidden" value="<?= $totalBayar; ?>">

            <div class="mb-3">
                <label for="nama" class="form-label">Nama Lengkap</label>
                <input type="text" name="nama" id="nama" class="form-control" required value="<?= htmlspecialchars($user['name']) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Pengiriman</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="pengiriman" id="antar" value="Diantar" required>
                    <label class="form-check-label" for="antar">Diantar</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="pengiriman" id="ambil" value="Ambil Sendiri">
                    <label class="form-check-label" for="ambil">Ambil Sendiri</label>
                </div>
            </div>

            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat Lengkap</label>
                <textarea name="alamat" id="alamat" rows="3" class="form-control" required></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Metode Pembayaran</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="metode_pembayaran" id="transfer" value="Transfer Bank" checked>
                    <label class="form-check-label" for="transfer">
                        Transfer Bank
                    </label>
                    <div class="border rounded p-2 mt-1 bg-light">
                        <small>
                            Lakukan pembayaran Anda langsung ke rekening bank kami, nomor rekening akan ditampilkan setelah proses ini.<br>
                            Pesanan Anda akan dikirim setelah kami menerima pembayaran.
                        </small>
                    </div>
                </div>
                <div class="form-check mt-2">
                    <input class="form-check-input" type="radio" name="metode_pembayaran" id="cod" value="COD - Bayar di tempat">
                    <label class="form-check-label" for="cod">
                        COD - Bayar di tempat
                    </label>
                </div>
            </div>

            <button type="submit" class="btn btn-success">Checkout</button>
        </form>

        <script>
            function updateTotal() {
                const qty = document.getElementById('qty').value;
                const harga = <?= $produkLangsung['price']; ?>;
                const total = qty * harga;
                document.getElementById('total-display').innerText = 'Rp ' + total.toLocaleString('id-ID');
                document.getElementById('total-hidden').value = total;
            }

            function cekStok(input) {
                const stok = parseInt(input.dataset.stok);
                const qty = parseInt(input.value);
                const warning = document.getElementById('stok-warning');

                if (qty > stok) {
                    input.classList.add('is-invalid');
                    warning.classList.remove('d-none');
                } else {
                    input.classList.remove('is-invalid');
                    warning.classList.add('d-none');
                }
                
                updateTotal();
            }
            
            // Inisialisasi event listener
            document.getElementById('qty').addEventListener('input', function() {
                cekStok(this);
            });
        </script>

    <?php elseif (!empty($_SESSION['keranjang'])): ?>
        <div class="alert alert-info">Checkout dari keranjang belum ditambahkan.</div>
    <?php else: ?>
        <div class="alert alert-warning">Tidak ada produk yang ingin dibayar.</div>
        <a href="produk.php" class="btn btn-outline-success">Lihat Produk</a>
    <?php endif; ?>
</div>

<?php include 'inc/footer.php'; ?>