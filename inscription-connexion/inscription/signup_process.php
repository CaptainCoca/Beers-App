<?php

session_start(); // Démarre la session pour pouvoir connecter l'utilisateur juste après

$host        = 'localhost';
$dbname      = 'maitre_houblon';
$username_db = 'root';
$password_db = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username_db, $password_db);
    // ERRMODE_EXCEPTION = si une requête échoue, PHP lève une exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // trim() supprime les espaces en début et fin de chaîne (évite " admin " ≠ "admin")
    $pseudo = trim($_POST['pseudo']);
    $email  = trim($_POST['email']);
    $pass   = $_POST['password'];

    // PASSWORD_BCRYPT = algorithme sécurisé recommandé pour les mots de passe
    $hashedPassword = password_hash($pass, PASSWORD_BCRYPT);

    try {
        // Insère le nouvel utilisateur en BDD avec son mot de passe hashé
        $stmt = $pdo->prepare("INSERT INTO users (pseudo, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$pseudo, $email, $hashedPassword]);

        // lastInsertId() récupère l'ID du dèrnier utilisateur créer
        $_SESSION['user_id'] = $pdo->lastInsertId();
        $_SESSION['pseudo']  = $pseudo; // On le stocke en session pour considérer l'utilisateur comme connecté immédiatement

        // Redirige vers la bibliothèque de bières
        echo "<script>
            alert('Bienvenue, Maître Houblon ! Ton grimoire t\'attend.'); 
            window.location.href='/Beers-App/page-beer-list/beer-list.php';
        </script>";
        exit(); // Stoppe l'exécution PHP après la redirection

    } catch (PDOException $e) {
        // getCode() == 23000 = code SQL pour violation de contrainte UNIQUE
        // Cela signifie que l'email ou le pseudo existe déjà en base de données
        if ($e->getCode() == 23000) {
            echo "<script>alert('Cet email ou ce pseudo est déjà utilisé'); window.history.back();</script>";
        } else {
            // Autre erreur SQL imprévue
            echo "Erreur lors de l'inscription : " . $e->getMessage();
        }
    }
}
?>