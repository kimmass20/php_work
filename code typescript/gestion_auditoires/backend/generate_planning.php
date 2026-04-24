<?php

require_once 'read.php';
require_once 'write.php';

/**
 * Génère automatiquement un planning
 */
function genererPlanning() {
    $cours = obtenirCours();
    $salles = obtenirSalles();
    $promotions = obtenirPromotions();

    $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'];
    $horaires = ['08:00', '10:00', '12:00', '14:00', '16:00'];

    $planning = [];

    foreach ($cours as $index => $c) {
        $promotion = trouverPromotion($c['promotionId']);

        if (!$promotion) continue;

        // Trouver une salle avec capacité suffisante
        $salleAssignee = null;
        foreach ($salles as $salle) {
            if ($salle['capacite'] >= $promotion['nombreEtudiants']) {
                $salleAssignee = $salle;
                break;
            }
        }

        if (!$salleAssignee) continue;

        // Calculer jour et horaire
        $jourIndex = $index % count($jours);
        $horaireIndex = floor($index / count($jours)) % count($horaires);

        $planning[] = [
            'jour' => $jours[$jourIndex],
            'horaire' => $horaires[$horaireIndex],
            'cours' => $c['nom'],
            'promotion' => $promotion['nom'],
            'salle' => $salleAssignee['nom']
        ];
    }

    sauvegarderPlanning($planning);
    return $planning;
}
