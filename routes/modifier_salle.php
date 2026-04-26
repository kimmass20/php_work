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
$capacite = nettoyerDonnees($_POST['capacite'] ?? '');

if ($id === '' || $nom === '' || $capacite === '') {
	rediriger('/frontend/pages/modifier_salle.php?id=' . urlencode($id) . '&error=Tous les champs sont requis');
}

if (!trouverSalle($id)) {
	rediriger('/frontend/pages/afficher_donnees.php?error=Salle introuvable');
}

if (!is_numeric($capacite) || (int)$capacite < 1) {
	rediriger('/frontend/pages/modifier_salle.php?id=' . urlencode($id) . '&error=La capacité doit être un nombre positif');
}

if (modifierSalle($id, $nom, (int)$capacite)) {
	rediriger('/frontend/pages/afficher_donnees.php?message=Salle modifiée avec succès');
}

rediriger('/frontend/pages/modifier_salle.php?id=' . urlencode($id) . '&error=Erreur lors de la modification de la salle');
