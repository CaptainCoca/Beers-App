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

    if (isset($_POST['beer_id'])) {
        $beer_id = (int)$_POST['beer_id'];

        // Vérifie l'existence du like
        $stmt = $pdo->prepare("SELECT id FROM likes_history WHERE user_id = ? AND beer_id = ?");
        $stmt->execute([$user_id, $beer_id]);
        $like_exists = $stmt->fetch();

        if (!$like_exists) {
            // AJOUT
            $pdo->prepare("INSERT INTO likes_history (user_id, beer_id) VALUES (?, ?)")->execute([$user_id, $beer_id]);
            $pdo->prepare("UPDATE beers_table SET likes = likes + 1 WHERE id = ?")->execute([$beer_id]);
            $status = 'added';
        } else {
            // RETRAIT
            $pdo->prepare("DELETE FROM likes_history WHERE user_id = ? AND beer_id = ?")->execute([$user_id, $beer_id]);
            $pdo->prepare("UPDATE beers_table SET likes = CASE WHEN likes > 0 THEN likes - 1 ELSE 0 END WHERE id = ?")->execute([$beer_id]);
            $status = 'removed';
        }

        // Retour du nouveau compte
        $res = $pdo->prepare("SELECT likes FROM beers_table WHERE id = ?");
        $res->execute([$beer_id]);
        
        echo json_encode([
            'success' => true, 
            'new_count' => (int)$res->fetchColumn(), 
            'status' => $status
        ]);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur système']);
}