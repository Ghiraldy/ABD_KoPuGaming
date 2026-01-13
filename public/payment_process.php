<?php
require_once __DIR__ . '/../inc/db.php';

$id_pesanan = (int)($_POST['id_pesanan'] ?? 0);
if ($id_pesanan <= 0) {
    die("ID pesanan tidak valid.");
}

// Ambil total harga pesanan
$stmt = $pdo->prepare("SELECT total_harga FROM pesanan WHERE id_pesanan=?");
$stmt->execute([$id_pesanan]);
$pesanan = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pesanan) {
    die("Pesanan tidak ditemukan.");
}

// Simpan pembayaran (QRIS dianggap lunas langsung)
$stmt = $pdo->prepare("
    INSERT INTO pembayaran (id_pesanan, metode_pembayaran, jumlah_bayar, status_pembayaran)
    VALUES (?, 'E-Wallet', ?, 'Lunas')
");
$stmt->execute([$id_pesanan, $pesanan['total_harga']]);

// Update status pesanan menjadi selesai
$pdo->prepare("UPDATE pesanan SET status='Selesai' WHERE id_pesanan=?")
    ->execute([$id_pesanan]);

header("Location: checkout_success.php?id=$id_pesanan");
exit;
