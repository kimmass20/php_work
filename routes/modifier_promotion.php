<?php

require_once __DIR__ . '/../backend/utils.php';
require_once __DIR__ . '/../backend/read.php';
require_once __DIR__ . '/../backend/write.php';
exigerAuthentification();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	rediriger('/frontend/pages/afficher_donnees.php?error=Méthode non autorisée');
}

$id = nettoyerDonnees($_POST['id'] ?? '');
$nom = nettoyerDonnees($_POST['nom'] ?? '');
$nombreEtudiants = nettoyerDonnees($_POST['nombreEtudiants'] ?? '');

if ($id === '' || $nom === '' || $nombreEtudiants === '') {
	rediriger('/frontend/pages/modifier_promotion.php?id=' . urlencode($id) . '&error=Tous les champs sont requis');
}

if (!trouverPromotion($id)) {
	rediriger('/frontend/pages/afficher_donnees.php?error=Promotion introuvable');
}

if (!is_numeric($nombreEtudiants) || (int)$nombreEtudiants < 1) {
	rediriger('/frontend/pages/modifier_promotion.php?id=' . urlencode($id) . '&error=Le nombre d\'étudiants doit être positif');
}

if (modifierPromotion($id, $nom, (int)$nombreEtudiants)) {
	rediriger('/frontend/pages/afficher_donnees.php?message=Promotion modifiée avec succès');
}

rediriger('/frontend/pages/modifier_promotion.php?id=' . urlencode($id) . '&error=Erreur lors de la modification de la promotion');
