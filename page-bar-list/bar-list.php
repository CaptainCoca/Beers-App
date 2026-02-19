<?php
session_start();

// 1. Vérification de sécurité (Indispensable pour l'examen)
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

    $stmt = $pdo->prepare("SELECT id, bar_name, created_at FROM bars_table WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$user_id]);
    $bars = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $selectedBar = null;
    $isAdding = isset($_GET['action']) && $_GET['action'] === 'new';

    if (isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT * FROM bars_table WHERE id = ? AND user_id = ?");
        $stmt->execute([$_GET['id'], $user_id]);
        $selectedBar = $stmt->fetch(PDO::FETCH_ASSOC);
    }

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
    <link rel="stylesheet" href="/Beers-App/page-bar-list/bar-list.css">
    <link href="https://fonts.googleapis.com/css2?family=Special+Elite&display=swap" rel="stylesheet">
    <title>Le Carnet d'Exploration - Maître Houblon</title>
</head>
<body>                                      
    <div class="app-container">
        <header class="columns is-mobile is-variable is-2 mb-4">
            <div class="column is-2">
                <img src="/Beers-App/Images/MaitreHoublon.png" alt="Logo" class="header-logo"> 
            </div>
            <div class="column is-6">
                <div class="header-box light"> LE MAÎTRE HOUBLON
                    <p class="slogan">L'amitié est un voyage, la bière est le bagage.</p>
                </div>
            </div>
            <div class="column is-4">
                <div class="header-box buttons-container">
                    <a href="/Beers-App/Avis-Public\avis-public.php" class="inner-btn">Grimoire Communautaire</a>
                    <a href="/Beers-App/index.html" class="inner-btn">Retour à l'Accueil</a>
                </div>
            </div>
        </header>

        <div class="carnet-container">
            <div class="carnet-book">
        
                <div class="page page-left">
                    <h2 class="carnet-title">Mes Explorations</h2>
                    <div class="sommaire">
                        <ul id="bar-menu">
                            <?php if (empty($bars)): ?>
                                <li class="bar-entry">Aucun récit pour le moment...</li>
                            <?php else: ?>
                                <?php foreach ($bars as $bar): ?>
                                    <li class="bar-entry">
                                        <a href="?id=<?php echo $bar['id']; ?>">
                                            <span class="bar-name-link"><?php echo html_entity_decode(htmlspecialchars($bar['bar_name'])); ?></span>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </div>
                    <a href="?action=new" class="btn-plume">+ Nouvelle Escale</a>
                </div>

                <div class="page page-right" id="detail-page">
                    
                    <?php if ($isAdding): ?>
                        <?php include 'includes/form-bar.php'; ?>

                    <?php elseif ($selectedBar): ?>
                        <div class="bar-detail-view">
                            <h2 class="carnet-title"><?php echo html_entity_decode(htmlspecialchars($selectedBar['bar_name'])); ?></h2>
                            
                            <div class="polaroid-frame">
                                <?php if ($selectedBar['image_path']): ?>
                                    <img src="../page-bar-list\uploadsBars/<?php echo $selectedBar['image_path']; ?>" alt="Photo du bar">
                                <?php else: ?>
                                    <div class="no-photo">Pas d'image</div>
                                <?php endif; ?>
                            </div>

                            <div class="details-content">
                                <p><strong>📍 Localisation :</strong> <?php echo html_entity_decode(htmlspecialchars($selectedBar['address'])); ?></p>
                                <div class="note-stars">
                                    <span class="label-note">⭐ Note du Maître :</span>
                                    <?php
                                    $note = $selectedBar['rating'];
                                    for ($i = 1; $i <= 5; $i++) {
                                        if ($i <= $note) {
                                            echo '<span class="star filled">★</span>';
                                        } else {
                                            echo '<span class="star empty">☆</span>';
                                        }
                                    }
                                    ?>
                                    <span class="note-chiffre">(<?php echo $note; ?>/5)</span>
                                </div>
                                <hr>
                                <p class="manuscrit-text">" <?php echo nl2br(html_entity_decode(htmlspecialchars($selectedBar['description']))); ?> "</p>
                            </div>

                            <a href="bar-delete.php?id=<?php echo $selectedBar['id']; ?>"
                            class="btn-delete" onnclick="return confirm('Êtes-vous sûr de vouloir arrcher cette page du grimoire ?');">
                            Supprimer cette escale
                            </a>
                        </div>
                        <hr>
                        <form action="/Beers-App/page-bar-list\bar-visibility.php" method="POST" class="visibility-form">
                            <input type="hidden" name="bar_id" value="<?php echo $selectedBar['id']; ?>">
                            <div class="field mb-4">
                                <label class="checkbox is-size-6">
                                    <input type="checkbox" id="public-toggle" 
                                        data-bar-id="<?= $selectedBar['id']; ?>"
                                        <?= ($selectedBar['is_public'] == 1) ? 'checked' : ''; ?> 
                                        onchange="updateVisibility(this)"> 
                                    <span id="status-text">📜 Partager dans le Grimoire Public</span>
                                </label>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="placeholder-content">
                            <p>Ouvrez le grimoire et sélectionnez une escale pour lire vos récits...</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="/Beers-App/page-bar-list\bar-list.js"></script>

</body>
</html>