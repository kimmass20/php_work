<?php

require_once 'read.php';

/**
 * Écrit les données dans un fichier JSON
 */
function ecrireDonnees($fichier, $donnees) {
    $chemin = __DIR__ . '/../data/' . $fichier;
    $json = json_encode($donnees, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    return file_put_contents($chemin, $json) !== false;
}

/**
 * Ajoute une salle
 */
function ajouterSalle($nom, $capacite) {
    $salles = obtenirSalles();

    $nouvelleSalle = [
        'id' => genererID(),
        'nom' => $nom,
        'capacite' => (int)$capacite
    ];

    $salles[] = $nouvelleSalle;
    return ecrireDonnees('salles.json', $salles);
}

/**
 * Ajoute une promotion
 */
function ajouterPromotion($nom, $nombreEtudiants) {
    $promotions = obtenirPromotions();

    $nouvellePromo = [
        'id' => genererID(),
        'nom' => $nom,
        'nombreEtudiants' => (int)$nombreEtudiants
    ];

    $promotions[] = $nouvellePromo;
    return ecrireDonnees('promotions.json', $promotions);
}

/**
 * Ajoute un cours
 */
function ajouterCours($nom, $type, $promotionId) {
    $cours = obtenirCours();

    $nouveauCours = [
        'id' => genererID(),
        'nom' => $nom,
        'type' => $type,
        'promotionId' => $promotionId
    ];

    $cours[] = $nouveauCours;
    return ecrireDonnees('cours.json', $cours);
}

/**
 * Sauvegarde le planning
 */
function sauvegarderPlanning($planning) {
    return ecrireDonnees('planning.json', $planning);
}
