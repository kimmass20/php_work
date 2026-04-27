<?php

require_once __DIR__ . '/utils.php';
require_once __DIR__ . '/read.php';

function ecrireDonnees($fichier, $donnees) {
	$chemin = __DIR__ . '/../data/' . $fichier;
	$json = json_encode($donnees, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
	return file_put_contents($chemin, $json) !== false;
}

function reinitialiserPlanning() {
	return ecrireDonnees('planning.json', []);
}

function mettreAJourEntite($fichier, $id, $callback) {
	$elements = lireDonnees($fichier);
	$modifie = false;

	foreach ($elements as $index => $element) {
		if (($element['id'] ?? '') !== $id) {
			continue;
		}

		$elements[$index] = $callback($element);
		$modifie = true;
		break;
	}

	if (!$modifie || !ecrireDonnees($fichier, $elements)) {
		return false;
	}

	reinitialiserPlanning();
	return true;
}

function supprimerEntite($fichier, $id) {
	$elements = lireDonnees($fichier);
	$restants = array_values(array_filter($elements, function ($element) use ($id) {
		return ($element['id'] ?? '') !== $id;
	}));

	if (count($restants) === count($elements)) {
		return false;
	}

	if (!ecrireDonnees($fichier, $restants)) {
		return false;
	}

	reinitialiserPlanning();
	return true;
}

function ajouterSalle($nom, $capacite) {
	$salles = obtenirSalles();

	$salles[] = [
		'id' => genererID(),
		'nom' => $nom,
		'capacite' => (int)$capacite,
	];

	if (!ecrireDonnees('salles.json', $salles)) {
		return false;
	}

	reinitialiserPlanning();
	return true;
}

function ajouterPromotion($nom, $nombreEtudiants) {
	$promotions = obtenirPromotions();

	$promotions[] = [
		'id' => genererID(),
		'nom' => $nom,
		'nombreEtudiants' => (int)$nombreEtudiants,
	];

	if (!ecrireDonnees('promotion.json', $promotions)) {
		return false;
	}

	reinitialiserPlanning();
	return true;
}

function ajouterOption($nom) {
	$options = obtenirOptions();

	$options[] = [
		'id' => genererID(),
		'nom' => $nom,
	];

	if (!ecrireDonnees('option.json', $options)) {
		return false;
	}

	reinitialiserPlanning();
	return true;
}

function ajouterCours($nom, $type, $promotionId, $optionId = null) {
	$cours = obtenirCours();

	$cours[] = [
		'id' => genererID(),
		'nom' => $nom,
		'type' => $type,
		'promotionId' => $promotionId,
		'optionId' => $optionId,
	];

	if (!ecrireDonnees('cours.json', $cours)) {
		return false;
	}

	reinitialiserPlanning();
	return true;
}

function modifierSalle($id, $nom, $capacite) {
	return mettreAJourEntite('salles.json', $id, function ($salle) use ($nom, $capacite) {
		$salle['nom'] = $nom;
		$salle['capacite'] = (int)$capacite;
		return $salle;
	});
}

function modifierPromotion($id, $nom, $nombreEtudiants) {
	return mettreAJourEntite('promotion.json', $id, function ($promotion) use ($nom, $nombreEtudiants) {
		$promotion['nom'] = $nom;
		$promotion['nombreEtudiants'] = (int)$nombreEtudiants;
		return $promotion;
	});
}

function modifierOption($id, $nom) {
	return mettreAJourEntite('option.json', $id, function ($option) use ($nom) {
		$option['nom'] = $nom;
		return $option;
	});
}

function modifierCours($id, $nom, $type, $promotionId, $optionId = null) {
	return mettreAJourEntite('cours.json', $id, function ($cours) use ($nom, $type, $promotionId, $optionId) {
		$cours['nom'] = $nom;
		$cours['type'] = $type;
		$cours['promotionId'] = $promotionId;
		$cours['optionId'] = $optionId;
		return $cours;
	});
}

function supprimerSalle($id) {
	return supprimerEntite('salles.json', $id);
}

function supprimerPromotion($id) {
	foreach (obtenirCours() as $cours) {
		if (($cours['promotionId'] ?? '') === $id) {
			return false;
		}
	}

	return supprimerEntite('promotion.json', $id);
}

function supprimerOption($id) {
	foreach (obtenirCours() as $cours) {
		if (($cours['optionId'] ?? '') === $id) {
			return false;
		}
	}

	return supprimerEntite('option.json', $id);
}

function supprimerCours($id) {
	return supprimerEntite('cours.json', $id);
}

function sauvegarderPlanning($planning) {
	return ecrireDonnees('planning.json', $planning);
}

