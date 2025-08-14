<?php
session_start();

if (isset($_GET['id'])) {
  $id = $_GET['id'];
  
  // Hapus item dari keranjang jika ada
  if (isset($_SESSION['keranjang'][$id])) {
    unset($_SESSION['keranjang'][$id]);
  }
}

// Setelah dihapus, redirect kembali ke halaman keranjang
header('Location: keranjang.php');
exit;
