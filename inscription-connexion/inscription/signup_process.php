<?php
// 1. Démarrer la session au tout début
session_start();

$host = 'localhost';
$dbname = 'maitre_houblon';
$username_db = 'root';
$password_db = ''; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username_db, $password_db);
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pseudo = $_POST['pseudo'];
    $email = $_POST['email'];
    $pass = $_POST['password'];

    $hashedPassword = password_hash($pass, PASSWORD_BCRYPT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (pseudo, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$pseudo, $email, $hashedPassword]);

        // Enregistrer les infos en session ICI
        // C'est ce qui permettra au script.js de savoir qui est là
        $_SESSION['id'] = $pdo->lastInsertId();
        $_SESSION['pseudo'] = $pseudo;

        echo "<script>alert('Bienvenue, Maitre Houblon ! Ton compte a été créé !'); window.location.href='/Beers-App/index.html';</script>";
        
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            echo "<script>alert('Cet email est déjà utilisé'); window.history.back();</script>";
        } else {
            echo "Erreur lors de l'inscription : " . $e->getMessage();
        }
    }
}
?>