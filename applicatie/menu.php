<?php
require_once 'db_connectie.php';
include 'header.php';
$db = maakVerbinding();
$stmt = $db->prepare("SELECT name, price FROM Product");
$stmt->execute();
$producten = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<h2>Menu</h2>
<?php foreach ($producten as $p): ?>
<div style="background:#fff; padding:15px; margin-bottom:10px;">
  <h3><?php echo htmlspecialchars($p['name']); ?></h3>
  <p>Prijs: €<?php echo number_format($p['price'], 2, ',', '.'); ?></p>
  <form method="post" action="cart.php">
    <input type="hidden" name="action" value="add">
    <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($p['name']); ?>">
    <label>Aantal:</label>
    <input type="number" name="quantity" value="1" min="1">
    <button type="submit">Toevoegen</button>
  </form>
</div>
<?php endforeach; ?>
<?php include 'footer.php'; ?>
