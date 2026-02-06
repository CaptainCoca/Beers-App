<?php
session_start();

$host = 'localhost';
$dbname = 'maitre_houblon';
$username_db = 'root';
$password_db = ''; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username_db, $password_db);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // On récupère l'email
    $email = $_POST['email'];
    $pass = $_POST['password'];

    // On cherche l'utilisateur par son email
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $userData = $stmt->fetch();

    if ($userData && password_verify($pass, $userData['password'])) {
        $_SESSION['user_email'] = $userData['email'];
        $_SESSION['user_id'] = $userData['id'];
        
        header('Location: index.html'); 
        exit();
    } else {
        echo "<script>alert('Email ou mot de passe incorrect'); window.location.href='/';</script>";
    }
}
?>

<!-- =============== A TERMINER =============== -->