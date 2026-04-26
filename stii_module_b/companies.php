<?php
require 'config.php';
requireAdmin();

$sql = $pdo->query("SELECT * FROM companies WHERE is_active = 1 ORDER BY id ASC");
$companies = $sql->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Companies</title>
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
        th { 
            background: #f2f2f2; 
        }
        .actions a { 
            margin-right: 10px; 
        }
        .nav { 
            margin-bottom: 20px; 
        }
        .nav a { 
            margin-right: 15px; 
        }
    </style>
</head>
<body>
    <div class="nav">
        <a href="/stii_module_b/companies/deactivated">Deactivated Companies</a>
        <a href="/stii_module_b/products">Products</a>
        <a href="/stii_module_b/logout">Logout</a>
    </div>
    <h2>Companies</h2>
    <a href="/stii_module_b/companies/new">Add New Company</a>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Address</th>
            <th>Telephone</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($companies as $c): ?>
        <tr>
            <td><?= $c['id'] ?></td>
            <td><?= h($c['company_name']) ?></td>
            <td><?= h($c['company_address']) ?></td>
            <td><?= h($c['company_telephone_number']) ?></td>
            <td><?= h($c['company_email_address']) ?></td>
            <td class="actions">
                <a href="/stii_module_b/companies/<?= $c['id'] ?>">View</a>
                <a href="/stii_module_b/companies/<?= $c['id'] ?>/edit">Edit</a>
                <a href="/stii_module_b/companies/<?= $c['id'] ?>/deactivate" onclick="return confirm('Deactivate this company? All its products will be hidden.')">Deactivate</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>