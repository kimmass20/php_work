<?php

require_once __DIR__ . '/../backend/utils.php';
require_once __DIR__ . '/../backend/read.php';
require_once __DIR__ . '/../backend/write.php';
exigerAuthentification();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	rediriger('/frontend/pages/afficher_donnees.php?error=Méthode non autorisée');
}

$id = nettoyerDonnees($_POST['id'] ?? '');

if ($id === '' || !trouverSalle($id)) {
	rediriger('/frontend/pages/afficher_donnees.php?error=Salle introuvable');
}

if (supprimerSalle($id)) {
	rediriger('/frontend/pages/afficher_donnees.php?message=Salle supprimée avec succès');
}

rediriger('/frontend/pages/afficher_donnees.php?error=Erreur lors de la suppression de la salle');
