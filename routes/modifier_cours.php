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
$type = nettoyerDonnees($_POST['type'] ?? '');
$promotionId = nettoyerDonnees($_POST['promotionId'] ?? '');
$optionId = nettoyerDonnees($_POST['optionId'] ?? '');

if ($id === '' || $nom === '' || $type === '' || $promotionId === '') {
	rediriger('/frontend/pages/modifier_cours.php?id=' . urlencode($id) . '&error=Tous les champs sont requis');
}

if (!trouverCours($id)) {
	rediriger('/frontend/pages/afficher_donnees.php?error=Cours introuvable');
}

if (!trouverPromotion($promotionId)) {
	rediriger('/frontend/pages/modifier_cours.php?id=' . urlencode($id) . '&error=Promotion invalide');
}

if (!in_array($type, ['obligatoire', 'optionnel'], true)) {
	rediriger('/frontend/pages/modifier_cours.php?id=' . urlencode($id) . '&error=Type de cours invalide');
}

if ($type === 'optionnel') {
		if ($optionId === '' || !trouverOption($optionId)) {
			rediriger('/frontend/pages/modifier_cours.php?id=' . urlencode($id) . '&error=Une option valide est requise');
		}
} else {
	$optionId = null;
}

if (modifierCours($id, $nom, $type, $promotionId, $optionId)) {
	rediriger('/frontend/pages/afficher_donnees.php?message=Cours modifié avec succès');
}

rediriger('/frontend/pages/modifier_cours.php?id=' . urlencode($id) . '&error=Erreur lors de la modification du cours');
