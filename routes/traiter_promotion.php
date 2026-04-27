<?php

require_once __DIR__ . '/../backend/utils.php';
require_once __DIR__ . '/../backend/write.php';
exigerAuthentification();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	rediriger('/frontend/pages/ajouter_promotion.php', 'Méthode non autorisée');
}

$nom = nettoyerDonnees($_POST['nom'] ?? '');
$nombreEtudiants = nettoyerDonnees($_POST['nombreEtudiants'] ?? '');

if ($nom === '' || $nombreEtudiants === '') {
	rediriger('/frontend/pages/ajouter_promotion.php?error=Tous les champs sont requis');
}

if (!is_numeric($nombreEtudiants) || (int)$nombreEtudiants < 1) {
	rediriger('/frontend/pages/ajouter_promotion.php?error=Le nombre d\'étudiants doit être un nombre positif');
}

if (ajouterPromotion($nom, (int)$nombreEtudiants)) {
	rediriger('/frontend/pages/afficher_donnees.php?message=Promotion ajoutée avec succès');
}

rediriger('/frontend/pages/ajouter_promotion.php?error=Erreur lors de l\'ajout de la promotion');

