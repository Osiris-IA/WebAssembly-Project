<?php
class User
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function register($pseudo, $email, $password)
    {
        // 1. Hasher le mot de passe
        $password_hache = password_hash($password, PASSWORD_BCRYPT);

        // 2. Insérer en base
        try {
            $stmt = $this->pdo->prepare(
                "INSERT INTO Users (pseudo, email, password_hash) 
             VALUES (:pseudo, :email, :hash)"
            );
            $stmt->execute([
                'pseudo' => $pseudo,
                'email'  => $email,
                'hash'   => $password_hache
            ]);
            return true;
        } catch (PDOException $e) {
            // Email ou pseudo déjà utilisé
            return false;
        }
    }

    public function login($email, $password)
    {
        // Chercher le user par email en base
        $stmt = $this->pdo->prepare("SELECT * FROM Users WHERE email=?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // Vérifier le mot de passe avec password_verify
        if ($user && password_verify($password, $user['password_hash'])) {
            return $user;
        }

        // Retourner le user si OK, false sinon 
        return false;
    }
}
