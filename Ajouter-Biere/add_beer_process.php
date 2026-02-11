<?php
session_start();

// 1. Vérification de la session (Sécurité)
if (!isset($_SESSION['user_id'])) {
    header('Location: /Beers-App/inscription-connexion/connexion/connexion.html');
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

// 3. Traitement des données du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $user_id = $_SESSION['user_id'];
    $beer_name = htmlspecialchars($_POST['beer_name']); // Sécurité contre XSS
    $rating = intval($_POST['rating']);
    $description = htmlspecialchars($_POST['description']);
    
    // Gestion de l'image par défaut
    $image_final_name = "default_beer.png"; 

    // Gestion de l'Upload de l'image
    if (isset($_FILES['beer_photo']) && $_FILES['beer_photo']['error'] === 0) {
        
        $upload_dir = 'uploads/';
        
        $file_name = $_FILES['beer_photo']['name'];
        $file_tmp = $_FILES['beer_photo']['tmp_name'];
        
        // Génère un nom unique pour éviter les doublons (ex: 17054321_ma-biere.jpg)
        $extension = pathinfo($file_name, PATHINFO_EXTENSION);
        $image_final_name = time() . '_' . bin2hex(random_bytes(5)) . '.' . $extension;
        
        $target_path = $upload_dir . $image_final_name;

        // Déplacement du fichier temporaire vers le dossier final
        if (!move_uploaded_file($file_tmp, $target_path)) {
            $image_final_name = "default_beer.png"; // En cas d'échec, retour au défaut
        }
    }

    // 5. Insertion dans la base de données
    try {
        $sql = "INSERT INTO beers_table (user_id, beer_name, image_path, rating, description) 
            VALUES (:user_id, :name, :image, :rating, :descr)";
    
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':user_id' => $user_id,
            ':name'    => $beer_name,
            ':image'   => $image_final_name,
            ':rating'  => $rating,
            ':descr'   => $description
        ]);

        header('Location: /Beers-App/page-beer-list/beer-list.php?success=1');
        exit();

    } catch (PDOException $e) {
        echo "Erreur lors de l'enregistrement : " . $e->getMessage();
    }
} else {
    // Si quelqu'un essaie d'accéder au fichier directement sans formulaire
    header('Location: ajouter-biere.php');
    exit();
}
?>