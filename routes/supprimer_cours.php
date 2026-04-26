<?php

require_once __DIR__ . '/../backend/utils.php';
require_once __DIR__ . '/../backend/read.php';
require_once __DIR__ . '/../backend/write.php';
exigerAuthentification();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	rediriger('/frontend/pages/afficher_donnees.php?error=Méthode non autorisée');
}

$id = nettoyerDonnees($_POST['id'] ?? '');

if ($id === '' || !trouverCours($id)) {
	rediriger('/frontend/pages/afficher_donnees.php?error=Cours introuvable');
}

if (supprimerCours($id)) {
	rediriger('/frontend/pages/afficher_donnees.php?message=Cours supprimé avec succès');
}

rediriger('/frontend/pages/afficher_donnees.php?error=Erreur lors de la suppression du cours');
