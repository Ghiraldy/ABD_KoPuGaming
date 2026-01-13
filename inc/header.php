<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Gaming Store</title>

  <!-- Pakai path RELATIF ke folder /public -->
  <link rel="stylesheet" href="css/style.css?v=5">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<header>
  <div class="container header-flex">
    <h1><a href="index.php">Gaming Store</a></h1>
    <nav>
      <a href="index.php">Beranda</a>
      <a href="cart.php"><i class="fa-solid fa-cart-shopping"></i> Keranjang (<?php echo cart_count(); ?>)</a>
    </nav>
  </div>
</header>

<main class="page-content">
