<?php
session_start();

if (!isset($_SESSION['user_id'])) {
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

if (isset($_POST['id']) && isset($_POST['status'])) {
    $beer_id = intval($_POST['id']);
    $status = intval($_POST['status']);
    $user_id = $_SESSION['user_id'];

    $sql = "UPDATE beers_table SET is_public = ? WHERE id = ? AND user_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$status, $beer_id, $user_id]);
    echo "OK";
}