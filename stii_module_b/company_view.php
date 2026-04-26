<?php
require 'config.php';
requireAdmin();
$id = $_GET['id'] ?? die('Missing ID');

$sql = $pdo->prepare("SELECT * FROM companies WHERE id = ?");
$sql->execute([$id]);
$company = $sql->fetch();
if (!$company) die('Company not found');

$sql = $pdo->prepare("SELECT * FROM products WHERE company_id = ? ORDER BY name");
$sql->execute([$id]);
$products = $sql->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Company: <?= h($company['company_name']) ?></title>
    <style>
        body { 
            font-family: Arial; 
            margin: 20px; 
        }
        table { 
            border-collapse: collapse; 
            width: 100%; 
            margin-top: 20px; 
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 8px; 
        }
        th { 
            background: #f2f2f2;
        }
        .nav a { 
            margin-right: 15px; 
        }
    </style>
</head>
<body>
    <div class="nav">
        <a href="/stii_module_b/companies">Back to Companies</a>
        <a href="/stii_module_b/products">All Products</a>
        <a href="/stii_module_b/logout">Logout</a>
    </div>
    <h2><?= h($company['company_name']) ?></h2>
    <p><strong>Address:</strong> <?= h($company['company_address']) ?></p>
    <p><strong>Tel:</strong> <?= h($company['company_telephone_number']) ?></p>
    <p><strong>Email:</strong> <?= h($company['company_email_address']) ?></p>
    <h3>Owner: <?= h($company['owner_name']) ?> (<?= h($company['owner_mobile_number']) ?>, <?= h($company['owner_email_address']) ?>)</h3>
    <h3>Contact: <?= h($company['contact_name']) ?> (<?= h($company['contact_mobile_number']) ?>, <?= h($company['contact_email_address']) ?>)</h3>

    <h3>Products of this company</h3>
    <?php if ($products): ?>
        <table>
            <tr><th>GTIN</th><th>Name (EN)</th><th>Name (FR)</th><th>Status</th><th>Actions</th></tr>
            <?php foreach ($products as $p): ?>
            <tr>
                <td><?= $p['gtin'] ?></td>
                <td><?= h($p['name']) ?></td>
                <td><?= h($p['name_fr']) ?></td>
                <td><?= $p['is_hidden'] ? 'Deactivated' : 'Active' ?></td>
                <td><a href="/stii_module_b/products/<?= $p['gtin'] ?>/edit">Edit</a></td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No products yet. <a href="/stii_module_b/products/new">Add one</a>.</p>
    <?php endif; ?>
</body>
</html>