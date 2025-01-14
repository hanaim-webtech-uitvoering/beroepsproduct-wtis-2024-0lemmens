<?php
require_once 'db_connectie.php';
include 'header.php';
if (!isset($_GET['order_id'])) {
    echo "<p>Geen order_id opgegeven.</p>";
    include 'footer.php';
    exit;
}
$orderId = (int)$_GET['order_id'];
$db = maakVerbinding();
$stmt = $db->prepare("SELECT order_id, datetime, status, address, client_username, client_name, personnel_username FROM Pizza_Order WHERE order_id=:o");
$stmt->bindParam(':o', $orderId);
$stmt->execute();
$order = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$order) {
    echo "<p>Bestelling niet gevonden.</p>";
    include 'footer.php';
    exit;
}
$stmtProd = $db->prepare("SELECT product_name, quantity FROM Pizza_Order_Product WHERE order_id=:o");
$stmtProd->bindParam(':o', $orderId);
$stmtProd->execute();
$orderProducts = $stmtProd->fetchAll(PDO::FETCH_ASSOC);
?>
<h2>Order #<?php echo $order['order_id']; ?></h2>
<p><strong>Klant username:</strong> <?php echo $order['client_username'] === null ? 'not given' : htmlspecialchars($order['client_username']); ?></p>
<p><strong>Klant naam:</strong> <?php echo htmlspecialchars($order['client_name'] ?? ''); ?></p>
<p><strong>Personeel username:</strong> <?php echo $order['personnel_username'] === null ? 'not given' : htmlspecialchars($order['personnel_username']); ?></p>
<p><strong>Datum/tijd:</strong> <?php echo $order['datetime']; ?></p>
<p><strong>Status:</strong> <?php echo $order['status']; ?></p>
<p><strong>Adres:</strong> <?php echo htmlspecialchars($order['address'] ?? ''); ?></p>
<h3>Producten</h3>
<table>
  <tr><th>Product</th><th>Aantal</th></tr>
  <?php foreach ($orderProducts as $op): ?>
  <tr>
    <td><?php echo htmlspecialchars($op['product_name'] ?? ''); ?></td>
    <td><?php echo (int)($op['quantity'] ?? 0); ?></td>
  </tr>
  <?php endforeach; ?>
</table>
<?php include 'footer.php'; ?>
