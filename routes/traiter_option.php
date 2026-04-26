<?php

require_once __DIR__ . '/../backend/utils.php';
require_once __DIR__ . '/../backend/write.php';
exigerAuthentification();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	rediriger('/frontend/pages/ajouter_option.php', 'Méthode non autorisée');
}

$nom = nettoyerDonnees($_POST['nom'] ?? '');

if ($nom === '') {
	rediriger('/frontend/pages/ajouter_option.php?error=Le nom de l\'option est requis');
}

if (ajouterOption($nom)) {
	rediriger('/frontend/pages/afficher_donnees.php?message=Option ajoutée avec succès');
}

rediriger('/frontend/pages/ajouter_option.php?error=Erreur lors de l\'ajout de l\'option');
