<?php
require_once '../../backend/utils.php';
require_once '../../backend/read.php';

$id = nettoyerDonnees($_GET['id'] ?? '');
$promotion = trouverPromotion($id);

if (!$promotion) {
	rediriger('/frontend/pages/afficher_donnees.php?error=Promotion introuvable');
}

$pageTitle = 'Modifier Promotion - Gestion des Auditoires';
$currentPage = 'add-group';

include '../components/header.php';
?>

<div class="page-container">
	<div class="page-header">
		<div class="page-icon bg-green">
			<i class="fas fa-pen"></i>
		</div>
		<h1 class="page-title">Modifier la Promotion</h1>
	</div>

	<div class="form-card">
		<form action="<?php echo appUrl('/routes/modifier_promotion.php'); ?>" method="POST" class="form">
			<input type="hidden" name="id" value="<?php echo htmlspecialchars($promotion['id']); ?>">

			<div class="form-group">
				<label for="nom" class="form-label">Nom de la Promotion</label>
				<select id="nom" name="nom" class="form-input" required>
					<?php foreach (['L1', 'L2', 'L3', 'L4'] as $niveau): ?>
						<option value="<?php echo $niveau; ?>" <?php echo ($promotion['nom'] ?? '') === $niveau ? 'selected' : ''; ?>>
							<?php echo $niveau; ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>

			<div class="form-group">
				<label for="nombreEtudiants" class="form-label">Nombre d'Étudiants</label>
				<input type="number" id="nombreEtudiants" name="nombreEtudiants" class="form-input" min="1" value="<?php echo (int)($promotion['nombreEtudiants'] ?? 0); ?>" required>
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
