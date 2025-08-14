<?php
session_start();
include '../db.php';

// Simulasi login admin (hapus di produksi)
if (!isset($_SESSION['admin_logged_in'])) {
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_name'] = "Admin";
}

// Tambah produk
if (isset($_POST['add'])) {
    $name  = mysqli_real_escape_string($conn, $_POST['name']);
    $price = (int) $_POST['price'];
    $stock = (int) $_POST['stock'];
    $imageName = '';

    if (!empty($_FILES['image']['name'])) {
        $imageName = time() . '_' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], "../images/" . $imageName);
    }

    mysqli_query($conn, "INSERT INTO products (name, price, stock, image) VALUES ('$name', '$price', '$stock', '$imageName')");
    header('Location: products.php');
    exit;
}

// Hapus produk
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $getImg = mysqli_query($conn, "SELECT image FROM products WHERE id=$id");
    if ($imgRow = mysqli_fetch_assoc($getImg)) {
        $imgPath = "../images/" . $imgRow['image'];
        if (!empty($imgRow['image']) && file_exists($imgPath)) {
            unlink($imgPath);
        }
    }
    mysqli_query($conn, "DELETE FROM products WHERE id=$id");
    header('Location: products.php');
    exit;
}

// Edit produk
if (isset($_POST['edit'])) {
    $id    = (int) $_POST['id'];
    $name  = mysqli_real_escape_string($conn, $_POST['name']);
    $price = (int) $_POST['price'];
    $stock = (int) $_POST['stock'];
    $imageName = $_POST['old_image'];

    if (!empty($_FILES['image']['name'])) {
        if (!empty($imageName) && file_exists("../images/" . $imageName)) {
            unlink("../images/" . $imageName);
        }
        $imageName = time() . '_' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], "../images/" . $imageName);
    }

    mysqli_query($conn, "UPDATE products SET name='$name', price='$price', stock='$stock', image='$imageName' WHERE id=$id");
    header('Location: products.php');
    exit;
}

$products = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Produk</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans">
    <div class="flex h-screen overflow-hidden">

        <!-- ✅ Sidebar -->
        <?php include '../inc/sidebar.php'; ?>

        <!-- ✅ Konten Utama -->
        <div class="flex-1 overflow-y-auto p-10">
            <h1 class="text-2xl font-bold mb-6">Daftar Produk</h1>

            <!-- Form Tambah Produk -->
            <form method="post" enctype="multipart/form-data" class="mb-8 bg-white p-6 rounded shadow-md">
                <h2 class="text-lg font-semibold mb-4">Tambah Produk</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input type="text" name="name" placeholder="Nama Produk" required class="border p-2 rounded">
                    <input type="number" name="price" placeholder="Harga" required class="border p-2 rounded">
                    <input type="number" name="stock" placeholder="Stok" required class="border p-2 rounded">
                    <input type="file" name="image" accept="image/*" class="border p-2 rounded">
                </div>
                <button name="add" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Tambah</button>
            </form>

            <!-- Tabel Produk -->
            <table class="min-w-full bg-white rounded shadow text-sm">
                <thead>
                    <tr>
                        <th class="px-4 py-3 border-b text-left">Gambar</th>
                        <th class="px-4 py-3 border-b text-left">ID</th>
                        <th class="px-4 py-3 border-b text-left">Nama Produk</th>
                        <th class="px-4 py-3 border-b text-left">Harga</th>
                        <th class="px-4 py-3 border-b text-left">Stok</th>
                        <th class="px-4 py-3 border-b text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($p = mysqli_fetch_assoc($products)): ?>
                    <tr class="border-t">
                        <td class="px-4 py-2">
                            <?php if (!empty($p['image'])): ?>
                                <img src="../images/<?php echo $p['image']; ?>" width="50" class="rounded">
                            <?php else: ?>
                                <span class="text-gray-400">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-2">P<?php echo str_pad($p['id'], 3, '0', STR_PAD_LEFT); ?></td>
                        <td class="px-4 py-2"><?php echo htmlspecialchars($p['name']); ?></td>
                        <td class="px-4 py-2">Rp <?php echo number_format($p['price'], 0, ',', '.'); ?></td>
                        <td class="px-4 py-2"><?php echo $p['stock']; ?></td>
                        <td class="px-4 py-2 space-x-2">
                            <a href="?delete=<?php echo $p['id']; ?>" class="text-red-500 hover:underline">Hapus</a>
                            <button onclick="fillEditForm(<?php echo htmlspecialchars(json_encode($p)); ?>)" class="text-blue-500 hover:underline">Edit</button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <!-- Modal Edit Produk -->
            <div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
                <div class="bg-white p-6 rounded shadow max-w-lg w-full">
                    <h2 class="text-lg font-semibold mb-4">Edit Produk</h2>
                    <form method="post" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="edit-id">
                        <input type="hidden" name="old_image" id="edit-old-image">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <input type="text" name="name" id="edit-name" required class="border p-2 rounded">
                            <input type="number" name="price" id="edit-price" required class="border p-2 rounded">
                            <input type="number" name="stock" id="edit-stock" required class="border p-2 rounded">
                            <input type="file" name="image" accept="image/*" class="border p-2 rounded">
                        </div>
                        <div class="flex justify-end gap-4 mt-4">
                            <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="text-gray-500">Batal</button>
                            <button name="edit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>

            <script>
                function fillEditForm(data) {
                    document.getElementById('edit-id').value = data.id;
                    document.getElementById('edit-name').value = data.name;
                    document.getElementById('edit-price').value = data.price;
                    document.getElementById('edit-stock').value = data.stock;
                    document.getElementById('edit-old-image').value = data.image;
                    document.getElementById('editModal').classList.remove('hidden');
                }
            </script>
        </div> <!-- End Content -->
    </div> <!-- End Wrapper -->
</body>
</html>
