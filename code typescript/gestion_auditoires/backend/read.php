<?php

/**
 * Lit les données d'un fichier JSON
 */
function lireDonnees($fichier) {
    $chemin = __DIR__ . '/../data/' . $fichier;

    if (!file_exists($chemin)) {
        return [];
    }

    $contenu = file_get_contents($chemin);
    $donnees = json_decode($contenu, true);

    return $donnees ?: [];
}

/**
 * Récupère toutes les salles
 */
function obtenirSalles() {
    return lireDonnees('salles.json');
}

/**
 * Récupère toutes les promotions
 */
function obtenirPromotions() {
    return lireDonnees('promotions.json');
}

/**
 * Récupère tous les cours
 */
function obtenirCours() {
    return lireDonnees('cours.json');
}

/**
 * Récupère le planning
 */
function obtenirPlanning() {
    return lireDonnees('planning.json');
}

/**
 * Trouve une promotion par ID
 */
function trouverPromotion($id) {
    $promotions = obtenirPromotions();
    foreach ($promotions as $promo) {
        if ($promo['id'] === $id) {
            return $promo;
        }
    }
    return null;
}

/**
 * Trouve une salle par ID
 */
function trouverSalle($id) {
    $salles = obtenirSalles();
    foreach ($salles as $salle) {
        if ($salle['id'] === $id) {
            return $salle;
        }
    }
    return null;
}
