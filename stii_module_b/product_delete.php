<?php
require 'config.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Invalid request');
}

$gtin = $_GET['gtin'] ?? $_POST['gtin'] ?? die('Missing GTIN');

$stmt = $pdo->prepare("SELECT * FROM products WHERE gtin=?");
$stmt->execute([$gtin]);
$product = $stmt->fetch();

if (!$product) die('Not found');
if (!$product['is_hidden']) die('Only hidden products can be deleted');

if ($product['image_path']) {
    @unlink($product['image_path']);
}

$stmt = $pdo->prepare("DELETE FROM products WHERE gtin=?");
$stmt->execute([$gtin]);

header('Location: /stii_module_b/products');