<?php
// Replace these values with your hosting provider's exact info
$host = '127.0.0.1'; // EXAMPLE only — use real host
$port = '3306';
$dbname = 'u718651490_basilan';
$username = 'u718651490_basilan';
$password = 'Eurichjoy24';

$dsn = "mysql:host=$host;port=$port;dbname=$dbname";

try {
    $db = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    error_log("DB Error: " . $e->getMessage());
    // Optionally redirect or silently fail
    exit;
}
?>
