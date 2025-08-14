<?php
$host = "localhost";
$user = "root";
$pass = ""; // default XAMPP (kosong)
$dbname = "udsitepu"; // ganti sesuai nama database kamu

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
?>
