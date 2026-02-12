<?php
session_start();

// 1. Sécurité : redirection si non connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: /Beers-App/inscription-connexion/connexion/connexion.html');
    exit();
}

// 2. Connexion à la base de données
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

// 3. Traitement du formulaire (si envoyé en POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $user_id = $_SESSION['user_id'];
    $beer_name = trim($_POST['beer_name']); 
    $rating = intval($_POST['rating']);
    $description = trim($_POST['description']); // On garde le texte brut (propre pour les apostrophes)
    
    $image_final_name = "default_beer.png"; 

    // Gestion de l'Upload
    if (isset($_FILES['beer_photo']) && $_FILES['beer_photo']['error'] === 0) {
        $upload_dir = '../Ajouter-Biere\uploads/';
        
        // Création du dossier s'il n'existe pas
        if (!is_dir($upload_dir)) { mkdir($upload_dir, 0777, true); }

        $file_name = $_FILES['beer_photo']['name'];
        $file_tmp = $_FILES['beer_photo']['tmp_name'];
        
        $extension = pathinfo($file_name, PATHINFO_EXTENSION);
        $image_final_name = time() . '_' . bin2hex(random_bytes(5)) . '.' . $extension;
        
        $target_path = $upload_dir . $image_final_name;

        if (!move_uploaded_file($file_tmp, $target_path)) {
            $image_final_name = "default_beer.png"; 
        }
    }

    // Insertion BDD
    try {
        $sql = "INSERT INTO beers_table (user_id, beer_name, image_path, rating, description) 
                VALUES (:user_id, :name, :image, :rating, :descr)";
    
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':user_id' => $user_id,
            ':name'    => $beer_name,
            ':image'   => $image_final_name,
            ':rating'  => $rating,
            ':descr'   => $description
        ]);

        header('Location: /Beers-App/page-beer-list/beer-list.php?success=1');
        exit();

    } catch (PDOException $e) {
        $error_msg = "Erreur lors de l'enregistrement : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nouvelle Pépite - Le Maître Houblon</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.0/css/bulma.min.css">
    <link rel="stylesheet" href="/Beers-App/Ajouter-Biere\ajouter-biere.css">
    <link href="https://fonts.googleapis.com/css2?family=Special+Elite&display=swap" rel="stylesheet">
</head>
<body>
    <a href="/Beers-App/page-beer-list/beer-list.php" class="back-home">
        <span>← Ma Bibliothèque</span> 
    </a>

    <div class="app-container is-flex is-align-items-center is-justify-content-center">
        <div class="container">
            <div class="columns is-centered">
                <div class="column is-6">
                    <div class="wireframe-box p-6">
                        <h1 class="title has-text-centered">Ajouter une découverte</h1>
                        
                        <?php if(isset($error_msg)): ?>
                            <div class="notification is-danger"><?php echo $error_msg; ?></div>
                        <?php endif; ?>
                    
                        <form action="" method="POST" enctype="multipart/form-data">

                            <div class="field">
                                <label class="label">Nom de la bière</label>
                                <div class="control">
                                    <input class="input wireframe-input" type="text" name="beer_name" placeholder="Ex: Pelican, 1664.." required>
                                </div>
                            </div>

                            <div class="field">
                                <label class="label">Photo de la pépite (ou pas)</label>
                                <div class="control">
                                    <div class="file has-name is-fullwidth">
                                        <label class="file-label">
                                            <input class="file-input" type="file" name="beer_photo" id="beer_photo" accept="image/*">
                                            <span class="file-cta">
                                                <span class="file-icon">📤</span>
                                                <span class="file-label">Choisir une image...</span>
                                            </span>
                                            <span class="file-name" id="file-name-display"> Aucun Fichier... </span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="field">
                                <label class="label">Ma note</label>
                                <div class="control">
                                    <div class="select is-fullwidth">
                                        <select name="rating" class="wireframe-input">
                                            <option value="5">⭐⭐⭐⭐⭐ - Divine</option>
                                            <option value="4">⭐⭐⭐⭐☆ - Très Bonne</option>
                                            <option value="3">⭐⭐⭐☆☆ - Sympathique</option>
                                            <option value="2">⭐⭐☆☆☆ - Bof Bof</option>
                                            <option value="1">⭐☆☆☆☆ - Dla Merde</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="field">
                                <label class="label">Mon avis (pour la postérité)</label>
                                <div class="control">
                                    <textarea class="textarea wireframe-input" name="description" placeholder="Note de dégustation, amertume, ou anecdote rigolote..." rows="4"></textarea>
                                </div>
                            </div>

                            <div class="control mt-6">
                                <button type="submit" class="button is-dark is-fullwidth is-large inner-btn">Enregistrer dans mon grimoire</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="/Beers-App/Ajouter-Biere\ajouter-biere.js"></script>
</body>
</html>