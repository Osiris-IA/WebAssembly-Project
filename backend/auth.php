<?php
function requireAuth() {
    if (session_status() === PHP_SESSION_NONE) session_start();

    if (!isset($_SESSION['user_id'])) {
        // Si c'est une requête Fetch/AJAX (JSON attendu)
        if (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
            http_response_code(401); // Non autorisé
            echo json_encode(['error' => 'Authentification requise']);
            exit;
        }

        // Sinon, redirection classique vers le login
        header('Location: login.php');
        exit;
    }
}