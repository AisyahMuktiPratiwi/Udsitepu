<?php
session_start();
include '../../pelanggan/inc/db.php';

// Proses perubahan status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ubah_status'])) {
  $orderId = (int)$_POST['order_id'];
  $statusBaru = $_POST['status'];
  $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
  $stmt->bind_param("si", $statusBaru, $orderId);
  $stmt->execute();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Admin - Daftar Pesanan</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans">
  <div class="flex h-screen overflow-hidden">
    
    <!-- ✅ Sidebar -->
    <?php include '../inc/sidebar.php'; ?>

    <!-- ✅ Main Content -->
    <div class="flex-1 overflow-y-auto p-10">
      <h2 class="text-2xl font-bold text-green-700 mb-6">📦 Daftar Pesanan Masuk</h2>

      <div class="overflow-x-auto">
        <table class="min-w-full bg-white rounded shadow text-sm">
          <thead class="bg-green-100 text-green-900">
            <tr>
              <th class="px-4 py-3 text-left">#</th>
              <th class="px-4 py-3 text-left">Nama Pelanggan</th>
              <th class="px-4 py-3 text-left">Total</th>
              <th class="px-4 py-3 text-left">Status</th>
              <th class="px-4 py-3 text-left">Tanggal</th>
              <th class="px-4 py-3 text-left">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $result = $conn->query("SELECT * FROM orders ORDER BY created_at DESC");
            $no = 1;
            while ($row = $result->fetch_assoc()):
            ?>
            <tr class="border-t">
              <td class="px-4 py-2"><?= $no++; ?></td>
              <td class="px-4 py-2"><?= htmlspecialchars($row['customer_name']); ?></td>
              <td class="px-4 py-2">Rp <?= number_format($row['total'], 0, ',', '.'); ?></td>
              <td class="px-4 py-2">
                <form method="POST" class="flex items-center gap-2">
                  <input type="hidden" name="order_id" value="<?= $row['id']; ?>">
                  <select name="status" class="border rounded px-2 py-1 text-sm">
                    <option value="menunggu" <?= $row['status'] == 'menunggu' ? 'selected' : '' ?>>Menunggu</option>
                    <option value="diproses" <?= $row['status'] == 'diproses' ? 'selected' : '' ?>>Diproses</option>
                    <option value="dikirim" <?= $row['status'] == 'dikirim' ? 'selected' : '' ?>>Dikirim</option>
                    <option value="selesai" <?= $row['status'] == 'selesai' ? 'selected' : '' ?>>Selesai</option>
                  </select>
              </td>
              <td class="px-4 py-2"><?= $row['created_at']; ?></td>
              <td class="px-4 py-2 space-x-2">
                  <button type="submit" name="ubah_status" class="bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600">Simpan</button>
                  <a href="detail_order.php?id=<?= $row['id']; ?>" class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">Detail</a>
                </form>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>
</html>
