<?php
$host = 'localhost';
$db   = 'desa_pinabetengan';  // nama database
$user = 'andro';              // user MariaDB Anda
$pass = 'kakidikepala';       // password Anda
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // Hanya untuk debugging — HAPUS nanti!
    die("❌ Database error: " . $e->getMessage());
}
?>