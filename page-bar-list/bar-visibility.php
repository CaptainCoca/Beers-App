<?php

session_start();

// Double vérification de sécurité :
// L'utilisateur doit être connecté (session active)
// Un bar_id doit avoir été envoyé en POST
// Si l'une des deux conditions manque, on redirige sans rien faire
if (!isset($_SESSION['user_id']) || !isset($_POST['bar_id'])) {
    header('Location: bar-list.php');
    exit();
}

$host        = 'localhost';
$dbname      = 'maitre_houblon';
$username_db = 'root';
$password_db = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username_db, $password_db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

if (isset($_POST['bar_id'])) {
    $bar_id    = intval($_POST['bar_id']);    // intval() : force en entier pour éviter des valeurs inattendues
    $is_public = intval($_POST['is_public']); // Sera 0 ou 1 (privé ou public)
    $user_id   = $_SESSION['user_id'];

    // Met à jour uniquement le champ is_public du bar concerné
    // Le AND user_id = :user_id est une sécurité essentielle :
    // → empêche un utilisateur de rendre public le bar d'un autre en falsifiant l'ID
    $sql  = "UPDATE bars_table SET is_public = :is_public WHERE id = :id AND user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([
        ':is_public' => $is_public,
        ':id'        => $bar_id,
        ':user_id'   => $user_id
    ]);
    // $result = true si la requête s'est exécutée sans erreur, false sinon

    // Si le paramètre 'ajax' est présent dans le POST (envoyé par bar-list.js)
    // on répond en texte brut au lieu de faire une redirection HTML
    if (isset($_POST['ajax'])) {
        echo ($result) ? "success" : "error_db"; // Réponse courte lue par le fetch() JavaScript
        exit(); // Stoppe l'exécution : on ne veut pas continuer vers le header() ci-dessous
    }
}

// Si ce n'est pas une requête AJAX (formulaire classique sans JavaScript),
// on redirige simplement vers la liste après la mise à jour
header("Location: bar-list.php");
exit();