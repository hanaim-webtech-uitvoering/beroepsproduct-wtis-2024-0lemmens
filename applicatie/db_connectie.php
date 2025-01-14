<?php
$db_host = 'database_server';
$db_name = 'pizzeria';
$db_user = 'sa';
$db_password = 'abc123!@#';

try {
    $verbinding = new PDO(
        "sqlsrv:Server=$db_host;Database=$db_name;ConnectionPooling=0;TrustServerCertificate=1",
        $db_user,
        $db_password
    );
    $verbinding->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Fout bij verbinding met database: " . $e->getMessage());
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function maakVerbinding() {
    global $verbinding;
    return $verbinding;
}
?>
