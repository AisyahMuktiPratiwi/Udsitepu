<?php
session_start();
header('Content-Type: application/json');
include 'inc/db.php';

if (!isset($_POST['id'])) {
  echo json_encode(['status' => 'error', 'message' => 'ID tidak ditemukan']);
  exit;
}

$id = (int) $_POST['id'];
$produk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id = $id"));

if (!$produk) {
  echo json_encode(['status' => 'error', 'message' => 'Produk tidak ditemukan']);
  exit;
}

$stok_tersedia = $produk['stock'];
$qty = isset($_SESSION['keranjang'][$id]) ? $_SESSION['keranjang'][$id] + 1 : 1;

if ($qty > $stok_tersedia) {
  echo json_encode(['status' => 'error', 'message' => 'Stok tidak mencukupi']);
  exit;
}

$_SESSION['keranjang'][$id] = $qty;

// Hitung total jumlah item di keranjang
$jumlah_total = 0;
foreach ($_SESSION['keranjang'] as $jumlah) {
  $jumlah_total += $jumlah;
}

echo json_encode(['status' => 'ok', 'jumlah' => $jumlah_total]);
?>
