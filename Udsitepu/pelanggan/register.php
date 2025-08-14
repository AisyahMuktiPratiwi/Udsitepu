<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'inc/db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $password2 = isset($_POST['password2']) ? $_POST['password2'] : '';

    if (!$name || !$email || !$password || !$password2) {
        $error = "Semua field harus diisi.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email tidak valid.";
    } elseif ($password !== $password2) {
        $error = "Password tidak cocok.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Email sudah terdaftar.";
        } else {
            $password_hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $password_hashed);
            if ($stmt->execute()) {
                $success = "Registrasi berhasil! <a href='login.php'>Login di sini</a>.";
            } else {
                $error = "Gagal registrasi: " . $stmt->error;
            }
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi Akun</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f2f2f2;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 420px;
            margin: 80px auto;
            background: #e8f5e9; /* FORM BERWARNA HIJAU */
            padding: 30px 25px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        h2 {
            text-align: center;
            color: #2e7d32;
            margin-bottom: 25px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #1b5e20;
        }
        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #a5d6a7;
            border-radius: 6px;
            margin-bottom: 15px;
            font-size: 14px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        button:hover {
            background-color: #388e3c;
        }
        .message {
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 6px;
            font-size: 14px;
        }
        .error {
            background-color: #ffebee;
            color: #d32f2f;
            border: 1px solid #ef9a9a;
        }
        .success {
            background-color: #e8f5e9;
            color: #2e7d32;
            border: 1px solid #a5d6a7;
        }
        a {
            color: #1b5e20;
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Form Registrasi</h2>

    <?php if ($error): ?>
        <div class="message error"><?= $error ?></div>
    <?php elseif ($success): ?>
        <div class="message success"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="name">Nama</label>
        <input type="text" id="name" name="name" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <label for="password2">Ulangi Password</label>
        <input type="password" id="password2" name="password2" required>

        <button type="submit">Daftar</button>
    </form>
</div>

</body>
</html>
