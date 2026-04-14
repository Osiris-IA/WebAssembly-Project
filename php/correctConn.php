<?php
session_start();

$host = "localhost";
$username = "root";
$password = "";
$database = "test";
$message = "";

try {
    // Connexion à la base de données avec charset pour éviter les problèmes d'encodage
    $connect = new PDO("mysql:host=$host;dbname=$database;charset=utf8mb4", $username, $password);
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $connect->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

// Vérification de l'existence des colonnes nécessaires dans la table users
    $usersColumnsStmt = $connect->query("SHOW COLUMNS FROM users");
    $usersColumns = array_column($usersColumnsStmt->fetchAll(), 'Field');

    $loginColumn = in_array('username', $usersColumns, true) ? 'username' : (in_array('pseudo', $usersColumns, true) ? 'pseudo' : null);
    $passwordColumn = in_array('password_hash', $usersColumns, true) ? 'password_hash' : (in_array('password', $usersColumns, true) ? 'password' : null);
    $hasRoleColumn = in_array('role', $usersColumns, true);

    if ($loginColumn === null || $passwordColumn === null) {
        throw new RuntimeException("Colonnes manquantes dans users : username/pseudo et password/password_hash sont requises.");
    }

    if (isset($_POST["login"])) {
        $loginInput = trim($_POST["username"] ?? "");
        $plainPassword = $_POST["password"] ?? "";

        if ($loginInput === "" || $plainPassword === "") {
            $message = '
                <script>
                sweetAlert("Incorrect username or password!", "Please try again.", "error");
                </script>';
        } else {
            $roleSelect = $hasRoleColumn ? ", role" : "";
            $query = "SELECT id, {$loginColumn} AS login_name, {$passwordColumn} AS password_hash {$roleSelect} FROM users WHERE {$loginColumn} = :login LIMIT 1";
            $statement = $connect->prepare($query);
            $statement->execute([
                'login' => $loginInput
            ]);

            $user = $statement->fetch();

            if ($user && password_verify($plainPassword, $user["password_hash"])) {
                session_regenerate_id(true);
                $_SESSION["user_id"] = (int) $user["id"];
                $_SESSION["username"] = $user["login_name"];
                $_SESSION["role"] = $user["role"] ?? "user";

                if (($_SESSION["role"] ?? "user") === "admin") {
                    header("Location: admin/dashboard.php");
                    exit;
                }

                header("Location: user/dashboard.php");
                exit;
            }

            $message = '
                <script>
                sweetAlert("Incorrect username or password!", "Please try again.", "error");
                </script>';
        }
    }
} catch (PDOException $error) {
    $message = $error->getMessage();
}
