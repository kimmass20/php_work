<?php
require_once __DIR__ . '/../backend/utils.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	envoyerJSON(['error' => 'Méthode non autorisée'], 405);
}

$username = nettoyerDonnees($_POST['username'] ?? authDefaultUsername());
if ($username === '') {
	envoyerJSON(['error' => 'Nom utilisateur requis'], 400);
}

$credentials = webauthnGetUserCredentials($username);
if (count($credentials) === 0) {
	envoyerJSON(['error' => 'Aucune passkey enregistrée pour cet utilisateur'], 404);
}

$challenge = webauthnCreateChallenge();
webauthnSetPendingChallenge('auth_' . $username, $challenge);

$allowCredentials = [];
foreach ($credentials as $credential) {
	if (!isset($credential['id'])) {
		continue;
	}
	$allowCredentials[] = [
		'id' => $credential['id'],
		'type' => 'public-key',
	];
}

envoyerJSON([
	'challenge' => $challenge,
	'timeout' => 60000,
	'rpId' => webauthnRpId(),
	'userVerification' => 'required',
	'allowCredentials' => $allowCredentials,
]);
