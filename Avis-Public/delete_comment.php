<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || !isset($_POST['comment_id'])) {
    echo json_encode(['success' => false]); exit;
}

$host = 'localhost'; 
$dbname = 'maitre_houblon'; 
$db_user = 'root'; 
$db_pass = ''; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $db_user, $db_pass);
    
    // Vérifie que le commentaire appartient bien à l'utilisateur
    $stmt = $pdo->prepare("DELETE FROM comments WHERE id = ? AND user_id = ?");
    $stmt->execute([$_POST['comment_id'], $_SESSION['user_id']]);

    echo json_encode(['success' => $stmt->rowCount() > 0]);
} catch (Exception $e) {
    echo json_encode(['success' => false]);
}