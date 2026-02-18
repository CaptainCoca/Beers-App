<?php
session_start();

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

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];

    // 1. On récupère le nom de l'image pour la supprimer du dossier uploads
    $query = $pdo->prepare("SELECT image_path FROM bars_table WHERE id = ?");
    $query->execute([$id]);
    $bar = $query->fetch();

    if ($bar && $bar['image_path'] != 'default.png') {
        $file = "../page-bar-list\uploadsBars/" . $bar['image_path'];
        if (file_exists($file)) {
            unlink($file); // Supprime le fichier physique
        }
    }

    // 2. On supprime l'entrée dans la base de données
    $delete = $pdo->prepare("DELETE FROM bars_table WHERE id = ?");
    $delete->execute([$id]);

    header('Location: bar-list.php?deleted=1');
    exit();
}