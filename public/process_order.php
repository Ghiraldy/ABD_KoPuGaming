<?php
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/functions.php';

$cart = $_SESSION['cart'] ?? [];
if (!$cart) {
    die("Keranjang kosong.");
}

// Data dari form checkout
$nama = trim($_POST['nama'] ?? '');
$alamat = trim($_POST['alamat'] ?? '');
$telepon = trim($_POST['telepon'] ?? '');
$email = $telepon . "@guest.local"; // diperlukan karena tabel pelanggan punya kolom email unique

if (!$nama || !$alamat || !$telepon) {
    die("Data checkout tidak lengkap.");
}

// ==================== INSERT KE TABEL PELANGGAN ====================
// Cek apakah pelanggan sudah pernah checkout berdasarkan nomor HP
$cek = $pdo->prepare("SELECT id_pelanggan FROM pelanggan WHERE no_hp = ?");
$cek->execute([$telepon]);
$existing = $cek->fetchColumn();

if ($existing) {
    // Pakai pelanggan yang sudah ada
    $id_pelanggan = $existing;

    // Update nama & alamat jika diubah
    $upd = $pdo->prepare("UPDATE pelanggan SET nama_pelanggan=?, alamat=? WHERE id_pelanggan=?");
    $upd->execute([$nama, $alamat, $id_pelanggan]);

} else {
    // Buat email dummy (harus unik)
    $email = $telepon . "@guest.local";

    // Tambah pelanggan baru
    $stmt = $pdo->prepare("INSERT INTO pelanggan(nama_pelanggan, email, no_hp, alamat) VALUES (?,?,?,?)");
    $stmt->execute([$nama, $email, $telepon, $alamat]);
    $id_pelanggan = $pdo->lastInsertId();
}

// Hitung total harga pesanan
$total = 0;
foreach ($cart as $id => $qty) {
    $p = $pdo->query("SELECT harga FROM produk WHERE id_produk=$id")->fetch();
    $total += $p['harga'] * $qty;
}

// ==================== INSERT KE TABEL PESANAN ====================
$stmt = $pdo->prepare("INSERT INTO pesanan(id_pelanggan, total_harga) VALUES (?,?)");
$stmt->execute([$id_pelanggan, $total]);
$id_pesanan = $pdo->lastInsertId();

// ==================== INSERT KE TABEL DETAIL_PESANAN ====================
$stmt = $pdo->prepare("INSERT INTO detail_pesanan(id_pesanan, id_produk, jumlah, harga_satuan) VALUES (?,?,?,?)");

foreach ($cart as $id_produk => $qty) {
    $p = $pdo->query("SELECT harga FROM produk WHERE id_produk=$id_produk")->fetch();
    $stmt->execute([$id_pesanan, $id_produk, $qty, $p['harga']]);

    // Kurangi stok produk
    $pdo->query("UPDATE produk SET stok = stok - $qty WHERE id_produk=$id_produk");
}

// Bersihkan keranjang
unset($_SESSION['cart']);

// Redirect ke halaman sukses
header("Location: checkout_success.php?id=$id_pesanan");
exit;
