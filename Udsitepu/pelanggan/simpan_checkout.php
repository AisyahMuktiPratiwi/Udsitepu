<?php
session_start();
include 'inc/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Perbarui qty berdasarkan input form
    foreach ($_POST['qty'] as $id => $qty) {
        $_SESSION['keranjang'][$id] = (int)$qty;
    }

    $total = 0;
    $keranjang = $_SESSION['keranjang'];

    // Hitung total dan simpan ke session checkout
    foreach ($keranjang as $id => $qty) {
        $produk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT price FROM products WHERE id = $id"));
        $total += $produk['price'] * $qty;
    }

    $_SESSION['checkout'] = [
        'nama'       => $_POST['nama'],
        'alamat'     => $_POST['alamat'],
        'pengiriman' => $_POST['pengiriman'],
        'bank'       => $_POST['bank'],
        'metode'     => $_POST['metode'],
        'keranjang'  => $keranjang,
        'qty'        => $_POST['qty'], // simpan input qty untuk jaga-jaga
        'total'      => $total
    ];

    // Set timer mulai sekarang (mis. 5 menit) — dipakai di upload_bukti.php
    $_SESSION['start_time'] = time();

    header("Location: upload_bukti.php");
    exit;
}
?>
