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
$authenticatorDataB64 = (string)($payload['authenticatorData'] ?? '');
$signatureB64 = (string)($payload['signature'] ?? '');

if ($username === '' || $credentialId === '' || $clientDataJSONB64 === '' || $authenticatorDataB64 === '' || $signatureB64 === '') {
	envoyerJSON(['error' => 'Données incomplètes'], 400);
}

$expectedChallenge = webauthnConsumePendingChallenge('auth_' . $username);
if (!$expectedChallenge) {
	envoyerJSON(['error' => 'Challenge introuvable ou expiré'], 400);
}

$found = webauthnFindCredentialById($credentialId);
if (!$found || ($found['username'] ?? '') !== $username) {
	envoyerJSON(['error' => 'Credential inconnu'], 404);
}

$publicKeyPem = (string)($found['credential']['publicKeyPem'] ?? '');
if ($publicKeyPem === '') {
	envoyerJSON(['error' => 'Clé publique manquante'], 400);
}

$clientDataJSON = base64UrlDecode($clientDataJSONB64);
$authenticatorData = base64UrlDecode($authenticatorDataB64);
$signature = base64UrlDecode($signatureB64);

if ($clientDataJSON === false || $authenticatorData === false || $signature === false) {
	envoyerJSON(['error' => 'Encodage invalide'], 400);
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

if ($clientType !== 'webauthn.get') {
	envoyerJSON(['error' => 'Type d\'assertion invalide'], 400);
}

if (!hash_equals(webauthnOrigin(), $clientOrigin)) {
	envoyerJSON(['error' => 'Origin non autorisée'], 400);
}

if (strlen($authenticatorData) < 37) {
	envoyerJSON(['error' => 'Authenticator data invalide'], 400);
}

$rpIdHash = substr($authenticatorData, 0, 32);
$expectedRpIdHash = hash('sha256', webauthnRpId(), true);
if (!hash_equals($expectedRpIdHash, $rpIdHash)) {
	envoyerJSON(['error' => 'RP ID invalide'], 400);
}

$flags = ord($authenticatorData[32]);
$isUserPresent = ($flags & 0x01) === 0x01;
if (!$isUserPresent) {
	envoyerJSON(['error' => 'Présence utilisateur requise'], 400);
}

$counterBytes = substr($authenticatorData, 33, 4);
$counterData = unpack('Ncounter', $counterBytes);
$newCounter = (int)($counterData['counter'] ?? 0);
$storedCounter = (int)($found['credential']['counter'] ?? 0);
if ($storedCounter > 0 && $newCounter <= $storedCounter) {
	envoyerJSON(['error' => 'Compteur de sécurité invalide'], 400);
}

$signedData = $authenticatorData . hash('sha256', $clientDataJSON, true);
$verifyResult = openssl_verify($signedData, $signature, $publicKeyPem, OPENSSL_ALGO_SHA256);
if ($verifyResult !== 1) {
	envoyerJSON(['error' => 'Signature invalide'], 401);
}

webauthnUpdateCredentialCounter($username, $credentialId, $newCounter);
$_SESSION['is_authenticated'] = true;
$_SESSION['auth_username'] = $username;

envoyerJSON(['ok' => true, 'redirect' => appUrl('/index.php')]);
