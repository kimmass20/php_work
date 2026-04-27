<?php

require_once __DIR__ . '/read.php';
require_once __DIR__ . '/write.php';

function genererPlanning() {
	$cours = obtenirCours();
	$salles = obtenirSalles();

	$jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'];
	$horaires = ['08:00', '10:00', '12:00', '14:00', '16:00'];

	$planning = [];
	$nonPlanifies = [];
	$occupationSalles = [];
	$occupationPromotions = [];

	usort($salles, function ($a, $b) {
		return (($a['capacite'] ?? 0) <=> ($b['capacite'] ?? 0));
	});

	foreach ($cours as $c) {
		$promotionId = $c['promotionId'] ?? '';
		$promotion = trouverPromotion($promotionId);

		if (!$promotion) {
			$nonPlanifies[] = [
				'cours' => $c['nom'] ?? 'Cours',
				'raison' => 'Promotion introuvable',
			];
			continue;
		}

		$sallesCompatibles = [];
		foreach ($salles as $salle) {
			if (($salle['capacite'] ?? 0) >= ($promotion['nombreEtudiants'] ?? 0)) {
				$sallesCompatibles[] = $salle;
			}
		}

		if (empty($sallesCompatibles)) {
			$nonPlanifies[] = [
				'cours' => $c['nom'] ?? 'Cours',
				'promotion' => $promotion['nom'] ?? 'Promotion',
				'raison' => 'Aucune salle adaptée',
			];
			continue;
		}

		$creneauTrouve = false;

		foreach ($jours as $jour) {
			foreach ($horaires as $horaire) {
				$cleCreneau = $jour . '|' . $horaire;

				if (isset($occupationPromotions[$cleCreneau][$promotionId])) {
					continue;
				}

				foreach ($sallesCompatibles as $salle) {
					$salleId = $salle['id'] ?? '';

					if ($salleId === '' || isset($occupationSalles[$cleCreneau][$salleId])) {
						continue;
					}

					$planning[] = [
						'jour' => $jour,
						'horaire' => $horaire,
						'cours' => $c['nom'] ?? 'Cours',
						'promotion' => $promotion['nom'] ?? 'Promotion',
						'salle' => $salle['nom'] ?? 'Salle',
					];

					$occupationSalles[$cleCreneau][$salleId] = true;
					$occupationPromotions[$cleCreneau][$promotionId] = true;
					$creneauTrouve = true;
					break 3;
				}
			}
		}

		if (!$creneauTrouve) {
			$nonPlanifies[] = [
				'cours' => $c['nom'] ?? 'Cours',
				'promotion' => $promotion['nom'] ?? 'Promotion',
				'raison' => 'Aucun créneau disponible',
			];
		}
	}

	sauvegarderPlanning($planning);
	return [
		'planning' => $planning,
		'nonPlanifies' => $nonPlanifies,
	];
}

