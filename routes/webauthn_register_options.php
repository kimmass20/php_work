<?php
require_once __DIR__ . '/../backend/utils.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	envoyerJSON(['error' => 'Méthode non autorisée'], 405);
}

$username = nettoyerDonnees($_POST['username'] ?? authDefaultUsername());
if ($username === '') {
	envoyerJSON(['error' => 'Nom utilisateur requis'], 400);
}

$challenge = webauthnCreateChallenge();
webauthnSetPendingChallenge('register_' . $username, $challenge);

$existing = webauthnGetUserCredentials($username);
$excludeCredentials = [];
foreach ($existing as $credential) {
	if (!isset($credential['id'])) {
		continue;
	}
	$excludeCredentials[] = [
		'id' => $credential['id'],
		'type' => 'public-key',
	];
}

envoyerJSON([
	'challenge' => $challenge,
	'rp' => [
		'name' => 'Gestion des Auditoires',
		'id' => webauthnRpId(),
	],
	'user' => [
		'id' => base64UrlEncode($username),
		'name' => $username,
		'displayName' => $username,
	],
	'pubKeyCredParams' => [
		['type' => 'public-key', 'alg' => -7],
		['type' => 'public-key', 'alg' => -257],
	],
	'timeout' => 60000,
	'attestation' => 'none',
	'authenticatorSelection' => [
		'residentKey' => 'preferred',
		'userVerification' => 'required',
		'authenticatorAttachment' => 'platform',
	],
	'excludeCredentials' => $excludeCredentials,
]);
