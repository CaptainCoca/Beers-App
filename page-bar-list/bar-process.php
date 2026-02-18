<?php
session_start();

// 1. Sécurité : Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: /Beers-App/index.html');
    exit();
}

// 2. Connexion à la base de données
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

// 3. Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Récupération et nettoyage simple des données (faille XSS)
    $user_id = $_SESSION['user_id'];
    $bar_name = htmlspecialchars($_POST['bar_name']);
    $address = htmlspecialchars($_POST['address']);
    $rating = intval($_POST['rating']);
    $description = htmlspecialchars($_POST['description']);
    
    // Gestion de l'image
    $image_name = null; // Par défaut, pas d'image

    if (isset($_FILES['bar_image']) && $_FILES['bar_image']['error'] === 0) {
        // Dossier de destination
        $target_dir = "../page-bar-list\uploadsBars/";
        
        // Créer le dossier s'il n'existe pas
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // Renommer l'image pour éviter les doublons (ID_USER + TIMESTAMP)
        $extension = pathinfo($_FILES['bar_image']['name'], PATHINFO_EXTENSION);
        $image_name = "bar_" . $user_id . "_" . time() . "." . $extension;
        $target_file = $target_dir . $image_name;

        // Déplacer le fichier temporaire vers le dossier final
        move_uploaded_file($_FILES['bar_image']['tmp_name'], $target_file);
    }

    // 4. Insertion SQL (Requête préparée pour éviter les injections SQL)
    try {
        $sql = "INSERT INTO bars_table (user_id, bar_name, address, rating, description, image_path) 
                VALUES (:user_id, :bar_name, :address, :rating, :description, :image_path)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':user_id' => $user_id,
            ':bar_name' => $bar_name,
            ':address' => $address,
            ':rating' => $rating,
            ':description' => $description,
            ':image_path' => $image_name
        ]);

        // Redirection vers le carnet avec un petit succès
        header('Location: /Beers-App/page-bar-list\bar-list.php?success=1');
        exit();

    } catch (PDOException $e) {
        die("Erreur lors de l'enregistrement : " . $e->getMessage());
    }
}