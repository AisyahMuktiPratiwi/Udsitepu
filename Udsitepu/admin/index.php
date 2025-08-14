<?php
require_once 'config.php';
require_once 'inc/auth.php';
must_login();          // blokir jika belum login

$page = $_GET['page'] ?? 'dashboard';
$allowed = [
    'dashboard','products','orders','payments','shipping','reports'
];
if (!in_array($page,$allowed)) $page='dashboard';

include 'inc/header.php';
include "pages/$page.php";
include 'inc/footer.php';
