<?php
require_once __DIR__ . '/../backend/utils.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	envoyerJSON(['error' => 'Méthode non autorisée'], 405);
}

$payloadRaw = file_get_contents('php://input');
$payload = json_decode($payloadRaw, true);

if (!is_array($payload)) {
	envoyerJSON(['error' => 'Payload invalide'], 400);
}

$username = nettoyerDonnees($payload['username'] ?? authDefaultUsername());
$credentialId = (string)($payload['credentialId'] ?? '');
$clientDataJSONB64 = (string)($payload['clientDataJSON'] ?? '');
$publicKeyPem = (string)($payload['publicKeyPem'] ?? '');

if ($username === '' || $credentialId === '' || $clientDataJSONB64 === '' || $publicKeyPem === '') {
	envoyerJSON(['error' => 'Données incomplètes'], 400);
}

$expectedChallenge = webauthnConsumePendingChallenge('register_' . $username);
if (!$expectedChallenge) {
	envoyerJSON(['error' => 'Challenge introuvable ou expiré'], 400);
}

$clientDataJSON = base64UrlDecode($clientDataJSONB64);
if ($clientDataJSON === false) {
	envoyerJSON(['error' => 'clientDataJSON invalide'], 400);
}

$clientData = json_decode($clientDataJSON, true);
if (!is_array($clientData)) {
	envoyerJSON(['error' => 'clientDataJSON non lisible'], 400);
}

$clientChallenge = (string)($clientData['challenge'] ?? '');
$clientType = (string)($clientData['type'] ?? '');
$clientOrigin = (string)($clientData['origin'] ?? '');

if (!hash_equals($expectedChallenge, $clientChallenge)) {
	envoyerJSON(['error' => 'Challenge non valide'], 400);
}

if ($clientType !== 'webauthn.create') {
	envoyerJSON(['error' => 'Type d\'attestation invalide'], 400);
}

if (!hash_equals(webauthnOrigin(), $clientOrigin)) {
	envoyerJSON(['error' => 'Origin non autorisée'], 400);
}

if (strpos($publicKeyPem, 'BEGIN PUBLIC KEY') === false) {
	envoyerJSON(['error' => 'Clé publique invalide'], 400);
}

if (!webauthnStoreCredential($username, $credentialId, $publicKeyPem, 0)) {
	envoyerJSON(['error' => 'Impossible de sauvegarder la passkey'], 500);
}

envoyerJSON(['ok' => true, 'message' => 'Passkey enregistrée avec succès']);
