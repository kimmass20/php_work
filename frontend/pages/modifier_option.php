<?php
require_once '../../backend/utils.php';
require_once '../../backend/read.php';

$id = nettoyerDonnees($_GET['id'] ?? '');
$option = trouverOption($id);

if (!$option) {
	rediriger('/frontend/pages/afficher_donnees.php?error=Option introuvable');
}

$pageTitle = 'Modifier Option - Gestion des Auditoires';
$currentPage = 'add-option';

include '../components/header.php';
?>

<div class="page-container">
	<div class="page-header">
		<div class="page-icon bg-purple">
			<i class="fas fa-pen"></i>
		</div>
		<h1 class="page-title">Modifier l'Option</h1>
	</div>

	<div class="form-card">
		<form action="<?php echo appUrl('/routes/modifier_option.php'); ?>" method="POST" class="form">
			<input type="hidden" name="id" value="<?php echo htmlspecialchars($option['id']); ?>">

			<div class="form-group">
				<label for="nom" class="form-label">Nom de l'Option</label>
				<input type="text" id="nom" name="nom" class="form-input" value="<?php echo htmlspecialchars($option['nom'] ?? ''); ?>" required>
			</div>

			<div class="form-actions">
				<button type="submit" class="btn btn-primary">Enregistrer</button>
				<a href="<?php echo appUrl('/frontend/pages/afficher_donnees.php'); ?>" class="btn btn-secondary">Annuler</a>
			</div>
		</form>
	</div>

	<?php if (isset($_GET['error'])): ?>
		<div class="alert alert-error"><?php echo htmlspecialchars($_GET['error']); ?></div>
	<?php endif; ?>
</div>

<?php include '../components/footer.php'; ?>
