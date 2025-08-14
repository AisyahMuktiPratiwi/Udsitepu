<?php
session_start();
include '../db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../login.php");
    exit();
}

// Ambil data admin dari tabel 'admins'
$admin_id = 1; // Atur jika menggunakan $_SESSION['admin_id']
$query = mysqli_query($conn, "SELECT * FROM admins WHERE id = $admin_id")
    or die("Query admin gagal: " . mysqli_error($conn));

$admin = mysqli_fetch_assoc($query);
if (!$admin) {
    die("Data admin tidak ditemukan.");
}

// Simpan perubahan username
if (isset($_POST['save_username'])) {
    $new_username = $_POST['username'];
    $update = mysqli_query($conn, "UPDATE admins SET username = '$new_username' WHERE id = $admin_id");
    if ($update) {
        $_SESSION['admin_name'] = $new_username;
        echo "<script>alert('Username berhasil diperbarui!'); location.href='settings.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui username');</script>";
    }
}

// Simpan perubahan password
if (isset($_POST['change_password'])) {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password === $confirm_password) {
        $hashed = password_hash($new_password, PASSWORD_DEFAULT);
        $update = mysqli_query($conn, "UPDATE admins SET password = '$hashed' WHERE id = $admin_id");
        if ($update) {
            echo "<script>alert('Password berhasil diperbarui!'); location.href='settings.php';</script>";
        } else {
            echo "<script>alert('Gagal memperbarui password');</script>";
        }
    } else {
        echo "<script>alert('Password tidak cocok!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pengaturan Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans">
<div class="flex h-screen overflow-hidden">

    <!-- ✅ Sidebar -->
    <?php include '../inc/sidebar.php'; ?>

    <!-- ✅ Main Content -->
    <div class="flex-1 overflow-y-auto p-10">
        <h2 class="text-2xl font-bold text-gray-700 mb-6">⚙️ Pengaturan Admin</h2>

        <!-- Form Ubah Username -->
        <div class="bg-white rounded shadow p-6 mb-8 max-w-xl">
            <h3 class="text-lg font-semibold mb-4">Ubah Username</h3>
            <form method="POST">
                <div class="mb-4">
                    <label class="block mb-1 font-medium">Username Baru</label>
                    <input type="text" name="username" value="<?= htmlspecialchars($admin['username']) ?>" required class="w-full border px-4 py-2 rounded">
                </div>
                <button type="submit" name="save_username" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Simpan</button>
            </form>
        </div>

        <!-- Form Ubah Password -->
        <div class="bg-white rounded shadow p-6 max-w-xl">
            <h3 class="text-lg font-semibold mb-4">Ganti Password</h3>
            <form method="POST">
                <div class="mb-4">
                    <label class="block mb-1 font-medium">Password Baru</label>
                    <input type="password" name="new_password" required class="w-full border px-4 py-2 rounded">
                </div>
                <div class="mb-4">
                    <label class="block mb-1 font-medium">Konfirmasi Password</label>
                    <input type="password" name="confirm_password" required class="w-full border px-4 py-2 rounded">
                </div>
                <button type="submit" name="change_password" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Ganti Password</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
