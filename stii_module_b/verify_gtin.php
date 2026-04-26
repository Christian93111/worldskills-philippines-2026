<?php
require 'config.php';
$results = null;
$allValid = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['gtins'])) {
    $lines = explode("\n", trim($_POST['gtins']));
    $results = [];
    $allValid = true;
    foreach ($lines as $line) {
        $gtin = trim($line);
        if ($gtin === '') continue;
        $valid = preg_match('/^\d{13,14}$/', $gtin);
        if ($valid) {
            $stmt = $pdo->prepare("SELECT id FROM products WHERE gtin = ? AND is_hidden = 0");
            $stmt->execute([$gtin]);
            $valid = $stmt->fetch() ? true : false;
        }
        $results[] = ['gtin' => $gtin, 'valid' => $valid];
        if (!$valid) $allValid = false;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>GTIN Bulk Verification</title>
    <style>
        body { 
            font-family: Arial; 
            max-width: 600px; 
            margin: 30px auto; 
        }
        textarea { 
            width: 100%; 
            height: 150px; 
        }
        .valid { 
            color: green; 
        }
        .invalid { 
            color: red; 
        }
        .all-valid { 
            background: #d4edda;
             padding: 10px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h1>GTIN Bulk Verification</h1>
    <form method="post">
        <p>Enter GTINs (one per line):</p>
        <textarea name="gtins" placeholder="37900123458228&#10;37900123458345"></textarea>
        <button type="submit">Verify</button>
    </form>
    <?php if ($results !== null): ?>
        <?php if ($allValid): ?>
            <div class="all-valid">All GTINs are valid!</div>
        <?php endif; ?>
        <h2>Results:</h2>
        <ul>
            <?php foreach ($results as $r): ?>
                <li class="<?= $r['valid'] ? 'valid' : 'invalid' ?>">
                    <?= h($r['gtin']) ?>: <?= $r['valid'] ? 'Valid' : 'Invalid' ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</body>
</html>