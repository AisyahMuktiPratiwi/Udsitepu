<?php
require_once __DIR__.'/../config.php';

// dipanggil di setiap halaman admin
function must_login() {
    if (empty($_SESSION['admin_id'])) {
        header("Location: /login.php");
        exit;
    }
}
?>