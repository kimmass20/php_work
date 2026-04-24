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
					<input id="username" name="username" type="text" class="form-input" required autocomplete="username">
				</div>
				<div class="form-group">
					<label for="password" class="form-label">Mot de passe</label>
					<input id="password" name="password" type="password" class="form-input" required autocomplete="current-password">
				</div>
				<button type="submit" class="btn btn-primary btn-block">
					<i class="fas fa-sign-in-alt"></i>
					Se connecter
				</button>
			</form>

			<p class="auth-help">Par défaut: <strong>admin / admin123</strong>. Configurez <code>APP_AUTH_USER</code> et <code>APP_AUTH_PASS</code> pour personnaliser.</p>
		</div>
	</div>
</body>
</html>
