<?php

require_once '../backend/utils.php';
require_once '../backend/write.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    rediriger('/frontend/pages/ajouter_cours.php', 'Méthode non autorisée');
}

$nom = nettoyerDonnees($_POST['nom'] ?? '');
$type = nettoyerDonnees($_POST['type'] ?? '');
$promotionId = nettoyerDonnees($_POST['promotionId'] ?? '');

if (empty($nom) || empty($type) || empty($promotionId)) {
    rediriger('/frontend/pages/ajouter_cours.php?error=Tous les champs sont requis');
}

if (!in_array($type, ['obligatoire', 'optionnel'])) {
    rediriger('/frontend/pages/ajouter_cours.php?error=Type de cours invalide');
}

if (ajouterCours($nom, $type, $promotionId)) {
    rediriger('/frontend/pages/afficher_donnees.php?message=Cours ajouté avec succès');
} else {
    rediriger('/frontend/pages/ajouter_cours.php?error=Erreur lors de l\'ajout du cours');
}
