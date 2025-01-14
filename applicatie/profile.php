<?php
require_once 'db_connectie.php';
include 'header.php';
if (!isset($_SESSION['username'])) {
    echo "<p>Log in om je bestellingen te zien.</p>";
    include 'footer.php';
    exit;
}
$db = maakVerbinding();
$client_username = $_SESSION['username'];
$sql = "SELECT order_id, datetime, status, address FROM Pizza_Order WHERE client_username = :c ORDER BY order_id DESC";
$stmt = $db->prepare($sql);
$stmt->bindParam(':c', $client_username);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<h2>Mijn bestellingen</h2>
<?php if (!$orders): ?>
<p>Geen bestellingen.</p>
<?php else: ?>
<table>
  <tr><th>Order</th><th>Datum/Tijd</th><th>Status</th><th>Adres</th></tr>
  <?php foreach ($orders as $o): ?>
  <tr>
    <td><a href="order_detail.php?order_id=<?php echo $o['order_id']; ?>"><?php echo $o['order_id']; ?></a></td>
    <td><?php echo $o['datetime']; ?></td>
    <td><?php echo $o['status']; ?></td>
    <td><?php echo htmlspecialchars($o['address'] ?? ''); ?></td>
  </tr>
  <?php endforeach; ?>
</table>
<?php endif; ?>
<?php include 'footer.php'; ?>
