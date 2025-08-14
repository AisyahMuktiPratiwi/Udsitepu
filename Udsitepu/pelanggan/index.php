<?php
include 'inc/header.php';
include 'inc/db.php'; // pastikan ini di sini kalau belum di header.php

$riwayat_query = $conn->query("SELECT * FROM orders ORDER BY created_at DESC LIMIT 5");
?>
<!-- Selamat Datang -->
<div class="card shadow-sm border-0 mx-4 mt-4">
  <div class="card-body text-center">
    <h2 class="mb-3 text-success fw-bold">🌾 Selamat Datang di Website UD. SITEPU 🌱</h2>
    <p class="mb-0 fs-5 text-muted">Tempatnya pupuk terbaik & terpercaya untuk petani cerdas! 🚜💚</p>
  </div>
</div>



<!-- Logo -->
<div class="text-center my-4">
  <img src="assets/img/Logo1.jpg" class="img-fluid rounded shadow" style="max-width: 900px;" alt="UD SITEPU Logo">
</div>

<!-- Produk Terlaris -->
<div class="container mt-4">
  <h4 class="mb-4 text-success fw-bold text-center">🔥 3 Produk Terlaris 🔥</h4>
  <div class="row justify-content-center">

    <!-- Produk Dummy #1 -->
    <div class="col-md-3 mb-4">
      <div class="card shadow-sm h-100">
        <img src="assets/img/npk 16.16.jpg" class="card-img-top" alt="NPK 16.16.16" style="height: 180px; object-fit: cover;">
        <div class="card-body text-center">
          <h5 class="card-title">NPK 16.16.16</h5>
          <p class="card-text text-success fw-bold">Rp 20.000 / kg</p>
          <a href="checkout.php?id=1" class="btn btn-warning btn-sm w-100 mb-1">Beli Sekarang</a>
          <a href="keranjang.php?id=1" class="btn btn-success btn-sm w-100">
            <i class="bi bi-cart-plus"></i> Keranjang
          </a>
        </div>
      </div>
    </div>

    <!-- Produk Dummy #2 -->
    <div class="col-md-3 mb-4">
      <div class="card shadow-sm h-100">
        <img src="assets/img/knock.jpg" class="card-img-top" alt="Knock" style="height: 180px; object-fit: cover;">
        <div class="card-body text-center">
          <h5 class="card-title">Knock</h5>
          <p class="card-text text-success fw-bold">Rp 35.000 / kg</p>
          <a href="checkout.php?id=2" class="btn btn-warning btn-sm w-100 mb-1">Beli Sekarang</a>
          <a href="keranjang.php?id=2" class="btn btn-success btn-sm w-100">
            <i class="bi bi-cart-plus"></i> Keranjang
          </a>
        </div>
      </div>
    </div>

    <!-- Produk Dummy #3 -->
    <div class="col-md-3 mb-4">
      <div class="card shadow-sm h-100">
        <img src="assets/img/lao ying.jpg" class="card-img-top" alt="Laoying" style="height: 180px; object-fit: cover;">
        <div class="card-body text-center">
          <h5 class="card-title">Laoying</h5>
          <p class="card-text text-success fw-bold">Rp 25.000 / kg</p>
          <a href="checkout.php?id=3" class="btn btn-warning btn-sm w-100 mb-1">Beli Sekarang</a>
          <a href="keranjang.php?id=3" class="btn btn-success btn-sm w-100">
            <i class="bi bi-cart-plus"></i> Keranjang
          </a>
        </div>
      </div>
    </div>