<?php
session_start();
include 'inc/db.php'; // File koneksi database, sesuaikan dengan file kamu

$error = '';

// Jika sudah login, redirect ke dashboard atau index
if (isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $remember = isset($_POST['remember']);

    // Query user berdasarkan kolom name (username)
    $stmt = $conn->prepare("SELECT * FROM users WHERE name = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verifikasi password (asumsi sudah di-hash pakai password_hash)
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['name'];
            $_SESSION['user_id'] = $user['id'];

            if ($remember) {
                setcookie('username', $user['name'], time() + (7 * 24 * 60 * 60), "/");
            } else {
                setcookie('username', '', time() - 3600, "/");
            }

            header('Location: index.php');
            exit;
        } else {
            $error = "Password salah.";
        }
    } else {
        $error = "Nama pengguna tidak ditemukan.";
    }

    $stmt->close();
}

$saved_username = isset($_COOKIE['username']) ? $_COOKIE['username'] : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Login - UD SITEPU</title>
<style>
  /* Gaya sama seperti sebelumnya, pakai warna hijau */
  body {
    font-family: Arial, sans-serif;
    background: #f0f8f1;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
  }
  .login-container {
    background: #e6f2e9;
    padding: 30px 40px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0,100,0,0.1);
    width: 320px;
  }
  h2 {
    font-weight: bold;
    margin-bottom: 20px;
    color: #2f7a2f;
  }
  label {
    font-weight: 600;
    display: block;
    margin: 10px 0 5px;
    color: #2f7a2f;
  }
  input[type=text], input[type=password] {
    width: 100%;
    padding: 8px 10px;
    margin-bottom: 10px;
    border: 1px solid #8bc34a;
    border-radius: 4px;
  }
  .checkbox-label {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
    color: #2f7a2f;
  }
  .checkbox-label input {
    margin-right: 8px;
  }
  button {
    background-color: #4caf50;
    border: none;
    color: white;
    padding: 10px;
    width: 100%;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
  }
  button:hover {
    background-color: #388e3c;
  }
  .links {
    margin-top: 15px;
    font-size: 14px;
    text-align: center;
  }
  .links a {
    color: #4caf50;
    text-decoration: none;
  }
  .links a:hover {
    text-decoration: underline;
  }
  .error {
    color: #d32f2f;
    margin-bottom: 10px;
    font-weight: 600;
  }
</style>
</head>
<body>

<div class="login-container">
  <h2>Silakan login terlebih dahulu</h2>

  <?php if ($error) : ?>
    <div class="error"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>

  <form method="POST" action="">
    <label for="username">Nama pengguna:</label>
    <input type="text" id="username" name="username" placeholder="Masukkan nama pengguna" required value="<?php echo htmlspecialchars($saved_username); ?>">

    <label for="password">Kata sandi:</label>
    <input type="password" id="password" name="password" placeholder="Masukkan kata sandi" required>

    <label class="checkbox-label">
      <input type="checkbox" name="remember" <?php echo ($saved_username) ? 'checked' : ''; ?>>
      Ingat saya
    </label>

    <button type="submit">Login</button>
  </form>

  <div class="links">
    <p><a href="lupapassword.php">Lupa kata sandi Anda?</a></p>
    <p>Belum punya akun? <a href="register.php">Daftar sekarang</a>.</p>
  </div>
</div>

</body>
</html>