<?php
include '../inc/db.php'; session_start();
$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'];
$jumlah = $_POST['jumlah'];
$q = mysqli_query($conn, "INSERT INTO cart (user_id, product_id, quantity) VALUES ($user_id, $product_id, $jumlah)");
header("Location: ../keranjang.php");
?>