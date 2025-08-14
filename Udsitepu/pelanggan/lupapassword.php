<?php
include 'inc/db.php';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $new_password = isset($_POST['new_password']) ? $_POST['new_password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

    if (!$email || !$new_password || !$confirm_password) {
        $error = "Semua field harus diisi.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid.";
    } elseif ($new_password !== $confirm_password) {
        $error = "Password tidak cocok.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 0) {
            $error = "Email tidak ditemukan.";
        } else {
            $hashed = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
            $stmt->bind_param("ss", $hashed, $email);
            if ($stmt->execute()) {
                $success = "Password berhasil diubah. <a href='login.php'>Login sekarang</a>.";
            } else {
                $error = "Gagal mengubah password: " . $stmt->error;
            }
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Lupa Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f3f3;
        }
        .container {
            max-width: 400px;
            background: #e8f5e9;
            padding: 25px;
            margin: 80px auto;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        h2 { text-align: center; color: #2e7d32; }
        label { display: block; margin-top: 10px; color: #1b5e20; }
        .input-group {
            position: relative;
        }
        input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #a5d6a7;
            border-radius: 5px;
        }
        .toggle-password {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
            user-select: none;
        }
        button {
            margin-top: 20px;
            width: 100%;
            padding: 10px;
            background: #4caf50;
            color: white;
            border: none;
            border-radius: 5px;
        }
        .message {
            margin-top: 15px;
            padding: 10px;
            border-radius: 6px;
        }
        .error { background: #ffcdd2; color: #c62828; }
        .success { background: #c8e6c9; color: #2e7d32; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Lupa Password</h2>

        <?php if ($error): ?>
            <div class="message error"><?= $error ?></div>
        <?php elseif ($success): ?>
            <div class="message success"><?= $success ?></div>
        <?php endif; ?>

        <form method="POST">
            <label>Email</label>
            <input type="email" name="email" required>

            <label>Password Baru</label>
            <div class="input-group">
                <input type="password" name="new_password" id="new_password" required>
                <span class="toggle-password" onclick="togglePassword('new_password')">👁️</span>
            </div>

            <label>Ulangi Password Baru</label>
            <div class="input-group">
                <input type="password" name="confirm_password" id="confirm_password" required>
                <span class="toggle-password" onclick="togglePassword('confirm_password')">👁️</span>
            </div>

            <button type="submit">Reset Password</button>
        </form>
    </div>

    <script>
        function togglePassword(id) {
            const input = document.getElementById(id);
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
</body>
</html>
