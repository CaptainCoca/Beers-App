<?php
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

    // Hachage du mot de passe
    $hashedPassword = password_hash($pass, PASSWORD_BCRYPT);

    try {
        // Insertion dans la base
        $stmt = $pdo->prepare("INSERT INTO users (pseudo, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$pseudo, $email, $hashedPassword]);

        echo "<script>alert('Compte créé avec succès !'); window.location.href='/inscription-connexion/connexion/connexion.html';</script>";
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) { // Erreur si l'email existe déjà
            echo "<script>alert('Cet email est déjà utilisé'); window.history.back();</script>";
        } else {
            echo "Erreur lors de l'inscription : " . $e->getMessage();
        }
    }
}
?>