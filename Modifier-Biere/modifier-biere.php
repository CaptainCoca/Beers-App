<?php

session_start();
// Vérifie que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    // Redirige vers la page de connexion si l'utilisateur n'est pas connecté
    header('Location: /Beers-App/inscription-connexion/connexion/connexion.html');
    exit(); // Stoppe l'exécution du script après la redirection
}

$host        = 'localhost';
$dbname      = 'maitre_houblon';
$username_db = 'root';
$password_db = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username_db, $password_db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

// L'ID de la bière est passé dans l'URL : modifier-biere.php?id=42
if (!isset($_GET['id'])) {
    // Si pas d'ID dans l'URL, on renvoie vers la liste
    header('Location: ../page-beer-list/beer-list.php');
    exit();
}

$id      = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Double vérification : la bière doit exister ET appartenir à l'utilisateur connecté
// Si quelqu'un tente de modifier la bière d'un autre en changeant l'id dans l'URL → bloqué
$stmt = $pdo->prepare("SELECT * FROM beers_table WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $user_id]);
$beer = $stmt->fetch(PDO::FETCH_ASSOC); // FETCH_ASSOC = résultat sous forme de tableau associatif (clé => valeur)

if (!$beer) {
    die("Bière non trouvée !"); // Arrêt si la bière n'existe pas ou appartient à quelqu'un d'autre
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // htmlspecialchars() neutralise les balises HTML (ex: <script>) pour éviter les attaques XSS
    $name        = htmlspecialchars($_POST['beer_name']);
    $rating      = intval($_POST['rating']);        // intval() force la conversion en entier
    $description = trim($_POST['description']);

    // Par défaut, on conserve l'image actuelle
    $image_path = $beer['image_path'];

    // Vérifie si une nouvelle image a été envoyée et si elle ne contient pas d'erreur
    // $_FILES['beer_photo']['error'] === 0 signifie "upload réussi, pas d'erreur"
    if (isset($_FILES['beer_photo']) && $_FILES['beer_photo']['error'] === 0) {
        $upload_dir = '../Ajouter-Biere\uploads/';

        // Extrait l'extension du fichier (ex: "jpg", "png") en minuscules
        $extension = strtolower(pathinfo($_FILES['beer_photo']['name'], PATHINFO_EXTENSION));

        // Génère un nom de fichier unique : timestamp + 10 octets aléatoires en hexadécimal
        // Évite les collisions de noms (deux utilisateurs uploadant "photo.jpg" en même temps)
        $new_image_name = time() . '_' . bin2hex(random_bytes(5)) . '.' . $extension;
        
        // Déplace le fichier temporaire (dossier temp du serveur) vers le dossier d'uploads définitif
        if (move_uploaded_file($_FILES['beer_photo']['tmp_name'], $upload_dir . $new_image_name)) {
            // Supprime l'ancienne image du serveur pour ne pas laisser des fichiers inutiles
            // Sauf si c'est l'image par défaut (partagée entre plusieurs bières)
            if ($beer['image_path'] != 'default_beer.png' && file_exists($upload_dir . $beer['image_path'])) {
                unlink($upload_dir . $beer['image_path']); // unlink() = supprimer un fichier sur le serveur
            }
            $image_path = $new_image_name; // On utilise désormais la nouvelle image
        }
    }

    // Met à jour la bière en BDD avec les nouvelles valeurs
    // Le AND user_id = ? est une sécurité supplémentaire (impossible de modifier la bière d'un autre)
    $update = $pdo->prepare("UPDATE beers_table SET beer_name = ?, rating = ?, description = ?, image_path = ? WHERE id = ? AND user_id = ?");
    $update->execute([$name, $rating, $description, $image_path, $id, $user_id]);

    // Redirige vers la liste avec un paramètre ?updated=1 (pour éventuellement afficher un message de succès)
    header('Location: ../page-beer-list/beer-list.php?updated=1');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier ma bière</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.0/css/bulma.min.css">
    <link rel="stylesheet" href="../Modifier-Biere\modifier-biere.css"> 
</head>
<body>
    <div class="container mt-5">
        <div class="box wireframe-box">
            <h1 class="title has-text-centered">MODIFIER LE GRIMOIRE</h1>
            
            <form action="" method="POST" enctype="multipart/form-data">
                <!-- action="" = le formulaire se soumet à lui-même (cette même page PHP) -->
                <!-- enctype="multipart/form-data" = obligatoire pour pouvoir envoyer des fichiers/images -->

                <div class="field">
                    <label class="label">Nom de la bière</label>
                    <div class="control">
                        <!-- htmlspecialchars() dans value="" empêche que des guillemets ou < > cassent le HTML -->
                        <input class="input" type="text" name="beer_name" value="<?php echo htmlspecialchars($beer['beer_name']); ?>" required>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Image actuelle</label>
                    <!-- Affiche l'image actuelle de la bière pour que l'utilisateur sache ce qu'il va remplacer -->
                    <img src="/Beers-App/Ajouter-Biere\uploads/<?php echo $beer['image_path']; ?>">
                    <div class="file has-name">
                        <label class="file-label">
                            <input class="file-input" type="file" name="beer_photo" id="beer_photo">
                            <span class="file-cta">
                                <span class="file-label">Changer l'image...</span>
                            </span>
                            <span class="file-name" id="file-name-display"> Aucun Fichier... </span>
                        </label>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Ma note</label>
                    <div class="control">
                        <div class="select is-fullwidth">
                            <select name="rating" class="wireframe-input">
                                <!-- Pour chaque option : si la note stockée correspond → attribut 'selected' ajouté -->
                                <!-- Cela pré-sélectionne la note actuelle de la bière dans la liste -->
                                <option value="5" <?php echo ($beer['rating'] == 5) ? 'selected' : ''; ?>>⭐⭐⭐⭐⭐ - Divine</option>
                                <option value="4" <?php echo ($beer['rating'] == 4) ? 'selected' : ''; ?>>⭐⭐⭐⭐☆ - Très Bonne</option>
                                <option value="3" <?php echo ($beer['rating'] == 3) ? 'selected' : ''; ?>>⭐⭐⭐☆☆ - Sympathique</option>
                                <option value="2" <?php echo ($beer['rating'] == 2) ? 'selected' : ''; ?>>⭐⭐☆☆☆ - Bof Bof</option>
                                <option value="1" <?php echo ($beer['rating'] == 1) ? 'selected' : ''; ?>>⭐☆☆☆☆ - Mauvais Souvenir</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Description</label>
                    <div class="control">
                        <!-- Le contenu du textarea est entre les balises (pas dans value="") -->
                        <!-- htmlspecialchars() protège contre les caractères spéciaux dans la description -->
                        <textarea class="textarea" name="description"><?php echo htmlspecialchars($beer['description']); ?></textarea>
                    </div>
                </div>

                <div class="buttons is-centered mt-5">
                    <button type="submit" class="button is-warning">SAUVEGARDER LES CHANGEMENTS</button>
                    <a href="../page-beer-list/beer-list.php" class="button is-light">ANNULER</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Ce script gère l'affichage du nom de fichier dans le champ image -->
    <script src="/Beers-App/Ajouter-Biere\ajouter-biere.js"></script>

</body>
</html>