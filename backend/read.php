<?php

function lireDonnees($fichier) {
	$chemin = __DIR__ . '/../data/' . $fichier;

	if (!file_exists($chemin)) {
		return [];
	}

	$contenu = file_get_contents($chemin);
	if ($contenu === false || trim($contenu) === '') {
		return [];
	}

	$contenu = preg_replace('/^\xEF\xBB\xBF/', '', $contenu);

	$donnees = json_decode($contenu, true);
	return is_array($donnees) ? $donnees : [];
}

function obtenirSalles() {
	return lireDonnees('salles.json');
}

function obtenirPromotions() {
	return lireDonnees('promotion.json');
}

function obtenirCours() {
	return lireDonnees('cours.json');
}

function obtenirOptions() {
	return lireDonnees('option.json');
}

function obtenirPlanning() {
	return lireDonnees('planning.json');
}

function trouverCours($id) {
	$cours = obtenirCours();
	foreach ($cours as $coursItem) {
		if (($coursItem['id'] ?? '') === $id) {
			return $coursItem;
		}
	}
	return null;
}

function trouverPromotion($id) {
	$promotions = obtenirPromotions();
	foreach ($promotions as $promo) {
		if (($promo['id'] ?? '') === $id) {
			return $promo;
		}
	}
	return null;
}

function trouverSalle($id) {
	$salles = obtenirSalles();
	foreach ($salles as $salle) {
		if (($salle['id'] ?? '') === $id) {
			return $salle;
		}
	}
	return null;
}

function trouverOption($id) {
	$options = obtenirOptions();
	foreach ($options as $option) {
		if (($option['id'] ?? '') === $id) {
			return $option;
		}
	}
	return null;
}

