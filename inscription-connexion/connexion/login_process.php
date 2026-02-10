<?php
session_start();

// Connexion BDD
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
    $email = $_POST['email'];
    $pass = $_POST['password'];

    // On cherche l'utilisateur par son email
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // On vérifie si l'utilisateur existe et si le mot de passe est correct
    if ($user && password_verify($pass, $user['password'])) {
        
        $_SESSION['pseudo'] = $user['pseudo'];
        $_SESSION['user_id'] = $user['id'];

        echo "<script>alert('Content de vous revoir, " . $user['pseudo'] . " !'); window.location.href='/Beers-App/index.html';</script>";
    } else {

        echo "<script>alert('Email ou mot de passe incorrect'); window.history.back();</script>";
    }
}
?>