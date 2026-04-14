<?php
session_start();

$host = "localhost";
$username = "root";
$password = "";
$database = "test";
$message = "";

try {
    $connect = new PDO("mysql:host=$host;dbname=$database;charset=utf8mb4", $username, $password);
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_POST["login"])) {
        $pseudo = trim($_POST["username"] ?? "");
        $motDePasse = $_POST["password"] ?? "";

        if ($pseudo === "" || $motDePasse === "") {
            $message = '<script>sweetAlert("Champs manquants", "Pseudo et mot de passe requis.", "error");</script>';
        } else {
            $query = "SELECT id, pseudo, password FROM users WHERE pseudo = :pseudo LIMIT 1";
            $statement = $connect->prepare($query);
            $statement->execute([
                'pseudo' => $pseudo
            ]);

            $user = $statement->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($motDePasse, $user["password"])) {
                session_regenerate_id(true);
                $_SESSION["user_id"] = (int) $user["id"];
                $_SESSION["pseudo"] = $user["pseudo"];

                header("Location: user/dashboard.php");
                exit;
            }

            $message = '<script>sweetAlert("Connexion refusée", "Pseudo ou mot de passe incorrect.", "error");</script>';
        }
    }
} catch (PDOException $error) {
    $message = $error->getMessage();
}
