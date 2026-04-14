<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

try {
    require_once __DIR__  . '/auth.php';
    require_once __DIR__  . '/config/database.php';
    requireAuth();
    // ... reste du code
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
    exit();
}

// Récupérer le fichier via $_FILES

$file = $_FILES['photo'] ?? null;
if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
    die('Erreur lors du téléchargement du fichier');
}

// Vérifier le type MIME

$mime = mime_content_type($file['tmp_name']);
$allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
if (!in_array($mime, $allowedMimes)) {
    die('Type de fichier non autorisé');
}

// Renommer et déplacer le fichier
$extension = pathinfo($file['name'], PATHINFO_EXTENSION);
$newName = uniqid() . '.' . $extension;
$newPath = 'uploads/' . $newName;

if (!is_dir('uploads')) {
    mkdir('uploads', 0755, true);
}
if (!move_uploaded_file($file['tmp_name'], $newPath)) {
    die('Erreur lors du déplacement');
}

// Sauvegarder en base
$stmt = $pdo->prepare("INSERT INTO Images (album_id, user_id, chemin_fichier) 
                        VALUES (:album_id, :user_id, :chemin)");
$stmt->execute([
    'album_id' => $_POST['album_id'],
    'user_id'  => $_SESSION['user_id'],
    'chemin'   => 'uploads/' . $newName

]);

$imageId = $pdo->lastInsertId(); // ← récupère l'id généré
echo json_encode(['image_id' => $imageId]); // ← renvoie au JS
