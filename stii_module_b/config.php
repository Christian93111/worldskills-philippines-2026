<?php
session_start();

$host = 'localhost';
$db   = 'stii_module_b';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

function isAdmin() {
    return isset($_SESSION['admin']) && $_SESSION['admin'] === true;
}

function requireAdmin() {
    if (!isAdmin()) {
        header('HTTP/1.0 401 Unauthorized');
        die('Access denied. <a href="/stii_module_b/login">Login</a>');
    }
}

function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
?>