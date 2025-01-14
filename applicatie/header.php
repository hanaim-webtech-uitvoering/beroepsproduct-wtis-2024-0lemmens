<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Pizzeria Sole Machina</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<nav>
  <ul>
    <li><a href="index.php">Home</a></li>
    <li><a href="menu.php">Menu</a></li>
    <li><a href="cart.php">Winkelmandje</a></li>
    <?php if (isset($_SESSION['username'])): ?>
      <li><a href="profile.php">Profiel</a></li>
      <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'staff'): ?>
        <li><a href="staff_orders.php">Bestellingen (Personeel)</a></li>
      <?php endif; ?>
      <li><a href="logout.php">Uitloggen</a></li>
    <?php else: ?>
      <li><a href="login.php">Inloggen</a></li>
      <li><a href="register.php">Registreren</a></li>
    <?php endif; ?>
    <li><a href="privacy.php">Privacy</a></li>
  </ul>
</nav>
<hr>
<div class="main-content">
