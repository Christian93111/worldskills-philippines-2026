<?php
require 'config.php';
requireAdmin();

$gtin = $_GET['gtin'] ?? die('Missing GTIN');

$sql = $pdo->prepare("
    SELECT p.*, c.company_name, c.is_active,
    (p.is_hidden OR c.is_active = 0) AS effective_hidden
    FROM products p
    JOIN companies c ON p.company_id = c.id
    WHERE p.gtin = ?
");
$sql->execute([$gtin]);
$product = $sql->fetch();

if (!$product) die('Product not found');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Product</title>
</head>
<body>

<h2>Product Details</h2>

<p><b>GTIN:</b> <?= h($product['gtin']) ?></p>
<p><b>Name:</b> <?= h($product['name']) ?></p>
<p><b>Name (FR):</b> <?= h($product['name_fr']) ?></p>
<p><b>Company:</b> <?= h($product['company_name']) ?></p>
<p><b>Status:</b> <?= $product['effective_hidden'] ? 'Hidden' : 'Active' ?></p>

<?php if ($product['image_path']): ?>
    <img src="/stii_module_b/<?= h($product['image_path']) ?>" width="150">
<?php endif; ?>

<br><br>

<a href="/stii_module_b/products/<?= h($product['gtin']) ?>/edit">Edit</a>
<a href="/stii_module_b/products">Back</a>

</body>
</html>