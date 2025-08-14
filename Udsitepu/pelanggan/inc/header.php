<?php
// Cek apakah session belum dimulai, jika belum baru start
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

ob_start(); // Jika kamu butuh fungsi header() setelah output

// Hitung total item di keranjang
$jumlah_keranjang = 0;
if (!empty($_SESSION['keranjang'])) {
  foreach ($_SESSION['keranjang'] as $qty) {
    $jumlah_keranjang += $qty;
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no" />
  <title>UD. SITEPU</title>

  <!-- Bootstrap CSS -->
   <!-- Bootstrap Bundle dengan Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="style.css">

  <style>
    body {
      background-color: #f5f3f0;
    }

    .banner {
      width: 100%;
      max-width: 900px;
      margin: auto;
      background: #ffffff;
      box-shadow: 0 0 15px rgba(0,0,0,0.15);
      border-radius: 10px;
      overflow: hidden;
      user-select: none;
    }

    .top-image {
      height: 350px;
      background-color: #aed581;
      overflow: hidden;
    }

    .background-mountain {
      width: 100%;
      height: 100%;
      object-fit: cover;
      filter: brightness(0.9);
    }

    .content {
      padding: 20px;
      text-align: center;
    }

    .footer {
      background-color: #4caf50;
      color: white;
      padding: 10px;
      font-size: 1.2em;
      text-align: center;
    }

    .collage {
      display: flex;
      justify-content: space-around;
      margin-top: 20px;
      flex-wrap: wrap;
    }

    .collage img {
      width: 100px;
      height: auto;
      border-radius: 5px;
    }

    h1 {
      font-size: 2em;
      margin: 10px 0;
    }

    p {
      font-size: 1.2em;
    }

    .icon-bar a:hover {
      color: #ffcdd2;
      transform: scale(1.1);
      transition: 0.2s;
    }

    .banner {
      position: relative;
      width: 100vw;
      left: 50%;
      right: 50%;
      margin-left: -50vw;
      margin-right: -50vw;
      overflow: hidden;
    }

    .banner-img {
      width: 100%;
      height: auto;
      display: block;
      object-fit: cover;
    }
  </style>
</head>
<body>

<!-- NAVBAR UTAMA + KATEGORI -->
<nav class="navbar navbar-expand-lg navbar-dark bg-success">
  <div class="container-fluid px-3">

    <!-- Logo -->
    <a class="navbar-brand" href="#">UD. SITEPU</a>

    <!-- Tombol hamburger -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Isi navbar -->
    <div class="collapse navbar-collapse" id="mainNavbar">

      <!-- Link menu kategori -->
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link text-white" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="produk.php">Semua Produk</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="tentang.php">Tentang Kami</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="riwayat.php">Riwayat</a></li>
      </ul>

      <!-- Elemen kanan (pencarian + ikon) -->
      <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center gap-2 mt-3 mt-lg-0">

        <!-- Form pencarian -->
        <form class="d-flex w-100 w-lg-auto" role="search" action="cari.php" method="GET">
          <input class="form-control me-2" type="search" name="q" placeholder="Cari produk..." aria-label="Search">
          <button class="btn btn-outline-light" type="submit"><i class="fas fa-search"></i></button>
        </form>


        <!-- Ikon keranjang -->
        <a href="keranjang.php" class="text-white position-relative" title="Keranjang">
          <i class="fas fa-shopping-cart fa-lg"></i>
          <?php if ($jumlah_keranjang > 0): ?>
          <span id="jumlah-keranjang" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
  <?= $jumlah_keranjang ?>
</span>

          <?php endif; ?>
        </a>

        <!-- Login / Logout -->
        <?php if (isset($_SESSION['username'])): ?>
          <a href="logout.php" class="text-white" title="Logout">
            <i class="fas fa-sign-out-alt fa-lg"></i>
          </a>
        <?php else: ?>
          <a href="login.php" class="text-white" title="Login">
            <i class="fas fa-user fa-lg"></i>
          </a>
        <?php endif; ?>
      </div>

    </div>

  </div>
</nav>

<!-- Greeting jika login -->
<?php if (isset($_SESSION['username'])): ?>
  <div class="bg-light text-center py-3">
    <h5 class="text-success mb-0">🌿 Halo, <?= htmlspecialchars($_SESSION['username']) ?></h5>
  </div>
<?php endif; ?>

<!-- Konten mulai di sini -->
<div class="container mt-4">
