<?php
session_start();

// 1. Sécurité et Connexion
if (!isset($_SESSION['user_id'])) {
    header('Location: /Beers-App/inscription-connexion/connexion/connexion.html');
    exit();
}

$host = 'localhost';
$dbname = 'maitre_houblon';
$username_db = 'root';
$password_db = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username_db, $password_db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

// 2. Récupérer la bière à modifier
if (!isset($_GET['id'])) {
    header('Location: ../page-beer-list/beer-list.php');
    exit();
}

$id = $_GET['id'];
$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM beers_table WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $user_id]);
$beer = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$beer) {
    die("Bière non trouvée !");
}

// 3. Traitement de la modification (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['beer_name']);
    $rating = intval($_POST['rating']);
    $description = trim($_POST['description']);

    $image_path = $beer['image_path']; // Par défaut, on garde l'ancienne

    // Gestion de la nouvelle image
    if (isset($_FILES['beer_photo']) && $_FILES['beer_photo']['error'] === 0) {
        $upload_dir = '../Ajouter-Biere\uploads/';
        $extension = strtolower(pathinfo($_FILES['beer_photo']['name'], PATHINFO_EXTENSION));
        $new_image_name = time() . '_' . bin2hex(random_bytes(5)) . '.' . $extension;
        
        if (move_uploaded_file($_FILES['beer_photo']['tmp_name'], $upload_dir . $new_image_name)) {
            // On supprime l'ancienne image physiquement (sauf si c'est la par défaut)
            if ($beer['image_path'] != 'default_beer.png' && file_exists($upload_dir . $beer['image_path'])) {
                unlink($upload_dir . $beer['image_path']);
            }
            $image_path = $new_image_name;
        }
    }

    // Mise à jour BDD
    $update = $pdo->prepare("UPDATE beers_table SET beer_name = ?, rating = ?, description = ?, image_path = ? WHERE id = ? AND user_id = ?");
    $update->execute([$name, $rating, $description, $image_path, $id, $user_id]);

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
                <div class="field">
                    <label class="label">Nom de la bière</label>
                    <div class="control">
                        <input class="input" type="text" name="beer_name" value="<?php echo htmlspecialchars($beer['beer_name']); ?>" required>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Image actuelle</label>
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

    <script src="/Beers-App/Ajouter-Biere\ajouter-biere.js"></script>

</body>
</html>