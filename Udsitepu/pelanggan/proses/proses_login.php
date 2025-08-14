<?php
include '../inc/db.php'; session_start();
$email = $_POST['email'];
$password = $_POST['password'];
$q = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
$u = mysqli_fetch_assoc($q);
if ($u && password_verify($password, $u['password'])) {
  $_SESSION['user_id'] = $u['id'];
  $_SESSION['nama'] = $u['name'];
  header("Location: ../index.php");
} else {
  echo "<script>alert('Login gagal'); window.location='../login.php';</script>";
}
?>