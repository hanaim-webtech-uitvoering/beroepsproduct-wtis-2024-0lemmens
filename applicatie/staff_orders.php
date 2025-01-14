<?php
require_once 'db_connectie.php';
include 'header.php';
if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'staff') {
    echo "<p>Geen toegang - alleen voor personeel.</p>";
    include 'footer.php';
    exit;
}
$db = maakVerbinding();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $orderId = (int)$_POST['order_id'];
    $newStatus = (int)$_POST['status'];
    $upd = $db->prepare("UPDATE Pizza_Order SET status=:s WHERE order_id=:o");
    $upd->bindParam(':s', $newStatus);
    $upd->bindParam(':o', $orderId);
    $upd->execute();
}
$stmt = $db->prepare("SELECT order_id, client_username, personnel_username, datetime, status, address FROM Pizza_Order ORDER BY order_id ASC");
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<h2>Overzicht van bestellingen (personeel)</h2>
<table>
  <tr>
    <th>Order ID</th>
    <th>Klant (client_username)</th>
    <th>Personeel (personnel_username)</th>
    <th>Datum/Tijd</th>
    <th>Status</th>
    <th>Adres</th>
    <th>Wijzig Status</th>
  </tr>
  <?php foreach ($orders as $o): ?>
  <tr>
    <td><a href="order_detail.php?order_id=<?php echo $o['order_id']; ?>"><?php echo $o['order_id']; ?></a></td>
    <td><?php echo ($o['client_username'] === null) ? 'not given' : htmlspecialchars($o['client_username']); ?></td>
    <td><?php echo ($o['personnel_username'] === null) ? 'not given' : htmlspecialchars($o['personnel_username']); ?></td>
    <td><?php echo $o['datetime']; ?></td>
    <td><?php echo $o['status']; ?></td>
    <td><?php echo htmlspecialchars($o['address'] ?? ''); ?></td>
    <td>
      <form method="post">
        <input type="hidden" name="order_id" value="<?php echo $o['order_id']; ?>">
        <select name="status">
          <option value="0" <?php if ($o['status'] == 0) echo 'selected'; ?>>Ontvangen</option>
          <option value="1" <?php if ($o['status'] == 1) echo 'selected'; ?>>In behandeling</option>
          <option value="2" <?php if ($o['status'] == 2) echo 'selected'; ?>>Onderweg</option>
          <option value="3" <?php if ($o['status'] == 3) echo 'selected'; ?>>Afgerond</option>
        </select>
        <button type="submit">Opslaan</button>
      </form>
    </td>
  </tr>
  <?php endforeach; ?>
</table>
<?php include 'footer.php'; ?>
