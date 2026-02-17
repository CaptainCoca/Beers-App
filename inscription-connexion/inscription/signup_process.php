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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pseudo = trim($_POST['pseudo']);
    $email = trim($_POST['email']);
    $pass = $_POST['password'];

    $hashedPassword = password_hash($pass, PASSWORD_BCRYPT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (pseudo, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$pseudo, $email, $hashedPassword]);

        // On utilise 'user_id' pour correspondre à la vérification de beer-list.php
        $_SESSION['user_id'] = $pdo->lastInsertId(); 
        $_SESSION['pseudo'] = $pseudo;

        // Redirection directe vers la bibliothèque pour une meilleure expérience
        echo "<script>
            alert('Bienvenue, Maître Houblon ! Ton grimoire t\'attend.'); 
            window.location.href='/Beers-App/page-beer-list/beer-list.php';
        </script>";
        exit();
        
    } catch (PDOException $e) {
        // Gestion de l'erreur "Email déjà existant" (Code 23000)
        if ($e->getCode() == 23000) {
            echo "<script>alert('Cet email ou ce pseudo est déjà utilisé'); window.history.back();</script>";
        } else {
            echo "Erreur lors de l'inscription : " . $e->getMessage();
        }
    }
}
?>