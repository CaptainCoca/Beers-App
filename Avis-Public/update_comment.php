<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Veuillez vous connecter !']);
    exit;
}

$user_id = $_SESSION['user_id'];
$host = 'localhost';
$dbname = 'maitre_houblon';
$db_user = 'root';
$db_pass = ''; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $content = trim($_POST['content']);
    if (empty($content)) {
        echo json_encode(['success' => false, 'message' => 'Le parchemin ne peut pas être vide.']);
        exit;
    }

    $stmt = $pdo->prepare("UPDATE comments SET content = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$content, $_POST['comment_id'], $_SESSION['user_id']]);

    echo json_encode(['success' => true, 'new_content' => htmlspecialchars($content)]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}