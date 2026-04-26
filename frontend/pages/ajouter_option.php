<?php
$pageTitle = 'Ajouter Option - Gestion des Auditoires';
$currentPage = 'add-option';

include '../components/header.php';
?>

<div class="page-container">
	<div class="page-header">
		<div class="page-icon bg-purple">
			<i class="fas fa-tags"></i>
		</div>
		<h1 class="page-title">Ajouter une Option</h1>
	</div>

	<div class="form-card">
		<form action="<?php echo appUrl('/routes/traiter_option.php'); ?>" method="POST" class="form">
			<div class="form-group">
				<label for="nom" class="form-label">Nom de l'Option</label>
				<input type="text" id="nom" name="nom" class="form-input" placeholder="ex: Génie Logiciel" required>
			</div>

			<div class="form-actions">
				<button type="submit" class="btn btn-primary">Ajouter Option</button>
				<a href="<?php echo appUrl('/frontend/pages/afficher_donnees.php'); ?>" class="btn btn-secondary">Annuler</a>
			</div>
		</form>
	</div>

	<?php if (isset($_GET['error'])): ?>
		<div class="alert alert-error"><?php echo htmlspecialchars($_GET['error']); ?></div>
	<?php endif; ?>
</div>

<?php include '../components/footer.php'; ?>
