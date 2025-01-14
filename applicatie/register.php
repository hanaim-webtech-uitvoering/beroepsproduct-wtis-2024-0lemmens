<?php
require_once 'db_connectie.php';
include 'header.php';
$db = maakVerbinding();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $firstName = $_POST['first_name'] ?? '';
    $lastName = $_POST['last_name'] ?? '';
    $role = $_POST['role'] ?? 'client';
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $checkSql = "SELECT username FROM [User] WHERE username=:u";
    $stmt = $db->prepare($checkSql);
    $stmt->bindParam(':u', $username);
    $stmt->execute();
    if ($stmt->fetch()) {
        echo "<p>Gebruikersnaam bestaat al.</p>";
    } else {
        $sql = "INSERT INTO [User] (username, password, first_name, last_name, role) VALUES (:u, :p, :f, :l, :r)";
        $ins = $db->prepare($sql);
        $ins->bindParam(':u', $username);
        $ins->bindParam(':p', $hashedPassword);
        $ins->bindParam(':f', $firstName);
        $ins->bindParam(':l', $lastName);
        $ins->bindParam(':r', $role);
        $ins->execute();
        echo "<p>Registratie geslaagd. <a href='login.php'>Inloggen</a></p>";
    }
}
?>
<h2>Registreren</h2>
<form method="post">
  <label>Gebruikersnaam:</label>
  <input type="text" name="username" required>
  <label>Wachtwoord:</label>
  <input type="password" name="password" required>
  <label>Voornaam:</label>
  <input type="text" name="first_name" required>
  <label>Achternaam:</label>
  <input type="text" name="last_name" required>
  <label>Rol:</label>
  <select name="role">
    <option value="client">Klant</option>
    <option value="staff">Personeel</option>
  </select>
  <button type="submit">Registreer</button>
</form>
<?php include 'footer.php'; ?>
