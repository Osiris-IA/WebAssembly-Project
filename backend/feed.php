<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/config/database.php';
requireAuth();

ini_set('display_errors', 0);

// Vérifier que l'utilisateur est connecté
header('Content-Type: application/json');

// Récupérer le paramètre de pagination depuis l'URL
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$userId = $_SESSION['user_id'];
// Exécuter la requête SQL
try {
    $stmt = $pdo->prepare("
    SELECT i.*, a.nom as album_nom
    FROM Images i
    JOIN Albums a ON i.album_id = a.id
    LEFT JOIN Album_shares s ON s.album_id = a.id AND s.user_id = :user_id
    WHERE
        a.visibilite = 'public'
        OR a.user_id = :user_id
        OR s.user_id IS NOT NULL
    ORDER BY i.date_upload DESC
    LIMIT :limit OFFSET :offset
");

    $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

    $stmt->execute();

    // Retourner le JSON
    $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($images);
} catch (PDOException $e) {
    // Si ça plante, on renvoie une erreur propre en JSON, pas du HTML
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
