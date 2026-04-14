<?php
session_start();
require_once __DIR__  . '/../config/database.php';

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    die('Non autorisé');
}

// Récupérer les données JSON envoyées par JS
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['image_id']) || !isset($data['recette'])) {
    http_response_code(400);
    die('Données manquantes');
}

$imageId = $data['image_id'];
$recette = json_encode($data['recette']);

// Vérifier que l'image appartient à l'utilisateur connecté
$stmt = $pdo->prepare("SELECT id FROM Images WHERE id = ? AND user_id = ?");
$stmt->execute([$imageId, $_SESSION['user_id']]);
if (!$stmt->fetch()) {
    http_response_code(403);
    die('Accès refusé');
}

// Sauvegarder la recette
$stmt = $pdo->prepare("UPDATE Images SET recette = ? WHERE id = ?");
$stmt->execute([$recette, $imageId]);

echo json_encode(['success' => true]);
