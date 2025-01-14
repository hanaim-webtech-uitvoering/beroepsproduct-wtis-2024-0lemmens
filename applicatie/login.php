<?php
require_once 'db_connectie.php';
include 'header.php';
$db = maakVerbinding();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $sql = "SELECT username, password, role FROM [User] WHERE username=:u";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':u', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            echo "<p>Welkom, " . htmlspecialchars($user['username']) . " (rol: " . htmlspecialchars($user['role']) . ").</p>";
            echo "<p><a href='index.php'>Ga verder</a></p>";
            include 'footer.php';
            exit;
        } else {
            echo "<p>Onjuist wachtwoord.</p>";
        }
    } else {
        echo "<p>Onbekende gebruiker.</p>";
    }
}
?>
<h2>Inloggen</h2>
<form method="post">
  <label>Gebruikersnaam:</label>
  <input type="text" name="username" required>
  <label>Wachtwoord:</label>
  <input type="password" name="password" required>
  <button type="submit">Inloggen</button>
</form>
<?php include 'footer.php'; ?>
