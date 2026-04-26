<?php
require_once '../../backend/utils.php';
require_once '../../backend/read.php';

$id = nettoyerDonnees($_GET['id'] ?? '');
$cours = trouverCours($id);

if (!$cours) {
	rediriger('/frontend/pages/afficher_donnees.php?error=Cours introuvable');
}

$promotions = obtenirPromotions();
$options = obtenirOptions();

$pageTitle = 'Modifier Cours - Gestion des Auditoires';
$currentPage = 'add-course';

include '../components/header.php';
?>

<div class="page-container">
	<div class="page-header">
		<div class="page-icon bg-purple">
			<i class="fas fa-pen"></i>
		</div>
		<div>
			<h1 class="page-title page-title-tight">Modifier le Cours</h1>
			<p class="page-lead">Mets à jour les informations du cours sans perdre la cohérence entre promotion, option et planning.</p>
		</div>
	</div>

	<?php if (empty($options)): ?>
		<div class="alert alert-info">Aucune option supplémentaire n'est disponible. Un cours optionnel existant conservera son option actuelle tant qu'elle n'est pas supprimée.</div>
	<?php endif; ?>

	<div class="form-card">
		<form action="<?php echo appUrl('/routes/modifier_cours.php'); ?>" method="POST" class="form">
			<input type="hidden" name="id" value="<?php echo htmlspecialchars($cours['id']); ?>">

			<div class="form-group">
				<label for="nom" class="form-label">Nom du Cours</label>
				<input type="text" id="nom" name="nom" class="form-input" value="<?php echo htmlspecialchars($cours['nom'] ?? ''); ?>" required>
			</div>

			<div class="form-group">
				<label for="type" class="form-label">Type de Cours</label>
				<select id="type" name="type" class="form-input" required>
					<option value="obligatoire" <?php echo ($cours['type'] ?? '') === 'obligatoire' ? 'selected' : ''; ?>>Obligatoire</option>
					<option value="optionnel" <?php echo ($cours['type'] ?? '') === 'optionnel' ? 'selected' : ''; ?>>Optionnel</option>
				</select>
			</div>

			<div class="form-group">
				<label for="promotionId" class="form-label">Promotion Assignée</label>
				<select id="promotionId" name="promotionId" class="form-input" required>
					<?php foreach ($promotions as $promotion): ?>
						<option value="<?php echo htmlspecialchars($promotion['id'] ?? ''); ?>" <?php echo ($cours['promotionId'] ?? '') === ($promotion['id'] ?? '') ? 'selected' : ''; ?>>
							<?php echo htmlspecialchars($promotion['nom'] ?? ''); ?> (<?php echo (int)($promotion['nombreEtudiants'] ?? 0); ?> étudiants)
						</option>
					<?php endforeach; ?>
				</select>
			</div>

			<div class="form-group">
				<label for="optionId" class="form-label">Option liée</label>
				<select id="optionId" name="optionId" class="form-input">
					<option value="">Aucune option</option>
					<?php foreach ($options as $option): ?>
						<option value="<?php echo htmlspecialchars($option['id'] ?? ''); ?>" <?php echo ($cours['optionId'] ?? '') === ($option['id'] ?? '') ? 'selected' : ''; ?>>
							<?php echo htmlspecialchars($option['nom'] ?? ''); ?>
						</option>
					<?php endforeach; ?>
				</select>
				<p class="form-hint">Garde ce champ vide pour un cours obligatoire.</p>
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

<script>
const typeField = document.getElementById('type');
const optionField = document.getElementById('optionId');

function synchroniserOption() {
    if (!typeField || !optionField) {
        return;
    }

    const optionnelle = typeField.value === 'optionnel';
    optionField.required = optionnelle;

    if (!optionnelle) {
        optionField.value = '';
    }
}

if (typeField && optionField) {
    typeField.addEventListener('change', synchroniserOption);
    synchroniserOption();
}
</script>

<?php include '../components/footer.php'; ?>
