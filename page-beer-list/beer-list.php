<?php
session_start();

// 1. Vérification de la session
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

    $user_id = $_SESSION['user_id'];
    
    $stmt = $pdo->prepare("SELECT * FROM beers_table WHERE user_id = ? ORDER BY id DESC");
    $stmt->execute([$user_id]); 
    $beers = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.0/css/bulma.min.css">
    <link rel="stylesheet" href="/Beers-App/page-beer-list/pageBeerList.css">
    <link href="https://fonts.googleapis.com/css2?family=Special+Elite&display=swap" rel="stylesheet">
    <title>Le Maître Houblon</title>
</head>
<body>      
    <div class="app-container">
        <header class="columns is-mobile is-variable is-2 mb-4">
            <div class="column is-2">
                <img src="/Beers-App/Images/MaitreHoublon.png" alt="Le Maître Houblon" class="header-logo"> 
            </div>
            <div class="column is-6">
                <div class="header-box light"> LE MAÎTRE HOUBLON
                    <p class="slogan">L'amitié est un voyage, la bière est le bagage.</p>
                </div>
            </div>
            <div class="column is-4">
                <div id="auth-buttons" class="header-box buttons-container">
                    <a href="/Beers-App/Avis-Public\avis-public.php" class="inner-btn">Grimoire Communautaire</a>
                    <a href="/Beers-App/index.html" class="inner-btn">Retour à l'accueil</a>
                </div>
            </div>
        </header>

        <div class="level mb-6">
            <div class="level-left">
                <div class="field has-addons">
                    <div class="control">
                        <input class="input wireframe-input" type="text" placeholder="Rechercher une bière...">
                    </div>
                    <div class="control">
                        <button class="button is-dark">🔍</button>
                    </div>
                </div>
            </div>
            <div class="level-right">
                <button class="button is-dark btn-ajouter" onclick="window.location.href='../Ajouter-Biere/ajouter-biere.php'">Ajouter une bière ➕</button>
            </div>
        </div>

        <div class="columns is-multiline">
            <?php if (empty($beers)): ?>
                <div class="column is-12 has-text-centered">
                    <div class="wireframe-box p-5">
                        <p class="is-size-5">
                            Ton grimoire est vide... 🍺<br>
                            Clique sur "Ajouter" pour commencer ta collection !
                        </p>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($beers as $beer): ?>
                    <div class="column is-3">
                        <div class="beer-card wireframe-box">
                    
                            <div class="beer-image-container">
                                <img src="../Ajouter-Biere/uploads/<?php echo htmlspecialchars($beer['image_path']); ?>" alt="Photo de <?php echo htmlspecialchars($beer['beer_name']); ?>" class="beer-img">
                            </div>

                            <h3 class="beer-title">
                                <?php echo htmlspecialchars($beer['beer_name']); ?>
                            </h3>

                            <div class="stars">
                                <?php 
                                $note = intval($beer['rating']);
                                for ($i = 1; $i <= 5; $i++) {
                                    echo ($i <= $note) ? "★" : "☆";
                                }
                                ?>
                            </div>

                            <div class="beer-comment">
                                "<?php echo htmlspecialchars($beer['description']); ?>"
                            </div>

                            <div class="card-actions">
                                <a href="../Modifier-Biere\modifier-biere.php?id=<?php echo $beer['id']; ?>" class="btn-action btn-edit">
                                    Modifier
                                </a>
                                <a href="/Beers-App/Supprimer-Biere\supprimer-biere.php?id=<?php echo $beer['id']; ?>" class="btn-action btn-delete"
                                onclick="return confirm('Es tu sûr de vouloir oublier cette bière ?');">
                                Supprimer
                                </a>
                            </div>

                            <div class="field mt-2">
                                <label class="checkbox label">
                                    <input type="checkbox" class="public-toggle" data-beer-id="<?php echo $beer['id']; ?>;"
                                    <?php echo ($beer['is_public'] == 1) ? 'checked' : ''; ?>> Partager (Public)
                                </label>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script src="/Beers-App/page-beer-list\visibility.js"></script>

</body>
</html>