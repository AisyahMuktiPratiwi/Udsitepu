<?php
session_start();
date_default_timezone_set('Asia/Jakarta');
include 'inc/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Safety: pastikan keranjang ada
    if (!isset($_SESSION['keranjang']) || empty($_SESSION['keranjang'])) {
        $_SESSION['keranjang'] = [];
    }

    // Sync qty dari form (kalau ada)
    if (isset($_POST['qty']) && is_array($_POST['qty'])) {
        foreach ($_POST['qty'] as $id => $qty) {
            $_SESSION['keranjang'][(int)$id] = max(0, (int)$qty);
        }
    }

    // Hitung total
    $total = 0;
    $keranjang = $_SESSION['keranjang'];
    foreach ($keranjang as $id => $qty) {
        $id = (int)$id;
        $qty = (int)$qty;
        if ($qty <= 0) continue;
        $res = mysqli_query($conn, "SELECT price FROM products WHERE id = $id");
        if ($row = mysqli_fetch_assoc($res)) {
            $total += ((int)$row['price']) * $qty;
        }
    }

    // Simpan data checkout ke SESSION (belum ke DB!)
    $_SESSION['checkout'] = [
        'nama'       => $_POST['nama']       ?? '',
        'alamat'     => $_POST['alamat']     ?? '',
        'pengiriman' => $_POST['pengiriman'] ?? 'Diantar',
        'bank'       => $_POST['bank']       ?? 'Mandiri',
        'metode'     => $_POST['metode']     ?? 'Transfer Bank',
        'keranjang'  => $keranjang,
        'qty'        => $_POST['qty'] ?? [], // optional
        'total'      => (int)$total
    ];

    // Set start time untuk timer 60 menit
    $_SESSION['start_time'] = time();

    // Arahkan ke halaman upload bukti
    header("Location: upload_bukti.php");
    exit;
}

// Kalau akses langsung tanpa POST
header("Location: keranjang.php");
exit;
