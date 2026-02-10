<?php
session_start();
session_destroy(); // On efface tout
header('Location: /Beers-App/index.html'); // Retour à l'accueil
exit();
?>