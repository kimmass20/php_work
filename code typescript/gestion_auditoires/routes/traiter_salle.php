<?php

require_once '../backend/utils.php';
require_once '../backend/write.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    rediriger('/frontend/pages/ajouter_salle.php', 'Méthode non autorisée');
}

$nom = nettoyerDonnees($_POST['nom'] ?? '');
$capacite = nettoyerDonnees($_POST['capacite'] ?? '');

if (empty($nom) || empty($capacite)) {
    rediriger('/frontend/pages/ajouter_salle.php?error=Tous les champs sont requis');
}

if (!is_numeric($capacite) || $capacite < 1) {
    rediriger('/frontend/pages/ajouter_salle.php?error=La capacité doit être un nombre positif');
}

if (ajouterSalle($nom, $capacite)) {
    rediriger('/frontend/pages/afficher_donnees.php?message=Salle ajoutée avec succès');
} else {
    rediriger('/frontend/pages/ajouter_salle.php?error=Erreur lors de l\'ajout de la salle');
}
