<?php

require_once __DIR__ . '/../backend/utils.php';
require_once __DIR__ . '/../backend/write.php';
exigerAuthentification();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	rediriger('/frontend/pages/ajouter_cours.php', 'Méthode non autorisée');
}

$nom = nettoyerDonnees($_POST['nom'] ?? '');
$type = nettoyerDonnees($_POST['type'] ?? '');
$promotionId = nettoyerDonnees($_POST['promotionId'] ?? '');
$optionId = nettoyerDonnees($_POST['optionId'] ?? '');

if ($nom === '' || $type === '' || $promotionId === '') {
	rediriger('/frontend/pages/ajouter_cours.php?error=Tous les champs sont requis');
}

if (!in_array($type, ['obligatoire', 'optionnel'], true)) {
	rediriger('/frontend/pages/ajouter_cours.php?error=Type de cours invalide');
}

if (!trouverPromotion($promotionId)) {
	rediriger('/frontend/pages/ajouter_cours.php?error=Promotion introuvable');
}

if ($type === 'optionnel') {
	if ($optionId === '') {
		rediriger('/frontend/pages/ajouter_cours.php?error=Une option est requise pour un cours optionnel');
	}

	if (!trouverOption($optionId)) {
		rediriger('/frontend/pages/ajouter_cours.php?error=Option invalide');
	}
} else {
	$optionId = null;
}

if (ajouterCours($nom, $type, $promotionId, $optionId)) {
	rediriger('/frontend/pages/afficher_donnees.php?message=Cours ajouté avec succès');
}

rediriger('/frontend/pages/ajouter_cours.php?error=Erreur lors de l\'ajout du cours');

