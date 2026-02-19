<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_POST['bar_id'])) {
    header('Location: bar-list.php');
    exit();
}

$host = 'localhost';
$dbname = 'maitre_houblon';
$username_db = 'root';
$password_db = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username_db, $password_db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

if (isset($_POST['bar_id'])) {
    $bar_id = intval($_POST['bar_id']);
    $is_public = intval($_POST['is_public']);
    $user_id = $_SESSION['user_id'];

    $sql = "UPDATE bars_table SET is_public = :is_public WHERE id = :id AND user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([
        ':is_public' => $is_public,
        ':id'        => $bar_id,
        ':user_id'   => $user_id
    ]);

    if (isset($_POST['ajax'])) {
        echo ($result) ? "success" : "error_db";
        exit();
    }
}

header("Location: bar-list.php");
exit();