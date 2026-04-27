<?php
$pageTitle = 'Ajouter Salle - Gestion des Auditoires';
$currentPage = 'add-classroom';

include '../components/header.php';
?>

<div class="page-container">
	<div class="page-header">
		<div class="page-icon bg-blue">
			<i class="fas fa-door-open"></i>
		</div>
		<h1 class="page-title">Ajouter une Salle</h1>
	</div>

	<div class="form-card">
		<form action="<?php echo appUrl('/routes/traiter_salle.php'); ?>" method="POST" class="form">
			<div class="form-group">
				<label for="nom" class="form-label">Nom de la Salle</label>
				<input type="text" id="nom" name="nom" class="form-input" placeholder="ex: Salle A101" required>
			</div>

			<div class="form-group">
				<label for="capacite" class="form-label">Capacité</label>
				<input type="number" id="capacite" name="capacite" class="form-input" placeholder="ex: 50" min="1" required>
			</div>

			<div class="form-actions">
				<button type="submit" class="btn btn-primary">Ajouter Salle</button>
				<a href="<?php echo appUrl('/frontend/pages/afficher_donnees.php'); ?>" class="btn btn-secondary">Annuler</a>
			</div>
		</form>
	</div>

	<?php if (isset($_GET['error'])): ?>
		<div class="alert alert-error"><?php echo htmlspecialchars($_GET['error']); ?></div>
	<?php endif; ?>
</div>

<?php include '../components/footer.php'; ?>

