<?php
require 'config.php';
requireAdmin();
$sql = $pdo->query("SELECT * FROM companies WHERE is_active = 0 ORDER BY company_name");
$companies = $sql->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Deactivated Companies</title>
    <style>
        body { 
            font-family: Arial; margin: 20px; 
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
        <a href="/stii_module_b/companies">Active Companies</a>
        <a href="/stii_module_b/products">Products</a>
        <a href="/stii_module_b/logout">Logout</a>
    </div>
    <h2>Deactivated Companies</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Address</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($companies as $c): ?>
        <tr>
            <td><?= $c['id'] ?></td>
            <td><?= h($c['company_name']) ?></td>
            <td><?= h($c['company_address']) ?></td>
            <td><a href="/stii_module_b/companies/<?= $c['id'] ?>">View</a></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>