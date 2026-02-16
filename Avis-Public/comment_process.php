<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Connectez-vous pour commenter.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$host = 'localhost';
$dbname = 'maitre_houblon';
$db_user = 'root';
$db_pass = ''; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $db_user, $db_pass);
    
    if (isset($_POST['beer_id']) && isset($_POST['content'])) {
        $beer_id = (int)$_POST['beer_id'];
        $content = trim($_POST['content']);

        if (empty($content)) {
            echo json_encode(['success' => false, 'message' => 'Le parchemin est vide !']);
            exit;
        }

        $stmt = $pdo->prepare("INSERT INTO comments (beer_id, user_id, content) VALUES (?, ?, ?)");
        $stmt->execute([$beer_id, $user_id, $content]);

        // Rrenvoie le pseudo pour l'afficher direct en JS
        $stmtUser = $pdo->prepare("SELECT pseudo FROM users WHERE id = ?");
        $stmtUser->execute([$user_id]);
        $pseudo = $stmtUser->fetchColumn();

        echo json_encode([
            'success' => true, 
            'pseudo' => $pseudo,
            'content' => htmlspecialchars($content)
        ]);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur : ' . $e->getMessage()]);
}