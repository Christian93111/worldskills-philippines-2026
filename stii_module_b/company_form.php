<?php
require 'config.php';
requireAdmin();

$id = $_GET['id'] ?? null;
$deactivate = $_GET['deactivate'] ?? null;

if ($deactivate) {
    $pdo->prepare("UPDATE companies SET is_active = 0 WHERE id = ?")->execute([$deactivate]);
    $pdo->prepare("UPDATE products SET is_hidden = 1 WHERE company_id = ?")->execute([$deactivate]);
    header('Location: /stii_module_b/companies');
    exit;
}

$company = null;
if ($id) {
    $sql = $pdo->prepare("SELECT * FROM companies WHERE id = ?");
    $sql->execute([$id]);
    $company = $sql->fetch();
    if (!$company) {
        die('Company not found');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'company_name' => $_POST['company_name'],
        'company_address' => $_POST['company_address'],
        'company_telephone_number' => $_POST['company_telephone_number'],
        'company_email_address' => $_POST['company_email_address'],
        'owner_name' => $_POST['owner_name'],
        'owner_mobile_number' => $_POST['owner_mobile_number'],
        'owner_email_address' => $_POST['owner_email_address'],
        'contact_name' => $_POST['contact_name'],
        'contact_mobile_number' => $_POST['contact_mobile_number'],
        'contact_email_address' => $_POST['contact_email_address'],
    ];
    if ($id) {
        $sql = "UPDATE companies SET 
                company_name=:company_name, company_address=:company_address, company_telephone_number=:company_telephone_number,
                company_email_address=:company_email_address, owner_name=:owner_name, owner_mobile_number=:owner_mobile_number,
                owner_email_address=:owner_email_address, contact_name=:contact_name, contact_mobile_number=:contact_mobile_number,
                contact_email_address=:contact_email_address WHERE id=:id";
        $data['id'] = $id;
    } 
    
    else {
        $sql = "INSERT INTO companies 
                (company_name, company_address, company_telephone_number, company_email_address, owner_name, owner_mobile_number,
                owner_email_address, contact_name, contact_mobile_number, contact_email_address, is_active)
                VALUES (:company_name, :company_address, :company_telephone_number, :company_email_address, :owner_name,
                :owner_mobile_number, :owner_email_address, :contact_name, :contact_mobile_number, :contact_email_address, 1)";
    }
    $sql = $pdo->prepare($sql);
    $sql->execute($data);
    header('Location: /stii_module_b/companies');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $id ? 'Edit' : 'Add' ?> Company</title>
    <style>
        body { 
            font-family: Arial; 
            margin: 20px; 
        }
        form { 
            max-width: 600px;
        }
        label { 
            display: block; 
            margin-top: 10px; 
        }
        input, textarea { 
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
    <h2><?= $id ? 'Edit' : 'Add' ?> Company</h2>
    <form method="post">
        <label>Company Name: <input name="company_name" value="<?= h($company['company_name'] ?? '') ?>" required></label>
        <label>Address: <input name="company_address" value="<?= h($company['company_address'] ?? '') ?>" required></label>
        <label>Telephone: <input name="company_telephone_number" value="<?= h($company['company_telephone_number'] ?? '') ?>" required></label>
        <label>Email: <input type="email" name="company_email_address" value="<?= h($company['company_email_address'] ?? '') ?>" required></label>
        <fieldset>
            <legend>Owner</legend>
            <label>Name: <input name="owner_name" value="<?= h($company['owner_name'] ?? '') ?>" required></label>
            <label>Mobile: <input name="owner_mobile_number" value="<?= h($company['owner_mobile_number'] ?? '') ?>" required></label>
            <label>Email: <input type="email" name="owner_email_address" value="<?= h($company['owner_email_address'] ?? '') ?>" required></label>
        </fieldset>
        <fieldset>
            <legend>Contact</legend>
            <label>Name: <input name="contact_name" value="<?= h($company['contact_name'] ?? '') ?>" required></label>
            <label>Mobile: <input name="contact_mobile_number" value="<?= h($company['contact_mobile_number'] ?? '') ?>" required></label>
            <label>Email: <input type="email" name="contact_email_address" value="<?= h($company['contact_email_address'] ?? '') ?>" required></label>
        </fieldset>
        <button type="submit">Save</button>
        <a href="/stii_module_b/companies">Cancel</a>
    </form>
</body>
</html>