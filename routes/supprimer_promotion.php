<?php

require_once __DIR__ . '/../backend/utils.php';
require_once __DIR__ . '/../backend/read.php';
require_once __DIR__ . '/../backend/write.php';
exigerAuthentification();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	rediriger('/frontend/pages/afficher_donnees.php?error=Méthode non autorisée');
}

$id = nettoyerDonnees($_POST['id'] ?? '');

if ($id === '' || !trouverPromotion($id)) {
	rediriger('/frontend/pages/afficher_donnees.php?error=Promotion introuvable');
}

foreach (obtenirCours() as $cours) {
	if (($cours['promotionId'] ?? '') === $id) {
		rediriger('/frontend/pages/afficher_donnees.php?error=Impossible de supprimer une promotion liée à des cours');
	}
}

if (supprimerPromotion($id)) {
	rediriger('/frontend/pages/afficher_donnees.php?message=Promotion supprimée avec succès');
}

rediriger('/frontend/pages/afficher_donnees.php?error=Erreur lors de la suppression de la promotion');
