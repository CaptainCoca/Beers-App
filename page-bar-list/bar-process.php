<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: /Beers-App/index.html');
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $user_id = $_SESSION['user_id'];
    $bar_name = trim($_POST['bar_name']);
    $address = trim($_POST['address']);
    $rating = intval($_POST['rating']);
    $description = trim($_POST['description']);
    
    $is_public = isset($_POST['is_public']) ? 1 : 0;
    
    $image_name = null;
    if (isset($_FILES['bar_image']) && $_FILES['bar_image']['error'] === 0) {
        $target_dir = "../page-bar-list/uploadsBars/";
        $extension = pathinfo($_FILES['bar_image']['name'], PATHINFO_EXTENSION);
        $image_name = "bar_" . $user_id . "_" . time() . "." . $extension;
        $target_file = $target_dir . $image_name;
        move_uploaded_file($_FILES['bar_image']['tmp_name'], $target_file);
    }

    try {
        $sql = "INSERT INTO bars_table (user_id, bar_name, address, rating, description, image_path, is_public) 
                VALUES (:user_id, :bar_name, :address, :rating, :description, :image_path, :is_public)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':user_id' => $user_id,
            ':bar_name' => $bar_name,
            ':address' => $address,
            ':rating' => $rating,
            ':description' => $description,
            ':image_path' => $image_name,
            ':is_public' => $is_public
        ]);

        header('Location: /Beers-App/page-bar-list/bar-list.php?success=1');
        exit();

    } catch (PDOException $e) {
        die("Erreur lors de l'enregistrement : " . $e->getMessage());
    }
}