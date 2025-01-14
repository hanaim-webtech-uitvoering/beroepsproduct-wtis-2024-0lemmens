<?php
require_once 'db_connectie.php';
include 'header.php';
$db = maakVerbinding();
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'add') {
        $productName = $_POST['product_name'] ?? '';
        $quantity = (int)($_POST['quantity'] ?? 1);
        if ($productName && $quantity > 0) {
            if (isset($_SESSION['cart'][$productName])) {
                $_SESSION['cart'][$productName] += $quantity;
            } else {
                $_SESSION['cart'][$productName] = $quantity;
            }
        }
    } elseif ($action === 'placeOrder') {
        $deliveryAddress = $_POST['address'] ?? '';
        if (!isset($_SESSION['username'])) {
            echo "<p>Inloggen vereist om te bestellen.</p>";
        } else {
            $client_username = $_SESSION['username'];
            $sqlUser = "SELECT first_name, last_name FROM [User] WHERE username = :u";
            $stmtUser = $db->prepare($sqlUser);
            $stmtUser->bindParam(':u', $client_username);
            $stmtUser->execute();
            $userRow = $stmtUser->fetch(PDO::FETCH_ASSOC);
            $client_name = $userRow ? ($userRow['first_name'] . ' ' . $userRow['last_name']) : $client_username;
            if ($_SESSION['role'] === 'staff') {
                $personnel_username = $_SESSION['username'];
            } else {
                $stmtStaff = $db->query("SELECT TOP 1 username FROM [User] WHERE role='Personnel'");
                $staffRow = $stmtStaff->fetch(PDO::FETCH_ASSOC);
                $personnel_username = $staffRow ? $staffRow['username'] : 'staff_not_found';
            }
            $sqlOrder = "INSERT INTO Pizza_Order (client_username, client_name, personnel_username, datetime, status, address) VALUES (:cu, :cn, :pu, GETDATE(), 0, :ad)";
            $stmtOrder = $db->prepare($sqlOrder);
            $stmtOrder->bindParam(':cu', $client_username);
            $stmtOrder->bindParam(':cn', $client_name);
            $stmtOrder->bindParam(':pu', $personnel_username);
            $stmtOrder->bindParam(':ad', $deliveryAddress);
            $stmtOrder->execute();
            $orderId = $db->lastInsertId();
            if (!$orderId) {
                $res = $db->query("SELECT SCOPE_IDENTITY() as oid");
                $row = $res->fetch();
                $orderId = $row['oid'];
            }
            foreach ($_SESSION['cart'] as $prodName => $qty) {
                $stmtProd = $db->prepare("INSERT INTO Pizza_Order_Product (order_id, product_name, quantity) VALUES (:o, :p, :q)");
                $stmtProd->bindValue(':o', $orderId);
                $stmtProd->bindValue(':p', $prodName);
                $stmtProd->bindValue(':q', $qty);
                $stmtProd->execute();
            }
            $_SESSION['cart'] = [];
            echo "<p>Bestelling #$orderId is geplaatst.</p>";
        }
    }
}
?>
<h2>Winkelmandje</h2>
<?php if (empty($_SESSION['cart'])): ?>
<p>Je winkelmandje is leeg.</p>
<?php else: ?>
<table>
  <tr><th>Product</th><th>Aantal</th></tr>
  <?php foreach ($_SESSION['cart'] as $prod => $qty): ?>
  <tr>
    <td><?php echo htmlspecialchars($prod); ?></td>
    <td><?php echo (int)$qty; ?></td>
  </tr>
  <?php endforeach; ?>
</table>
<?php endif; ?>
<?php if (isset($_SESSION['username'])): ?>
<form method="post">
  <input type="hidden" name="action" value="placeOrder">
  <label>Adres:</label>
  <input type="text" name="address" required>
  <button type="submit">Bestellen</button>
</form>
<?php else: ?>
<p><a href="login.php">Log in</a> of <a href="register.php">Registreer</a> om te bestellen.</p>
<?php endif; ?>
<?php include 'footer.php'; ?>
