<?php
require_once '../../backend/utils.php';
require_once '../../backend/read.php';

$id = nettoyerDonnees($_GET['id'] ?? '');
$salle = trouverSalle($id);

if (!$salle) {
	rediriger('/frontend/pages/afficher_donnees.php?error=Salle introuvable');
}

$pageTitle = 'Modifier Salle - Gestion des Auditoires';
$currentPage = 'add-classroom';

include '../components/header.php';
?>

<div class="page-container">
	<div class="page-header">
		<div class="page-icon bg-blue">
			<i class="fas fa-pen"></i>
		</div>
		<h1 class="page-title">Modifier la Salle</h1>
	</div>

	<div class="form-card">
		<form action="<?php echo appUrl('/routes/modifier_salle.php'); ?>" method="POST" class="form">
			<input type="hidden" name="id" value="<?php echo htmlspecialchars($salle['id']); ?>">

			<div class="form-group">
				<label for="nom" class="form-label">Nom de la Salle</label>
				<input type="text" id="nom" name="nom" class="form-input" value="<?php echo htmlspecialchars($salle['nom'] ?? ''); ?>" required>
			</div>

			<div class="form-group">
				<label for="capacite" class="form-label">Capacité</label>
				<input type="number" id="capacite" name="capacite" class="form-input" min="1" value="<?php echo (int)($salle['capacite'] ?? 0); ?>" required>
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
