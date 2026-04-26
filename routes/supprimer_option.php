<?php

require_once __DIR__ . '/../backend/utils.php';
require_once __DIR__ . '/../backend/read.php';
require_once __DIR__ . '/../backend/write.php';
exigerAuthentification();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	rediriger('/frontend/pages/afficher_donnees.php?error=Méthode non autorisée');
}

$id = nettoyerDonnees($_POST['id'] ?? '');

if ($id === '' || !trouverOption($id)) {
	rediriger('/frontend/pages/afficher_donnees.php?error=Option introuvable');
}

foreach (obtenirCours() as $cours) {
	if (($cours['optionId'] ?? '') === $id) {
		rediriger('/frontend/pages/afficher_donnees.php?error=Impossible de supprimer une option liée à des cours');
	}
}

if (supprimerOption($id)) {
	rediriger('/frontend/pages/afficher_donnees.php?message=Option supprimée avec succès');
}

rediriger('/frontend/pages/afficher_donnees.php?error=Erreur lors de la suppression de l\'option');
