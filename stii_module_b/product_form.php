<?php
require 'config.php';
requireAdmin();

$gtin = $_GET['gtin'] ?? null;
$product = null;
if ($gtin) {
    $sql = $pdo->prepare("SELECT * FROM products WHERE gtin = ?");
    $sql->execute([$gtin]);
    $product = $sql->fetch();
    if (!$product) die('Product not found');
}

$companies = $pdo->query("SELECT id, company_name FROM companies WHERE is_active = 1 ORDER BY company_name")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_gtin = $_POST['gtin'];
    if (!preg_match('/^\d{13,14}$/', $new_gtin)) {
        die('GTIN must be 13 or 14 digits.');
    }
    if (!$product || $product['gtin'] != $new_gtin) {
        $check = $pdo->prepare("SELECT id FROM products WHERE gtin = ?");
        $check->execute([$new_gtin]);
        if ($check->fetch()) die('GTIN already exists.');
    }

    $uploaded_image = $product['image_path'] ?? null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = 'product_' . time() . '.' . $ext;
        $dest = 'uploads/' . $filename;
        if (!is_dir('uploads')) mkdir('uploads', 0777, true);
        move_uploaded_file($_FILES['image']['tmp_name'], $dest);
        if ($product && $product['image_path'] && file_exists($product['image_path'])) {
            unlink($product['image_path']);
        }
        $uploaded_image = $dest;
    }

    $data = [
        'company_id' => $_POST['company_id'],
        'gtin' => $new_gtin,
        'name' => $_POST['name'],
        'name_fr' => $_POST['name_fr'],
        'description' => $_POST['description'],
        'description_fr' => $_POST['description_fr'],
        'brand_name' => $_POST['brand_name'],
        'country_origin' => $_POST['country_origin'],
        'gross_weight' => $_POST['gross_weight'] ?: null,
        'net_content_weight' => $_POST['net_content_weight'] ?: null,
        'weight_unit' => $_POST['weight_unit'],
        'image_path' => $uploaded_image,
        'is_hidden' => isset($_POST['is_hidden']) ? 1 : 0,
    ];

    if ($product) {
        $sql = "UPDATE products SET company_id=:company_id, gtin=:gtin, name=:name, name_fr=:name_fr,
                description=:description, description_fr=:description_fr, brand_name=:brand_name,
                country_origin=:country_origin, gross_weight=:gross_weight, net_content_weight=:net_content_weight,
                weight_unit=:weight_unit, image_path=:image_path, is_hidden=:is_hidden
                WHERE gtin=:old_gtin";
        $data['old_gtin'] = $product['gtin'];
    } else {
        $sql = "INSERT INTO products 
                (company_id, gtin, name, name_fr, description, description_fr, brand_name, country_origin,
                gross_weight, net_content_weight, weight_unit, image_path, is_hidden)
                VALUES (:company_id, :gtin, :name, :name_fr, :description, :description_fr, :brand_name,
                :country_origin, :gross_weight, :net_content_weight, :weight_unit, :image_path, :is_hidden)";
    }
    $sql = $pdo->prepare($sql);
    $sql->execute($data);
    header('Location: /stii_module_b/products');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $product ? 'Edit' : 'Add' ?> Product</title>
    <style>
        body { 
            font-family: Arial;
            margin: 20px;
        }
        form { 
            max-width: 700px; 
        }
        label { 
            display: block;
            margin-top: 10px; 
        }
        input, textarea, select {
            width: 100%;
            padding: 8px; 
        }
        button { 
            margin-top: 15px; 
            padding: 10px; 
        }
    </style>
</head>
<body>
    <h2><?= $product ? 'Edit' : 'Add' ?> Product</h2>
    <form method="post" enctype="multipart/form-data">
        <label>Company:
            <select name="company_id" required>
                <?php foreach ($companies as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= ($product && $product['company_id'] == $c['id']) ? 'selected' : '' ?>>
                        <?= h($c['company_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>GTIN (13-14 digits): <input name="gtin" value="<?= h($product['gtin'] ?? '') ?>" required pattern="\d{13,14}"></label>
        <label>Name (English): <input name="name" value="<?= h($product['name'] ?? '') ?>" required></label>
        <label>Name (French): <input name="name_fr" value="<?= h($product['name_fr'] ?? '') ?>" required></label>
        <label>Description (English): <textarea name="description" rows="3"><?= h($product['description'] ?? '') ?></textarea></label>
        <label>Description (French): <textarea name="description_fr" rows="3"><?= h($product['description_fr'] ?? '') ?></textarea></label>
        <label>Brand: <input name="brand_name" value="<?= h($product['brand_name'] ?? '') ?>" required></label>
        <label>Country of Origin: <input name="country_origin" value="<?= h($product['country_origin'] ?? '') ?>" required></label>
        <label>Gross Weight: <input type="number" step="0.01" name="gross_weight" value="<?= $product['gross_weight'] ?? '' ?>"></label>
        <label>Net Weight: <input type="number" step="0.01" name="net_content_weight" value="<?= $product['net_content_weight'] ?? '' ?>"></label>
        <label>Weight Unit:
            <select name="weight_unit">
                <option value="g" <?= ($product && $product['weight_unit'] == 'g') ? 'selected' : '' ?>>g</option>
                <option value="kg" <?= ($product && $product['weight_unit'] == 'kg') ? 'selected' : '' ?>>kg</option>
                <option value="L" <?= ($product && $product['weight_unit'] == 'L') ? 'selected' : '' ?>>L</option>
            </select>
        </label>
        <label>Image: <input type="file" name="image" accept="image/*"></label>
        <?php if ($product && $product['image_path']): ?>
            <p>Current: <img src="/stii_module_b/<?= $product['image_path'] ?>" height="50"></p>
        <?php endif; ?>
        <label><input type="checkbox" name="is_hidden" value="1" <?= ($product && $product['is_hidden']) ? 'checked' : '' ?>> Hidden</label>
        <button type="submit">Save</button>
        <a href="/stii_module_b/products">Cancel</a>
    </form>
</body>
</html>