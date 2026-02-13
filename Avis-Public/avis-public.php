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

$sql = "SELECT b.*, u.pseudo FROM beers_table b JOIN users u ON b.user_id = u.id WHERE b.is_public = 1 ORDER BY b.id DESC";

$stmt = $pdo->query($sql);
$public_beers = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.0/css/bulma.min.css">
    <link rel="stylesheet" href="/Beers-App/Avis-Public\avis-public.css">
    <link href="https://fonts.googleapis.com/css2?family=Special+Elite&display=swap" rel="stylesheet">
    <title>Le Grimoire Public - Le Maître Houblon</title>
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
                    <a href="/Beers-App/index.html" class="inner-btn">Retour à l'accueil</a>
                </div>
            </div>
        </header>
        
        <div class="top-bar-grimoire mb-6">
            <div class="columns is-vcentered">
                <div class="column is-8">
                    <div class="header-side-box">
                        <h1 class="title is-2"> 🍻 Grimoire Communautaire de la Confrérie</h1>
                        <p class="slogan-public">Parce qu'une bonne bière est encore meilleure quand elle est partagée avec toute la guilde. Découvrez les trésors houblonnés que nos membres ont déniché aux quatre coins du royaume.</p>
                    </div>
                </div>
                <div class="column is-4">
                    <div class="search-box-wrapper">
                        <div class="field has-addons">
                            <div class="control is-expanded">
                                <input class="input-custom-search" type="text" placeholder="Rechercher une bière, un style, un maître..">
                            </div>
                            <div class="control">
                                <button class="inner-btn-search">Chercher</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="beers-list-fullwidth">
            <?php foreach ($public_beers as $beer): ?>
                <div class="beer-list-item">
                    <div class="beer-list-image">
                        <img src="/Beers-App/Ajouter-Biere/uploads/<?= htmlspecialchars($beer['image_path']) ?>" alt="Bière">
                    </div>
                
                    <div class="beer-list-content">
                        <h2 class="beer-name"><?= htmlspecialchars($beer['beer_name']) ?></h2>
                        <p class="author-tag">Partagé par : <strong><?= htmlspecialchars($beer['pseudo']) ?></strong></p>
                    
                        <div class="beer-rating">
                            <?= str_repeat('⭐', $beer['rating']) ?>
                        </div>
                    
                        <div class="quote">
                            <?= nl2br(htmlspecialchars($beer['description'])) ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
        </div>
</body>
</html>

