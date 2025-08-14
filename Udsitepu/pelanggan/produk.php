<?php
include 'inc/db.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Produk - UD SITEPU</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Tambahkan link ke Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<?php include 'inc/header.php'; ?>

<div class="container mt-5">
  <h2 class="text-success text-center mb-4">🌱 Daftar Produk Kami</h2>
  <div class="row">
    <?php
    // Ambil data produk dari database
    $result = $conn->query("SELECT * FROM products ORDER BY name ASC");
    if ($result->num_rows > 0):
      while ($row = $result->fetch_assoc()):
    ?>
      <div class="col-md-3 mb-4">
        <div class="card shadow-sm h-100">
          <!-- Gambar Produk -->
          <img src="../uploads/<?= htmlspecialchars($row['image']); ?>" alt="<?= htmlspecialchars($row['name']); ?>" class="card-img-top" style="height: 200px; object-fit: cover;">

          <div class="card-body text-center">
            <h5 class="card-title"><?= htmlspecialchars($row['name']); ?></h5>
            <p class="text-success fw-bold">Rp <?= number_format($row['price'], 0, ',', '.'); ?> / kg</p>
            <p class="text-success fw-bold"><?= htmlspecialchars($row['stock']); ?></p>
            <!-- Tombol Beli dan Keranjang -->
            <div class="d-grid gap-2">
        <?php if ($row['stock'] > 0): ?>
  <a href="checkout.php?id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">
    🛒 Beli Sekarang
  </a>
  <button type="button" class="btn btn-success btn-sm tambah-keranjang" data-id="<?= $row['id']; ?>" title="Tambah ke Keranjang">
    <i class="bi bi-cart-plus"></i> Tambah
  </button>
<?php else: ?>
  <button class="btn btn-secondary btn-sm" disabled>
    Stok Habis
  </button>
<?php endif; ?>


            </div>
          </div>
        </div>
      </div>
    <?php endwhile; else: ?>
      <div class="col-12">
        <p class="text-center text-muted">Belum ada produk tersedia.</p>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php include 'inc/footer.php'; ?>
</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function () {
    $('.tambah-keranjang').click(function () {
      const productId = $(this).data('id');
      $.ajax({
        url: 'tambah_keranjang_ajax.php',
        method: 'POST',
        data: { id: productId },
        dataType: 'json',
        success: function (res) {
          if (res.status === 'ok') {
            // Update isi badge keranjang tanpa reload
            $('#jumlah-keranjang').text(res.jumlah).show();

            // Kalau span belum ada, buat span baru
            if ($('#jumlah-keranjang').length === 0) {
              $('.fa-shopping-cart').after(`
                <span id="jumlah-keranjang" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                  ${res.jumlah}
                </span>
              `);
            }
          } else {
            alert(res.message);
          }
        },
        error: function () {
          alert('Gagal menambahkan ke keranjang!');
        }
      });
    });
  });
</script>


</html>
