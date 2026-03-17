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
    <body>
        <div class="barathon-layout">
            <aside class="barathon-sidebar">
                <div class="search-box">
                    <input type="text" id="bar-search" placeholder="Chercher une escale...">
                    <button class="btn-arrow">Rechercher</button>
                </div>

                <div id="bar-details" class="details-container">
                    <h2 id="detail-name">Nom du bar</h2>
                    <div id="detail-rating">⭐⭐⭐⭐⭐ 5.0</div>
                    <p id="detail-status"><span class="open">Ouvert</span> Ferme à 2:00</p>
                    <p id="detail-phone">07 57 54 15 48</p>
            
                    <div class="quote-box">
                        <p>"Ce bar est exceptionnel, comme moi"</p>
                    </div>

                    <button class="btn-route">Créer le meilleur itinéraire</button>
                </div>

                <div class="steps-scroll-area" id="itinerary-list">
                    </div>
            </aside>

            <main class="map-area">
                <div id="map"></div>
            </main>
        </div>
    </body>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="/Beers-App/Barathon/barathon.js"></script>
</html>


