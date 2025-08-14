<?php
session_start();
include 'db.php';

// Dummy session (hapus ini di produksi)
if (!isset($_SESSION['admin_logged_in'])) {
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_name'] = "Admin";
}

// Query seperti sebelumnya
$resProducts = mysqli_query($conn, "SELECT COUNT(*) AS total FROM products");
$row1 = mysqli_fetch_assoc($resProducts);
$totalproducts = isset($row1['total']) ? $row1['total'] : 0;

$resOrders = mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders");
$row2 = mysqli_fetch_assoc($resOrders);
$totalorders = isset($row2['total']) ? $row2['total'] : 0;

$resRevenue = mysqli_query($conn, "SELECT SUM(total) AS total FROM orders WHERE status != 'menunggu'");
$row3 = mysqli_fetch_assoc($resRevenue);
$totalrevenue = isset($row3['total']) ? $row3['total'] : 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans">

  <div class="flex h-screen overflow-hidden">

    <!-- ✅ Sidebar dimasukkan di sini -->
    <?php include 'inc/sidebar.php'; ?>

    <!-- ✅ Main Content -->
    <div class="flex-1 overflow-y-auto p-6">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white p-6 rounded shadow">
          <p class="text-gray-500">Total Products</p>
          <h3 class="text-2xl font-bold"><?php echo $totalproducts; ?></h3>
        </div>
        <div class="bg-white p-6 rounded shadow">
          <p class="text-gray-500">Total Orders</p>
          <h3 class="text-2xl font-bold"><?php echo $totalorders; ?></h3>
        </div>
        <div class="bg-white p-6 rounded shadow">
          <p class="text-gray-500">Pendapatan</p>
          <h3 class="text-2xl font-bold">Rp<?php echo number_format($totalrevenue, 2); ?></h3>
        </div>
      </div>

      <!-- Recent Orders -->
      <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
          <h2 class="text-lg font-semibold">Recent Orders</h2>
          <a href="pages/orders.php" class="text-blue-500 hover:underline">View All</a>
        </div>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <?php
              $queryRecent = mysqli_query($conn, "SELECT * FROM orders ORDER BY created_at DESC LIMIT 5");
              while ($order = mysqli_fetch_assoc($queryRecent)) {
                  $statusClass = '';
                  $status = strtolower($order['status']);
                  switch ($status) {
                      case 'completed':
                          $statusClass = 'bg-green-100 text-green-800';
                          break;
                      case 'processing':
                          $statusClass = 'bg-blue-100 text-blue-800';
                          break;
                      case 'shipped':
                          $statusClass = 'bg-yellow-100 text-yellow-800';
                          break;
                      case 'pending':
                          $statusClass = 'bg-gray-100 text-gray-800';
                          break;
                      default:
                          $statusClass = '';
                          break;
                  }

                  echo "<tr>
                      <td class='px-6 py-4 whitespace-nowrap'>#ORD-{$order['id']}</td>
                      <td class='px-6 py-4 whitespace-nowrap'>{$order['customer_name']}</td>
                      <td class='px-6 py-4 whitespace-nowrap'>{$order['created_at']}</td>
                      <td class='px-6 py-4 whitespace-nowrap'><span class='px-2 py-1 text-xs rounded-full $statusClass'>{$order['status']}</span></td>
                      <td class='px-6 py-4 whitespace-nowrap'>Rp" . number_format($order['total'], 2) . "</td>
                  </tr>";
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>

    </div> <!-- End Main -->
  </div> <!-- End Wrapper -->

</body>
</html>
