<?php
session_start();

$host = 'localhost';     
$dbname = 'maitre_houblon'; 
$username_db = 'root';  
$password_db = '';  

try {
    // PDO = couche d'abstraction pour parler à MySQL de façon sécurisée
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username_db, $password_db);
} catch (PDOException $e) {
    // Si la connexion échoue, on arrête tout et on affiche l'erreur
    die("Erreur : " . $e->getMessage());
}

// On ne traite le formulaire que si la page a été appelée en POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];       // Email saisi dans le formulaire
    $pass  = $_POST['password'];    // Mot de passe saisi (non hashé, c'est ce que l'utilisateur tape)

    // Requête préparée : le ? est un paramètre sécurisé (protège contre les injections SQL)
    // Une injection SQL = quelqu'un qui tape du code SQL dans un champ pour pirater la BDD
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]); // On passe l'email en paramètre ici, séparé de la requête
    $user = $stmt->fetch();   // Récupère la ligne correspondante (ou false si introuvable)

    // $user → l'email existe bien en base
    // password_verify() → compare le mot de passe tapé avec le hash stocké en BDD
    if ($user && password_verify($pass, $user['password'])) {
        
        // Connexion réussie : on stocke les infos de l'utilisateur dans la session
        $_SESSION['pseudo']  = $user['pseudo'];
        $_SESSION['user_id'] = $user['id'];

        // Affiche un message de bienvenue puis redirige vers l'accueil
        echo "<script>alert('Content de vous revoir, " . $user['pseudo'] . " !'); window.location.href='/Beers-App/index.html';</script>";
    } else {
        // Mot de passe incorrect ou email inconnu → message d'erreur + retour arrière
        echo "<script>alert('Email ou mot de passe incorrect'); window.history.back();</script>";
    }
}
?>