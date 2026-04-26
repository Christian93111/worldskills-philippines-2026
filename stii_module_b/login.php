<?php
require 'config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['passphrase'] === 'admin') {
        $_SESSION['admin'] = true;
        header('Location: /stii_module_b/companies');
        exit;
    } else {
        $error = 'Invalid passphrase';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body { 
            font-family: Arial; 
            max-width: 400px; 
            margin: 50px auto; 
        }
        button { 
            padding: 8px; 
            margin: 5px 0; 
            width: 100%; 
        }

        input {
            width: 94%;
            padding: 10px;
        }
        .error { 
            color: red; 
        }

        h2 {
            text-align: center;
        }
    </style>
</head>
<body>
    <h2>Login</h2>
    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
    <form method="post">
        <input type="text" name="passphrase" placeholder="Enter Passphrase" required>
        <button type="submit">Login</button>
    </form>
</body>
</html>