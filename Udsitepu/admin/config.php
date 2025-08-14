<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$mysqli = new mysqli("localhost", "root", "", "Udsitepu");

if ($mysqli->connect_errno) {
    die("Gagal konek DB: " . $mysqli->connect_error);
}
?>
