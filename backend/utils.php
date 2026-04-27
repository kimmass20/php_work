<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

function appBasePath() {
	static $basePath = null;

	if ($basePath !== null) {
		return $basePath;
	}

	$projectRoot = realpath(__DIR__ . '/..');
	$documentRoot = realpath($_SERVER['DOCUMENT_ROOT'] ?? '');

	if (!$projectRoot || !$documentRoot) {
		$basePath = '';
		return $basePath;
	}

	$projectRootNorm = str_replace('\\', '/', $projectRoot);
	$documentRootNorm = str_replace('\\', '/', $documentRoot);

	if (strpos($projectRootNorm, $documentRootNorm) !== 0) {
		$basePath = '';
		return $basePath;
	}

	$relative = substr($projectRootNorm, strlen($documentRootNorm));
	$relative = trim((string)$relative, '/');

	$basePath = $relative === '' ? '' : '/' . $relative;
	return $basePath;
}

function appUrl($path = '/') {
	if (preg_match('#^https?://#i', $path)) {
		return $path;
	}

	$normalizedPath = '/' . ltrim((string)$path, '/');
	return appBasePath() . $normalizedPath;
}

function genererID() {
	return uniqid('', true);
}

function nettoyerDonnees($data) {
	return htmlspecialchars(strip_tags(trim((string)$data)));
}

function envoyerJSON($data, $code = 200) {
	http_response_code($code);
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode($data, JSON_UNESCAPED_UNICODE);
	exit;
}

function rediriger($page, $message = '') {
	$url = (string)$page;

	if (!preg_match('#^https?://#i', $url)) {
		$url = appUrl($url);
	}

	if ($message !== '') {
		$separator = strpos($url, '?') === false ? '?' : '&';
		$url .= $separator . 'message=' . urlencode($message);
	}

	header('Location: ' . $url);
	exit;
}

function authDefaultUsername() {
	return $_ENV['APP_AUTH_USER'] ?? 'admin';
}

function authDefaultPassword() {
	return $_ENV['APP_AUTH_PASS'] ?? 'admin123';
}

function estAuthentifie() {
	return !empty($_SESSION['is_authenticated']);
}

function tenterConnexion($username, $password) {
	$validUsername = authDefaultUsername();
	$validPassword = authDefaultPassword();

	$isValidUser = hash_equals($validUsername, (string)$username);
	$isValidPass = hash_equals($validPassword, (string)$password);

	if (!$isValidUser || !$isValidPass) {
		return false;
	}

	$_SESSION['is_authenticated'] = true;
	$_SESSION['auth_username'] = $validUsername;

	return true;
}

function deconnecterUtilisateur() {
	$_SESSION = [];

	if (ini_get('session.use_cookies')) {
		$params = session_get_cookie_params();
		setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
	}

	session_destroy();
}

function exigerAuthentification() {
	if (estAuthentifie()) {
		return;
	}

	$requestedUri = $_SERVER['REQUEST_URI'] ?? appUrl('/index.php');
	$loginUrl = appUrl('/login.php');
	$separator = strpos($loginUrl, '?') === false ? '?' : '&';
	$redirectUrl = $loginUrl . $separator . 'redirect=' . urlencode($requestedUri);

	header('Location: ' . $redirectUrl);
	exit;
}

function base64UrlEncode($data) {
	return rtrim(strtr(base64_encode((string)$data), '+/', '-_'), '=');
}

function base64UrlDecode($data) {
	$normalized = strtr((string)$data, '-_', '+/');
	$padding = strlen($normalized) % 4;
	if ($padding > 0) {
		$normalized .= str_repeat('=', 4 - $padding);
	}
	return base64_decode($normalized, true);
}

function authDataPath() {
	return __DIR__ . '/../data/passkeys.json';
}

function lirePasskeys() {
	$path = authDataPath();
	if (!file_exists($path)) {
		return [];
	}

	$content = file_get_contents($path);
	if ($content === false || trim($content) === '') {
		return [];
	}

	$decoded = json_decode($content, true);
	return is_array($decoded) ? $decoded : [];
}

function ecrirePasskeys($passkeys) {
	$json = json_encode($passkeys, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
	return file_put_contents(authDataPath(), $json) !== false;
}

function webauthnRpId() {
	$host = $_SERVER['HTTP_HOST'] ?? '';
	$host = explode(':', $host)[0];
	return strtolower((string)$host);
}

function webauthnOrigin() {
	$https = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
	$scheme = $https ? 'https' : 'http';
	$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
	return $scheme . '://' . $host;
}

function webauthnCreateChallenge() {
	return base64UrlEncode(random_bytes(32));
}

function webauthnSetPendingChallenge($key, $challenge) {
	$_SESSION['webauthn_challenges'][$key] = $challenge;
}

function webauthnConsumePendingChallenge($key) {
	$challenge = $_SESSION['webauthn_challenges'][$key] ?? null;
	unset($_SESSION['webauthn_challenges'][$key]);
	return $challenge;
}

function webauthnGetUserCredentials($username) {
	$all = lirePasskeys();
	return $all[$username] ?? [];
}

function webauthnStoreCredential($username, $credentialId, $publicKeyPem, $counter = 0) {
	$all = lirePasskeys();
	if (!isset($all[$username]) || !is_array($all[$username])) {
		$all[$username] = [];
	}

	$updated = false;
	foreach ($all[$username] as $index => $credential) {
		if (($credential['id'] ?? '') === $credentialId) {
			$all[$username][$index]['publicKeyPem'] = $publicKeyPem;
			$all[$username][$index]['counter'] = (int)$counter;
			$updated = true;
			break;
		}
	}

	if (!$updated) {
		$all[$username][] = [
			'id' => $credentialId,
			'publicKeyPem' => $publicKeyPem,
			'counter' => (int)$counter,
		];
	}

	return ecrirePasskeys($all);
}

function webauthnFindCredentialById($credentialId) {
	$all = lirePasskeys();
	foreach ($all as $username => $credentials) {
		if (!is_array($credentials)) {
			continue;
		}
		foreach ($credentials as $credential) {
			if (($credential['id'] ?? '') === $credentialId) {
				return [
					'username' => $username,
					'credential' => $credential,
				];
			}
		}
	}
	return null;
}

function webauthnUpdateCredentialCounter($username, $credentialId, $counter) {
	$all = lirePasskeys();
	if (!isset($all[$username]) || !is_array($all[$username])) {
		return false;
	}

	foreach ($all[$username] as $index => $credential) {
		if (($credential['id'] ?? '') !== $credentialId) {
			continue;
		}
		$all[$username][$index]['counter'] = (int)$counter;
		return ecrirePasskeys($all);
	}

	return false;
}

