<?php
require 'config.php';
requireAdmin();

$sql = $pdo->query("SELECT p.*, c.company_name FROM products p JOIN companies c ON p.company_id = c.id ORDER BY p.name");
$products = $sql->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
    <style>
        body { 
            font-family: Arial; 
            margin: 20px; 
        }
        table { 
            border-collapse: collapse; 
            width: 100%; 
        }
        th, td { 
            border: 1px solid #ddd;
            padding: 8px; 
        }
        .nav a { 
            margin-right: 15px; 
        }
    </style>
</head>
<body>
    <div class="nav">
        <a href="/stii_module_b/companies">Companies</a>
        <a href="/stii_module_b/products">Products</a>
        <a href="/stii_module_b/logout">Logout</a>
    </div>
    <h2>Products</h2>
    <a href="/stii_module_b/products/new">Add New Product</a>
    <table>
        <tr>
            <th>GTIN</th>
            <th>Name (EN)</th>
            <th>Name (FR)</th>
            <th>Company</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($products as $p): ?>
        <tr>
            <td><?= $p['gtin'] ?></td>
            <td><?= h($p['name']) ?></td>
            <td><?= h($p['name_fr']) ?></td>
            <td><?= h($p['company_name']) ?></td>
            <td><?= $p['is_hidden'] ? 'Deactivate' : 'Active' ?></td>
            <td>
                <a href="/stii_module_b/products/<?= $p['gtin'] ?>/edit">Edit</a>
                <a href="/stii_module_b/products/<?= $p['gtin'] ?>">View</a>
                <?php if ($p['is_hidden']): ?>
                    <form method="POST" action="/stii_module_b/products/<?= $p['gtin'] ?>/delete" style="display:inline" onsubmit="return confirm('Permanently delete this product?')">
                        <button type="submit" style="background:none;border:none;color:blue;cursor:pointer;padding:0;font-size:inherit;">Delete</button>
                    </form>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>