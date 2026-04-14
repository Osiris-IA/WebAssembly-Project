<?php
$host = $_ENV['DB_HOST'] ?? 'database'; // nom du service Docker !
$dbname = $_ENV['DB_NAME'] ?? 'app';
$user = $_ENV['DB_USER'] ?? 'root';
$password = $_ENV['DB_PASSWORD'] ?? 'password';

try {
    // 1. On crée la connexion avec un DSN (Data Source Name)
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8",
        $user,
        $password
    ); // comment compléter ?
    // Active les erreurs PDO pour débugger
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die('Erreur connexion : ' . $e->getMessage());
}
