<?php
session_start();
header('Content-Type: application/json');

// On vérifie si la session existe
if (isset($_SESSION['pseudo'])) {
    echo json_encode([
        'connected' => true, 
        'pseudo' => $_SESSION['pseudo']
    ]);
} else {
    echo json_encode(['connected' => false]);
}
?>