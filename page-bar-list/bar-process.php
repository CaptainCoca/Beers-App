<?php

session_start();

// Sécurité : redirige vers l'accueil si l'utilisateur n'est pas connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: /Beers-App/index.html');
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

// On ne traite les données que si le formulaire a bien été soumis en POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $user_id     = $_SESSION['user_id'];
    $rating      = intval($_POST['rating']);        // intval() force la valeur en entier (ex: "3" → 3)
    $description = trim($_POST['description']);     // trim() supprime les espaces inutiles en début/fin

    // isset() + opérateur ternaire : si la case "public" est cochée → 1, sinon → 0
    $is_public   = isset($_POST['is_public']) ? 1 : 0;

    // filter_input() récupère bar_ref_id depuis POST et vérifie que c'est bien un entier valide
    // FILTER_VALIDATE_INT retourne false si la valeur n'est pas un entier → sécurité contre les données invalides
    $bar_ref_id = filter_input(INPUT_POST, 'bar_ref_id', FILTER_VALIDATE_INT);
    if (!$bar_ref_id) {
        die("Veuillez sélectionner un établissement."); // Arrête tout si aucun bar n'a été sélectionné
    }

    // Récupère le nom et l'adresse officiels depuis la table de référence (niort_bars_reference)
    // → On n'utilise PAS ce que l'utilisateur a tapé, mais les données normalisées de la BDD
    // Cela garantit que tous les utilisateurs ont le même nom pour le même bar (pas de variantes)
    $stmt = $pdo->prepare("SELECT name, address FROM niort_bars_reference WHERE id = ?");
    $stmt->execute([$bar_ref_id]);
    $ref = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$ref) {
        die("Établissement introuvable."); // L'ID envoyé ne correspond à aucun bar référencé
    }

    $bar_name = $ref['name'];    // Nom normalisé issu de la table de référence
    $address  = $ref['address']; // Adresse normalisée issu de la table de référence

    // Gestion de l'image uploadée 
    $image_name = null; // Par défaut, pas d'image (null sera stocké en BDD)

    // Vérifie qu'un fichier a bien été envoyé et sans erreur (error === 0 = upload OK)
    if (isset($_FILES['bar_image']) && $_FILES['bar_image']['error'] === 0) {
        $target_dir = "../page-bar-list/uploadsBars/";

        // Récupère l'extension du fichier original (ex: "jpg", "png", "webp")
        $extension  = pathinfo($_FILES['bar_image']['name'], PATHINFO_EXTENSION);

        // Construit un nom de fichier unique : "bar_" + ID utilisateur + "_" + timestamp
        // time() retourne l'heure en secondes depuis 1970 → toujours différent
        $image_name  = "bar_" . $user_id . "_" . time() . "." . $extension;
        $target_file = $target_dir . $image_name;

        // Déplace le fichier depuis le dossier temporaire PHP vers le dossier de stockage définitif
        move_uploaded_file($_FILES['bar_image']['tmp_name'], $target_file);
    }

    try {
        // Insertion du nouveau bar en BDD avec des paramètres nommés (:nom) pour plus de lisibilité
        // Les paramètres nommés évitent les injections SQL (les valeurs ne sont jamais interprétées comme du SQL)
        $sql = "INSERT INTO bars_table 
                    (user_id, bar_name, address, rating, description, image_path, is_public, bar_ref_id) 
                VALUES 
                    (:user_id, :bar_name, :address, :rating, :description, :image_path, :is_public, :bar_ref_id)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':user_id'     => $user_id,
            ':bar_name'    => $bar_name,     // Vient de niort_bars_reference 
            ':address'     => $address,      // Vient de niort_bars_reference
            ':rating'      => $rating,
            ':description' => $description,
            ':image_path'  => $image_name,   // Null si pas d'image, sinon le nom du fichier
            ':is_public'   => $is_public,
            ':bar_ref_id'  => $bar_ref_id    // Clé étrangère vers niort_bars_reference
            // Ce FK (clée étrangère) peut déclencher un trigger MySQL (mise à jour automatique de stats, par ex.)
        ]);

        // Redirige vers la liste des bars avec un paramètre de succès dans l'URL
        header('Location: /Beers-App/page-bar-list/bar-list.php?success=1');
        exit();

    } catch (PDOException $e) {
        die("Erreur lors de l'enregistrement : " . $e->getMessage());
    }
}