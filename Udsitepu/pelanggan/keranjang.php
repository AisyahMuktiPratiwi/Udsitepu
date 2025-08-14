<?php
session_start();
include 'inc/db.php';
include 'inc/header.php';

$total = 0;
?>
<div class="container my-5">
  <h3 class="mb-4 text-success">Keranjang Belanja</h3>
  <?php if (!empty($_SESSION['keranjang'])): ?>
    <form action="simpan_checkout.php" method="POST">
      <div class="row">
        <div class="col-lg-8">
          <table class="table table-bordered align-middle">
            <thead>
              <tr class="table-success">
                <th>Produk</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($_SESSION['keranjang'] as $id => $qty):
                $produk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id = $id"));
                $stok = $produk['stock'];
                if ($qty > $stok) {
                  $_SESSION['keranjang'][$id] = $stok;
                  $qty = $stok;
                }
                $subtotal = $produk['price'] * $qty;
                $total += $subtotal;
              ?>
              <tr>
                <td>
                  <img src="../uploads/<?= $produk['image'] ?>" width="60" class="me-2 rounded">
                  <?= htmlspecialchars($produk['name']) ?>
                </td>
                <td>Rp <?= number_format($produk['price'], 0, ',', '.') ?></td>
                <td>
                  <input type="number" name="qty[<?= $id ?>]" value="<?= $qty ?>" min="1" max="<?= $stok ?>"
                    class="form-control" id="qty-<?= $id ?>" data-price="<?= $produk['price'] ?>"
                    oninput="updateSubtotal(<?= $id ?>)">
                </td>
                <td id="subtotal-<?= $id ?>">Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
                <td><a href="hapus_keranjang.php?id=<?= $id ?>" class="btn btn-danger btn-sm">Hapus</a></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
         <a href="produk.php"  class="btn btn-warning w-30"> pilih produk kembali</a>

        </div>

        <div class="col-lg-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Ringkasan Belanja</h5>
              <p>Total: <strong class="text-success" id="total-belanja">Rp <?= number_format($total, 0, ',', '.') ?></strong></p>

              <?php 
              $user_id = $_SESSION['user_id'];
              $user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id = $user_id"));
              ?>
              <input type="hidden" name="total" id="total-hidden" value="<?= $total ?>">

              <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" required value="<?= htmlspecialchars($user['name']) ?>">
              </div>

              <div class="mb-3">
                <label class="form-label">Metode Pengiriman</label><br>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="pengiriman" value="Diantar" required> Diantar
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="pengiriman" value="Ambil Sendiri"> Ambil Sendiri
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label">Alamat</label>
                <textarea name="alamat" rows="3" class="form-control" required></textarea>
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
               Lakukan pembayaran Anda langsung ke rekening bank kami, nomor rekening akan ditampilkan setelah proses ini.Pesanan Anda akan dikirim setelah kami menerima pembayaran.
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

              <button type="submit" class="btn btn-success w-100">Checkout</button>
            </div>
          </div>
        </div>
      </div>
    </form>

    <!-- Script update subtotal & total -->
    <script>
      function updateSubtotal(id) {
        let qty = parseInt(document.getElementById('qty-' + id).value);
        let price = parseInt(document.getElementById('qty-' + id).dataset.price);
        let subtotal = qty * price;
        document.getElementById('subtotal-' + id).innerText = formatRupiah(subtotal);
        updateTotal();
      }

      function updateTotal() {
        let total = 0;
        document.querySelectorAll('input[name^="qty["]').forEach(input => {
          let qty = parseInt(input.value);
          let price = parseInt(input.dataset.price);
          total += qty * price;
        });
        document.getElementById('total-belanja').innerText = formatRupiah(total);
        document.getElementById('total-hidden').value = total;
      }

      function formatRupiah(number) {
        return 'Rp ' + number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
      }
    </script>
  <?php else: ?>
    <div class="alert alert-warning">Keranjang kosong.</div>
    <a href="produk.php" class="btn btn-success">Belanja Sekarang</a>
  <?php endif; ?>
</div>
<?php include 'inc/footer.php'; ?>
