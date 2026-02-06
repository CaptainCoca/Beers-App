<?php
    // Connexion à Laragon
    $host = 'localhost';
    $dbname = 'maitre_houblon';
    $username = 'root';
    $password = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Récupération des bières
        $query = $pdo->query("SELECT * FROM beers");
        $beers = $query->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }

    include 'beer-list-view.php';
?>