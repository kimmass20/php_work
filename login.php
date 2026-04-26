<?php
require_once __DIR__ . '/backend/utils.php';

if (estAuthentifie()) {
	rediriger('/index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$username = nettoyerDonnees($_POST['username'] ?? '');
	$password = (string)($_POST['password'] ?? '');
	$redirectTo = (string)($_POST['redirect'] ?? appUrl('/index.php'));

	if ($username === '' || $password === '') {
		$error = 'Veuillez renseigner le nom d\'utilisateur et le mot de passe.';
	} elseif (tenterConnexion($username, $password)) {
		$base = appBasePath();
		$sanitizedRedirect = $redirectTo;

		if (preg_match('#^https?://#i', $sanitizedRedirect)) {
			$sanitizedRedirect = appUrl('/index.php');
		}

		if ($base !== '' && strpos($sanitizedRedirect, $base) !== 0) {
			$sanitizedRedirect = appUrl('/index.php');
		}

		if ($base === '' && strpos($sanitizedRedirect, '/') !== 0) {
			$sanitizedRedirect = appUrl('/index.php');
		}

		header('Location: ' . $sanitizedRedirect);
		exit;
	} else {
		$error = 'Identifiants incorrects.';
	}
}

$redirect = (string)($_GET['redirect'] ?? $_POST['redirect'] ?? appUrl('/index.php'));
$defaultUsername = authDefaultUsername();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Connexion - Gestion des Auditoires</title>
	<link rel="stylesheet" href="<?php echo appUrl('/frontend/CSS/style.css'); ?>">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="auth-page">
	<div class="auth-container">
		<div class="auth-card">
			<div class="auth-header">
				<span class="auth-badge">Espace sécurisé</span>
				<h1>Connexion</h1>
				<p>Connectez-vous pour accéder à la gestion des auditoires.</p>
			</div>

			<?php if ($error !== ''): ?>
				<div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
			<?php endif; ?>

			<?php if (isset($_GET['message'])): ?>
				<div class="alert alert-success"><?php echo htmlspecialchars($_GET['message']); ?></div>
			<?php endif; ?>

			<form method="POST" class="form auth-form">
				<input type="hidden" name="redirect" value="<?php echo htmlspecialchars($redirect); ?>">
				<div class="form-group">
					<label for="username" class="form-label">Nom d'utilisateur</label>
					<input id="username" name="username" type="text" class="form-input" required autocomplete="username" value="<?php echo htmlspecialchars($defaultUsername); ?>">
				</div>
				<div class="form-group">
					<label for="password" class="form-label">Mot de passe</label>
					<input id="password" name="password" type="password" class="form-input" required autocomplete="current-password">
				</div>
				<button type="submit" class="btn btn-primary btn-block" id="password-login-btn">
					<i class="fas fa-sign-in-alt"></i>
					Se connecter (mot de passe)
				</button>
				<button type="button" class="btn btn-secondary btn-block" id="passkey-login-btn">
					<i class="fas fa-fingerprint"></i>
					Se connecter avec Face ID / Empreinte
				</button>
				<button type="button" class="btn btn-secondary btn-block" id="passkey-register-btn">
					<i class="fas fa-key"></i>
					Activer la connexion biométrique sur cet appareil
				</button>
			</form>

			<div class="alert alert-info auth-webauthn-status" id="passkey-status" hidden></div>
			<p class="auth-help">Par défaut: <strong>admin / admin123</strong>. Configurez <code>APP_AUTH_USER</code> et <code>APP_AUTH_PASS</code> pour personnaliser.</p>
		</div>
	</div>
	<script>
		function toBase64Url(buffer) {
			const bytes = new Uint8Array(buffer);
			let binary = '';
			for (let i = 0; i < bytes.length; i += 1) {
				binary += String.fromCharCode(bytes[i]);
			}
			return btoa(binary).replace(/\+/g, '-').replace(/\//g, '_').replace(/=+$/, '');
		}

		function fromBase64Url(value) {
			const base64 = value.replace(/-/g, '+').replace(/_/g, '/');
			const padded = base64 + '='.repeat((4 - (base64.length % 4)) % 4);
			const binary = atob(padded);
			const bytes = new Uint8Array(binary.length);
			for (let i = 0; i < binary.length; i += 1) {
				bytes[i] = binary.charCodeAt(i);
			}
			return bytes;
		}

		function showStatus(message, isError) {
			const statusEl = document.getElementById('passkey-status');
			statusEl.hidden = false;
			statusEl.textContent = message;
			statusEl.className = isError ? 'alert alert-error auth-webauthn-status' : 'alert alert-success auth-webauthn-status';
		}

		async function postForm(url, data) {
			const response = await fetch(url, {
				method: 'POST',
				headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
				body: new URLSearchParams(data).toString(),
			});
			return response.json();
		}

		async function postJson(url, data) {
			const response = await fetch(url, {
				method: 'POST',
				headers: { 'Content-Type': 'application/json' },
				body: JSON.stringify(data),
			});
			return response.json();
		}

		async function registerPasskey() {
			if (!window.PublicKeyCredential) {
				showStatus('Ce navigateur ne supporte pas WebAuthn.', true);
				return;
			}
			if (!window.isSecureContext) {
				showStatus('WebAuthn nécessite HTTPS (ou localhost). Ouvre le site en https://... pour utiliser Face ID / empreinte.', true);
				return;
			}
			const isPlatformAvailable = await PublicKeyCredential.isUserVerifyingPlatformAuthenticatorAvailable();
			if (!isPlatformAvailable) {
				showStatus('Aucun authentificateur biométrique local détecté (Windows Hello / Touch ID / Face ID).', true);
				return;
			}

			const username = document.getElementById('username').value.trim();
			if (!username) {
				showStatus('Veuillez renseigner le nom utilisateur.', true);
				return;
			}

			const options = await postForm('<?php echo appUrl('/routes/webauthn_register_options.php'); ?>', { username });
			if (options.error) {
				showStatus(options.error, true);
				return;
			}

			const publicKey = {
				...options,
				challenge: fromBase64Url(options.challenge),
				user: {
					...options.user,
					id: fromBase64Url(options.user.id),
				},
				excludeCredentials: (options.excludeCredentials || []).map((item) => ({
					...item,
					id: fromBase64Url(item.id),
				})),
			};

			const credential = await navigator.credentials.create({ publicKey });
			if (!credential) {
				showStatus('Création de passkey annulée.', true);
				return;
			}

			const response = credential.response;
			const publicKeyBuffer = response.getPublicKey ? response.getPublicKey() : null;
			if (!publicKeyBuffer) {
				showStatus('Le navigateur ne fournit pas la clé publique. Utilisez Chrome/Edge récent.', true);
				return;
			}

			const spkiBase64 = btoa(String.fromCharCode(...new Uint8Array(publicKeyBuffer)));
			const pemLines = spkiBase64.match(/.{1,64}/g) || [];
			const publicKeyPem = '-----BEGIN PUBLIC KEY-----\n' + pemLines.join('\n') + '\n-----END PUBLIC KEY-----';

			const verifyResult = await postJson('<?php echo appUrl('/routes/webauthn_register_verify.php'); ?>', {
				username,
				credentialId: toBase64Url(credential.rawId),
				clientDataJSON: toBase64Url(response.clientDataJSON),
				publicKeyPem,
			});

			if (verifyResult.error) {
				showStatus(verifyResult.error, true);
				return;
			}

			showStatus('Passkey enregistrée. Vous pouvez maintenant vous connecter par biométrie.', false);
		}

		async function loginWithPasskey() {
			if (!window.PublicKeyCredential) {
				showStatus('Ce navigateur ne supporte pas WebAuthn.', true);
				return;
			}
			if (!window.isSecureContext) {
				showStatus('WebAuthn nécessite HTTPS (ou localhost). Ouvre le site en https://... pour utiliser Face ID / empreinte.', true);
				return;
			}

			const username = document.getElementById('username').value.trim();
			if (!username) {
				showStatus('Veuillez renseigner le nom utilisateur.', true);
				return;
			}

			const options = await postForm('<?php echo appUrl('/routes/webauthn_auth_options.php'); ?>', { username });
			if (options.error) {
				showStatus(options.error, true);
				return;
			}

			const publicKey = {
				...options,
				challenge: fromBase64Url(options.challenge),
				allowCredentials: (options.allowCredentials || []).map((item) => ({
					...item,
					id: fromBase64Url(item.id),
				})),
			};

			const assertion = await navigator.credentials.get({ publicKey });
			if (!assertion) {
				showStatus('Authentification biométrique annulée.', true);
				return;
			}

			const response = assertion.response;
			const verifyResult = await postJson('<?php echo appUrl('/routes/webauthn_auth_verify.php'); ?>', {
				username,
				credentialId: toBase64Url(assertion.rawId),
				clientDataJSON: toBase64Url(response.clientDataJSON),
				authenticatorData: toBase64Url(response.authenticatorData),
				signature: toBase64Url(response.signature),
			});

			if (verifyResult.error) {
				showStatus(verifyResult.error, true);
				return;
			}

			window.location.href = verifyResult.redirect || '<?php echo appUrl('/index.php'); ?>';
		}

		document.getElementById('passkey-register-btn').addEventListener('click', () => {
			registerPasskey().catch((error) => {
				if (error && error.name === 'NotAllowedError') {
					showStatus('Action annulée ou vérification biométrique refusée.', true);
					return;
				}
				showStatus(error.message || 'Erreur pendant l\'enregistrement de la passkey.', true);
			});
		});

		document.getElementById('passkey-login-btn').addEventListener('click', () => {
			loginWithPasskey().catch((error) => {
				if (error && error.name === 'NotAllowedError') {
					showStatus('Vérification biométrique annulée/refusée.', true);
					return;
				}
				showStatus(error.message || 'Erreur pendant l\'authentification biométrique.', true);
			});
		});
	</script>
</body>
</html>
