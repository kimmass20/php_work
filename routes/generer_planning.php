<?php

require_once __DIR__ . '/../backend/utils.php';
require_once __DIR__ . '/../backend/generate_planning.php';
exigerAuthentification();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    rediriger('/index.php');
}

$resultat = genererPlanning();
$nombrePlanifies = count($resultat['planning'] ?? []);
$nombreNonPlanifies = count($resultat['nonPlanifies'] ?? []);

$message = 'Planning généré avec succès';
if ($nombreNonPlanifies > 0) {
	$message = 'Planning généré: ' . $nombrePlanifies . ' cours planifiés, ' . $nombreNonPlanifies . ' non planifiés';
}

rediriger('/frontend/pages/afficher_planning.php', $message);

