<?php
include '../inc/db.php'; session_start();
$nama = $_POST['nama'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$reg = mysqli_query($conn, "INSERT INTO users (name,email,password) VALUES('$nama','$email','$password')");
if ($reg) header("Location: ../login.php"); else echo "Gagal daftar";
?>