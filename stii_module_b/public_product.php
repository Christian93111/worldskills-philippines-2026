<?php
require 'config.php';

$gtin = $_GET['gtin'] ?? '';
if (!preg_match('/^\d{13,14}$/', $gtin)) {
    http_response_code(404);
    die('Invalid GTIN');
}

$sql = $pdo->prepare("SELECT p.*, c.company_name FROM products p JOIN companies c ON p.company_id = c.id WHERE p.gtin = ? AND p.is_hidden = 0");
$sql->execute([$gtin]);
$product = $sql->fetch();
if (!$product) {
    http_response_code(404);
    die('Product not found');
}

$lang = $_GET['lang'] ?? 'en';
if (!in_array($lang, ['en', 'fr'])) $lang = 'en';
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= h($lang === 'en' ? $product['name'] : $product['name_fr']) ?></title>
    <style>
        body { 
            font-family: Arial; 
            max-width: 600px; 
            margin: 20px auto; 
            padding: 0 15px; 
        }
        img { 
            max-width: 100%; 
            height: auto; 
        }
        .lang-switch { 
            margin-bottom: 20px;
            text-align: right;
        }
        .weight-info { 
            background: #f9f9f9; 
            padding: 10px; 
        }

        .company {
            margin-top: 50px;
            text-align: center;
        }

        .two {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="lang-switch">
        <a href="?lang=en">English</a> |
        <a href="?lang=fr">French</a>
    </div>
    <h1 class="company"><?= h($product['company_name']) ?></h1>
    <div>
        <?php if ($product['image_path'] && file_exists($product['image_path'])): ?>
            <img src="/stii_module_b/<?= $product['image_path'] ?>" alt="Product image">
        <?php else: ?>
            <img src="/stii_module_b/uploads/placeholder.jpg" alt="No image">
        <?php endif; ?>
    </div>
    <div class="two">
         <p><?= $product['gtin'] ?></p>
        <p><?= nl2br(h($lang === 'en' ? $product['description'] : $product['description_fr'])) ?></p>
    </div>
    <div class="weight-info">
        <p><strong>Weight:</strong> <?= $product['gross_weight'] ?><?= $product['weight_unit'] ?></p>
        <p><strong>Net Content Weight:</strong> <?= $product['net_content_weight'] ?><?= $product['weight_unit'] ?></p>
    </div>
</body>
</html>