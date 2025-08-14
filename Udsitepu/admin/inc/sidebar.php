<?php
if (!isset($_SESSION)) session_start();

// Fungsi dinamis untuk menentukan path relatif
function getPath($path) {
  // Dapatkan nama file yang sedang dijalankan
  $currentFile = basename($_SERVER['SCRIPT_FILENAME']);
  $currentDir = dirname($_SERVER['SCRIPT_NAME']);

  // str_contains hanya tersedia di PHP 8.0+
  // Ganti dengan strpos untuk PHP 5.6/7.0
  if (strpos($currentDir, '/pages') !== false) {
    return '../' . $path;
  } else {
    return $path;
  }
}
?>

<div class="bg-gray-800 text-white w-64 flex flex-col justify-between h-screen">
    <div>
        <div class="p-4 flex items-center space-x-3">
            <div class="bg-blue-500 p-2 rounded-lg">
                <i class="fas fa-shield-alt text-white"></i>
            </div>
            <span class="font-bold text-xl">AdminDasboard</span>
        </div>
        <div class="p-4 bg-gray-700 flex items-center space-x-3">
            <img src="<?php echo getPath('images/Logo1.jpg'); ?>" class="w-10 h-10 rounded-full object-cover" alt="Profile">
            <div>
                <div class="font-medium"><?php echo htmlspecialchars(isset($_SESSION['admin_name']) ? $_SESSION['admin_name'] : 'Admin'); ?></div>
            </div>
        </div>
        <nav class="p-4">
            <ul class="space-y-2">
                <li><a href="<?php echo getPath('dashboard.php'); ?>" class="flex items-center p-2 rounded-lg bg-blue-600">Dashboard</a></li>
                <li><a href="<?php echo getPath('pages/products.php'); ?>" class="flex items-center p-2 rounded-lg hover:bg-gray-700">Products</a></li>
                <li><a href="<?php echo getPath('pages/orders.php'); ?>" class="flex items-center p-2 rounded-lg hover:bg-gray-700">Orders</a></li>
                <li><a href="<?php echo getPath('pages/laporan.php'); ?>" class="flex items-center p-2 rounded-lg hover:bg-gray-700">Reports</a></li>
                <li><a href="<?php echo getPath('pages/settings.php'); ?>" class="flex items-center p-2 rounded-lg hover:bg-gray-700">Settings</a></li>
            </ul>
        </nav>
    </div>
    <div class="p-4">
        <a href="<?php echo getPath('pages/logout.php'); ?>" class="flex items-center p-2 rounded-lg hover:bg-gray-700">Logout</a>
    </div>
</div>
