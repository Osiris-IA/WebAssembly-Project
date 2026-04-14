<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/users.php';
require_once __DIR__ . '/../controllers/AuthController.php';

$auth = new AuthController($pdo);

// Si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth->handleRegister();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>WasmLightroom | Inscription</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet" />
    <style>
        body {
            background-color: #121212;
            color: white;
            font-family: "Inter", sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 24px;
        }

        .auth-card {
            background: #1e1e1e;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
            width: 100%;
            max-width: 360px;
            text-align: center;
        }

        h1 {
            margin: 0 0 30px;
            font-weight: 300;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 16px;
            background: #2b2b2b;
            border: 1px solid #333;
            border-radius: 8px;
            color: white;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #007aff;
            border: none;
            border-radius: 8px;
            color: white;
            font-weight: 600;
            cursor: pointer;
        }

        button:hover {
            background: #0063cc;
        }

        a {
            color: #888;
            text-decoration: none;
            font-size: 0.8rem;
            display: block;
            margin-top: 18px;
        }
    </style>
</head>

<body>
    <div class="auth-card">
        <h1>WasmLightroom</h1>
        <form method="POST">
            <input type="text" name="pseudo" placeholder="Pseudo" />
            <input type="email" name="email" placeholder="Email" />
            <input type="password" name="password" placeholder="Mot de passe" />
            <button type="submit">S'inscrire</button>
        </form>
        <a href="login.php">Déjà un compte ? Se connecter</a>
    </div>
</body>

</html>