<?php

require_once '../backend/utils.php';
require_once '../backend/write.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    rediriger('/frontend/pages/ajouter_promotion.php', 'Méthode non autorisée');
}

$nom = nettoyerDonnees($_POST['nom'] ?? '');
$nombreEtudiants = nettoyerDonnees($_POST['nombreEtudiants'] ?? '');

if (empty($nom) || empty($nombreEtudiants)) {
    rediriger('/frontend/pages/ajouter_promotion.php?error=Tous les champs sont requis');
}

if (!is_numeric($nombreEtudiants) || $nombreEtudiants < 1) {
    rediriger('/frontend/pages/ajouter_promotion.php?error=Le nombre d\'étudiants doit être un nombre positif');
}

if (ajouterPromotion($nom, $nombreEtudiants)) {
    rediriger('/frontend/pages/afficher_donnees.php?message=Promotion ajoutée avec succès');
} else {
    rediriger('/frontend/pages/ajouter_promotion.php?error=Erreur lors de l\'ajout de la promotion');
}
