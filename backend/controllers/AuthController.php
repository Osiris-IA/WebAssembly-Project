<?php
session_start();
// require_once '../lib/db.php';
require_once __DIR__ . '/../models/users.php';

class AuthController
{
    private $userModel;

    public function __construct($pdo)
    {
        $this->userModel = new User($pdo);
    }


    function handleRegister()
    {
        // 1. Récupérer les données du formulaire ($_POST)
        $pseudo   = $_POST['pseudo'] ?? '';
        $email    = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        // 2. Valider (email valide ? mot de passe assez long ?)
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            die('Email invalide');
        }
        if (strlen($password) < 6) {
            die('Mot de passe trop court (min 6 caractères)');
        }
        if (strlen($pseudo) < 3) {
            die('Pseudo trop court (min 3 caractères)');
        }



        // 3. Appeler $user->register(...)
        $result = $this->userModel->register($pseudo, $email, $password);
        if (!$result) {
            die('Email ou pseudo déjà utilisé');
        }
        // 4. Rediriger vers la page de connexion

        header('Location: /views/login.php');
        exit();

    }


    function handleLogin()
    {
        // 1. Récupérer les données du formulaire ($_POST)
        $email    = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        // 2. Appeler $user->login(...)
        $user = $this->userModel->login($email, $password);

        // 3. Si succès → démarrer session + stocker user
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['pseudo'] = $user['pseudo'];

            // 4. Rediriger vers l'accueil
            header('Location: /index.html');
            exit();
        } else {
            die('Email ou mot de passe incorrect');
        }
    }
}
