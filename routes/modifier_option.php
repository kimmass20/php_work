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

if ($id === '' || $nom === '') {
	rediriger('/frontend/pages/modifier_option.php?id=' . urlencode($id) . '&error=Le nom de l\'option est requis');
}

if (!trouverOption($id)) {
	rediriger('/frontend/pages/afficher_donnees.php?error=Option introuvable');
}

if (modifierOption($id, $nom)) {
	rediriger('/frontend/pages/afficher_donnees.php?message=Option modifiée avec succès');
}

rediriger('/frontend/pages/modifier_option.php?id=' . urlencode($id) . '&error=Erreur lors de la modification de l\'option');
