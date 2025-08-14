<?php
include '../inc/db.php'; session_start();
$user_id = $_SESSION['user_id'];
$cart = mysqli_query($conn, "SELECT * FROM cart WHERE user_id=$user_id");
$total = 0;
while ($c = mysqli_fetch_assoc($cart)) {
  $prod = mysqli_fetch_assoc(mysqli_query($conn, "SELECT price FROM products WHERE id=".$c['product_id']));
  $total += $c['quantity'] * $prod['price'];
}
mysqli_query($conn, "INSERT INTO orders (user_id,total) VALUES ($user_id,$total)");
$order_id = mysqli_insert_id($conn);
mysqli_query($conn, "DELETE FROM cart WHERE user_id=$user_id");
header("Location: ../riwayat.php");
?>