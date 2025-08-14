<?php
include 'inc/db.php'; // koneksi ke database
include 'inc/header.php';

$keyword = isset($_GET['q']) ? trim($_GET['q']) : '';
?>

<div class="container mt-4">
  <h4 class="mb-3">Hasil Pencarian untuk: <em><?php echo htmlspecialchars($keyword); ?></em></h4>
  <div class="row">

    <?php
    if ($keyword !== '') {
        $stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE ?");
        $searchTerm = "%{$keyword}%";
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($product = $result->fetch_assoc()) {
                echo '
                <div class="col-md-3 mb-4">
                  <div class="card shadow-sm h-100">
                    <img src="../uploads/' . htmlspecialchars($product['image']) . '" class="card-img-top" style="height:180px; object-fit:cover;" alt="' . htmlspecialchars($product['name']) . '">
                    <div class="card-body text-center">
                      <h5 class="card-title">' . htmlspecialchars($product['name']) . '</h5>
                      <p class="card-text text-success fw-bold">Rp ' . number_format($product['price'], 0, ',', '.') . '</p>
                      <a href="checkout.php?id=' . $product['id'] . '" class="btn btn-success btn-sm">Beli Sekarang</a>
                    </div>
                  </div>
                </div>';
            }
        } else {
            echo '<p class="text-muted">Tidak ada produk ditemukan.</p>';
        }

        $stmt->close();
    } else {
        echo '<p class="text-muted">Masukkan kata kunci pencarian.</p>';
    }
    ?>

  </div>
</div>

<?php include 'inc/footer.php'; ?>
