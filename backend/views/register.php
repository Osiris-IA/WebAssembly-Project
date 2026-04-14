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
<html>

<body>
    <h1>Inscription</h1>
    <form method="POST">
        <input type="text" name="pseudo" placeholder="Pseudo" />
        <input type="email" name="email" placeholder="Email" />
        <input type="password" name="password" placeholder="Mot de passe" />
        <button type="submit">S'inscrire</button>
    </form>
</body>

</html>