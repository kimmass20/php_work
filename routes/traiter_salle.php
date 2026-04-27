<?php

require_once __DIR__ . '/../backend/utils.php';
require_once __DIR__ . '/../backend/write.php';
exigerAuthentification();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	rediriger('/frontend/pages/ajouter_salle.php', 'Méthode non autorisée');
}

$nom = nettoyerDonnees($_POST['nom'] ?? '');
$capacite = nettoyerDonnees($_POST['capacite'] ?? '');

if ($nom === '' || $capacite === '') {
	rediriger('/frontend/pages/ajouter_salle.php?error=Tous les champs sont requis');
}

if (!is_numeric($capacite) || (int)$capacite < 1) {
	rediriger('/frontend/pages/ajouter_salle.php?error=La capacité doit être un nombre positif');
}

if (ajouterSalle($nom, (int)$capacite)) {
	rediriger('/frontend/pages/afficher_donnees.php?message=Salle ajoutée avec succès');
}

rediriger('/frontend/pages/ajouter_salle.php?error=Erreur lors de l\'ajout de la salle');

