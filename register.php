<?php
require_once __DIR__ . '/config/database.php';

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (strlen($password) < 6) {
        $error = "Mot de passe trop court (min 6 caractères)";
    } else {

        $stmt = $pdo->prepare("SELECT id FROM utilisateur WHERE username = ?");
        $stmt->execute([$username]);

        if ($stmt->fetch()) {
            $error = "Nom d'utilisateur déjà utilisé";
        } else {

            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare(
                "INSERT INTO utilisateur (username, password, role)
                 VALUES (?, ?, 'admin')"
            );
            $stmt->execute([$username, $hash]);

            $success = "Compte créé avec succès";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription | GStock</title>
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="public/css/style.css">
</head>
<style>
    /* RESET */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI', Tahoma, sans-serif;
    }

    /* BODY */
    .login-body {
        min-height: 100vh;
        background: linear-gradient(135deg, #2563eb, #1e3a8a);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* CONTAINER */
    .login-container {
        width: 100%;
        max-width: 380px;
        background: #ffffff;
        padding: 35px 30px;
        border-radius: 14px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        animation: fadeIn 0.6s ease;
    }

    /* TITLE */
    .login-container h2 {
        text-align: center;
        margin-bottom: 25px;
        color: #1e293b;
        font-size: 24px;
    }

    .login-container h2 i {
        color: #2563eb;
        margin-right: 6px;
    }

    /* INPUT BOX */
    .input-box {
        position: relative;
        margin-bottom: 18px;
    }

    .input-box i {
        position: absolute;
        top: 50%;
        left: 14px;
        transform: translateY(-50%);
        font-size: 18px;
        color: #64748b;
    }

    .input-box input {
        width: 100%;
        padding: 12px 14px 12px 42px;
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        font-size: 15px;
        transition: 0.3s;
    }

    .input-box input:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.15);
    }

    /* BUTTON */
    button {
        width: 100%;
        padding: 12px;
        background: #2563eb;
        border: none;
        border-radius: 8px;
        color: #fff;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.3s;
    }

    button:hover {
        background: #1e40af;
    }

    /* ERROR */
    .error {
        background: #fee2e2;
        color: #991b1b;
        padding: 10px;
        border-radius: 8px;
        margin-bottom: 15px;
        font-size: 14px;
        text-align: center;
    }

    /* HINT */
    .hint {
        margin-top: 18px;
        font-size: 13px;
        text-align: center;
        color: #475569;
    }

    /* ANIMATION */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(15px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
<body class="login-body">

<div class="login-container">
    <h2><i class="bx bx-user-plus"></i> Inscription</h2>

    <?php if ($error): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="success"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="input-box">
            <i class="bx bx-user"></i>
            <input type="text" name="username" placeholder="Nom d'utilisateur" required>
        </div>

        <div class="input-box">
            <i class="bx bx-lock-alt"></i>
            <input type="password" name="password" placeholder="Mot de passe" required>
        </div>

        <button type="submit">Créer le compte</button>
    </form>

    <p class="hint">
        <a href="login.php">← Retour à la connexion</a>
    </p>
</div>

</body>
</html>
