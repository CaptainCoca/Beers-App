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

$sqlBars = "SELECT ba.*, u.pseudo FROM bars_table ba JOIN users u ON ba.user_id = u.id WHERE ba.is_public = 1 ORDER BY ba.id DESC";
$stmtBars = $pdo->query($sqlBars);
$public_bars = $stmtBars->fetchAll(PDO::FETCH_ASSOC);



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
                    <div class="dropdown">
                        <button class="inner-btn dropbtn"><strong>Mes Carnets ▼</strong></button>
                        <div class="dropdown-content">
                            <a href="/Beers-App/page-beer-list/beer-list.php">🍺 Mes bières</a>
                            <a href="/Beers-App/page-bar-list/bar-list.php">📍 Mes Escales</a>
                        </div>
                    </div>
        
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

        <div class="tabs-wrapper mb-">
            <button class="tab-btn active" onclick="switchGrimoire('bieres')"> Bières de la Confrérie</button>
            <button class="tab-btn" onclick="switchGrimoire('bars')"> Escales du Royaume</button>
        </div>

        <div id="grimoire-bieres" class="grimoire-section">
            <div class="beers-list-fullwidth">
                <?php foreach ($public_beers as $beer): ?>
                    <?php 
                        $stmtComments = $pdo->prepare("SELECT c.id, c.content, c.user_id, c.beer_id, u.pseudo FROM comments c JOIN users u ON c.user_id = u.id WHERE c.beer_id = ? ORDER BY c.created_at ASC");
                        $stmtComments->execute([$beer['id']]);
                        $comments = $stmtComments->fetchAll();
                    ?>
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
    
                            <div class="beer-footer-row">
                                <div class="quote">
                                    <?= nl2br(htmlspecialchars($beer['description'])) ?>
                                </div>

                                <div class="beer-actions">
                                    <button class="action-btn like-btn" data-id="<?= $beer['id'] ?>">
                                        <span class="icon">🍺</span> 
                                        <span class="count"><?= $beer['likes'] ?></span>
                                    </button>
                                    <button class="action-btn comment-btn" data-id="<?= $beer['id'] ?>">
                                        <span class="icon">📜</span> 
                                    </button>
                                </div>
                            </div>

                            <div class="comments-container" id="comments-<?= $beer['id'] ?>">
                                <hr class="comment-divider">
                                <div class="comments-list">
                                    <?php if (empty($comments)): ?>
                                        <p class="is-size-7 has-text-black">Aucun parchemin laissé pour le moment...</p>
                                    <?php else: ?>
                                        <?php foreach ($comments as $com): ?>
                                            <div class="comment-item" id="comment-block-<?= $com['id'] ?>">
                                                <strong><?= htmlspecialchars($com['pseudo']) ?></strong>
                                                <p><?= nl2br(htmlspecialchars($com['content'])) ?></p>

                                                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $com['user_id']): ?>
                                                    <div class="comment-actions mt-1">
                                                        <button class="is-ghost p-0 mr-2 edit-comment-btn" data-id="<?= $com['id'] ?>">Modifier</button>
                                                        <button class="is-ghost p-0 has-text-danger delete-comment-btn" data-id="<?= $com['id'] ?>">Supprimer</button>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>

                                <?php if(isset($_SESSION['user_id'])): ?>
                                    <div class="comment-form-wrapper mt-3">
                                        <div class="field has-addons">
                                            <div class="control is-expanded">
                                                <input class="input is-small comment-input" type="text" placeholder="Ecrire un parchemin..." data-beer-id="<?= $beer['id'] ?>">
                                            </div>
                                            <div class="control">
                                                <button class="button is-small is-warning send-comment" data-beer-id="<?= $beer['id'] ?>">Envoyer</button>
                                            </div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <p class="is-size-7 has-text-danger mt-2">Connectez-vous pour laisser un parchemin.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div id="grimoire-bars" class="grimoire-section" style="display: none;">
            <div class="bars-grid-container">
                <?php if (empty($public_bars)): ?>
                    <p class="has-text-centered p-6">Aucune escale n'a encore été répertoriée dans le grimoire...</p>
                <?php else: ?>
                    <?php foreach ($public_bars as $bar) : ?>
                        <div class="bar-adventure-card"> 
                    
                            <div class="bar-card-header">
                                <img src="/Beers-App/page-bar-list/uploadsBars/<?= !empty($bar['image_path']) ? htmlspecialchars($bar['image_path']) : 'bar-default.png' ?>" alt="Bar">
                                <div class="bar-rating-badge">
                                    <?= str_repeat('⭐', $bar['rating']) ?>
                                </div>
                            </div>

                            <div class="bar-card-body">
                                <h2 class="bar-title"><?= html_entity_decode(htmlspecialchars($bar['bar_name'])) ?></h2>
                        
                                <p class="bar-location">📍 <?= htmlspecialchars($bar['address']) ?></p>
                        
                                <div class="bar-description-box">
                                    <div class="bar-text-content clamped">
                                        <?= nl2br(html_entity_decode(htmlspecialchars($bar['description'] ?? 'Pas de description.'))) ?>
                                    </div>
                                    <button class="bar-expand-link" onclick="toggleText(this)">▼ Voir le récit</button>
                                </div>

                                <div class="bar-card-footer">
                                    <span class="bar-author">Par Maître <strong><?= htmlspecialchars($bar['pseudo'] ?? 'Anonyme') ?></strong></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script src="/Beers-App/Avis-Public\avis-public.js"></script>

</body>
</html>

