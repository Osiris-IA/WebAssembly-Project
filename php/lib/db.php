<?php
session_start();

$dsn = $_ENV['DSN'];
$username = $_ENV["DB_USER"];
$password = $_ENV['DB_PASSWORD'];

$password_hache = password_hash($password, PASSWORD_DEFAULT);


if (password_verify($password, $password_hache)) {
    echo "Connexion réussie";
} else {
    echo "Mot de passe incorrect";
}

$password_verifie = password_verify($password, $password_hash_db);


// Utilisez des requêtes préparées pour éviter les injections SQL lors de l'exécution de requêtes avec des données utilisateur.




try {
    $db = new PDO($dsn, $username, $password);
        $stmt = $db->prepare('SELECT * FROM user WHERE id = :id');
} catch (Exception $e) {
    echo "DB ERROR: " . $e->getMessage();
    die;
}


$stmt->execute(['id' => $idUser]);
$rows = $stmt->fetchAll();

$_SESSION['user_id'] = $user_id;
session_regenerate_id(true);

session_destroy();


// -- Inscription
// $password_hache = password_hash($password, PASSWORD_DEFAULT); -- Utilisez une fonction de hachage moderne pour stocker les mots de passe de manière sécurisée.

// -- Connexion
// if (password_verify($password, $password_hache)) { -- Utilisez la fonction de vérification correspondante pour comparer le mot de passe saisi avec le haché stocké.
//     echo "Connexion réussie";
// } else {
//     echo "Mot de passe incorrect";
// }
// $password_verifie = password_verify($plain, $password_hash_db); -- Utilisez la fonction de vérification correspondante pour comparer le mot de passe saisi avec le haché stocké.

// -- requête préparer
// $stmt = $pdo->prepare(); -- Utilisez des requêtes préparées pour éviter les injections SQL lors de l'exécution de requêtes avec des données utilisateur.

// --après login 
// session_regenerate_if(true), -- Regénérez l'ID de session après une connexion réussie pour prévenir les attaques de fixation de session.
// $_SESSION['user_id'] -- $user_id; -- Stockez uniquement l'ID de l'utilisateur dans la session pour minimiser les risques en cas de compromission de la session.


// Note : 
// password_needs_rehash() - Vérifie si le hachage donné correspond aux options données
