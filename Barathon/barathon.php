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

// Bars de référence avec moyenne des notes publiques
$sqlBars = "
    SELECT 
        r.id, r.name, r.address, r.latitude, r.longitude,
        r.rating, r.phone, r.status, r.description,
        COUNT(b.id) as nb_avis
    FROM niort_bars_reference r
    LEFT JOIN bars_table b ON b.bar_ref_id = r.id AND b.is_public = 1
    GROUP BY r.id
    ORDER BY r.name ASC
";
$stmtBars = $pdo->query($sqlBars);
$bars_reference = $stmtBars->fetchAll(PDO::FETCH_ASSOC);

// Avis publics des utilisateurs (pour la sidebar)
$sqlAvis = "
    SELECT ba.*, u.pseudo 
    FROM bars_table ba 
    JOIN users u ON ba.user_id = u.id 
    WHERE ba.is_public = 1 
    ORDER BY ba.created_at DESC
";
$stmtAvis = $pdo->query($sqlAvis);
$public_bars = $stmtAvis->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.0/css/bulma.min.css">
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="/Beers-App/Barathon/barathon.css">
    <link href="https://fonts.googleapis.com/css2?family=Special+Elite&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
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

        <div class="barathon-layout">
            <aside class="barathon-sidebar">
                <div class="search-box">
                    <input type="text" id="bar-search" placeholder="Chercher une escale...">
                    <button class="btn-arrow" onclick="searchBar()">Rechercher</button>
                </div>

                <div id="bar-details" class="details-container">
                    <h2 id="detail-name">Sélectionnez un bar</h2>
                    <div id="detail-rating">—</div>
                    <p id="detail-nb-avis" style="font-size:0.85rem; opacity:0.7;"></p>
                    <p id="detail-status"></p>
                    <p id="detail-phone"></p>
                    <p id="detail-address" style="font-size:0.85rem;"></p>
            
                    <div class="quote-box" id="avis-container">
                        <p>Cliquez sur un marqueur pour voir les détails...</p>
                    </div>

                    <button class="btn-route">Créer le meilleur itinéraire</button>
                </div>

                <div class="steps-scroll-area" id="itinerary-list"></div>
            </aside>

            <main class="map-area">
                <div id="map"></div>
            </main>
        </div>
    </div>

    <!-- Passage des données PHP → JS -->
    <script>
        const barsData = <?= json_encode($bars_reference, JSON_UNESCAPED_UNICODE) ?>;
        const avisData = <?= json_encode($public_bars, JSON_UNESCAPED_UNICODE) ?>;
    </script>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="/Beers-App/Barathon/barathon.js"></script>
</body>
</html>