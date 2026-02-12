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

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    // 1. Récupérer le nom de l'image pour la supprimer du dossier
    $stmt = $pdo->prepare("SELECT image_path FROM beers_table WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $user_id]);
    $beer = $stmt->fetch();

    if ($beer) {
        // Supprimer le fichier physique
        $file = "../Ajouter-Biere/uploads/" . $beer['image_path'];
        if (file_exists($file) && $beer['image_path'] != 'default_beer.png') {
            unlink($file);
        }

        // 2. Supprimer la ligne en BDD
        $del = $pdo->prepare("DELETE FROM beers_table WHERE id = ? AND user_id = ?");
        $del->execute([$id, $user_id]);
    }
}

header('Location: /Beers-App/page-beer-list\beer-list.php');
exit();