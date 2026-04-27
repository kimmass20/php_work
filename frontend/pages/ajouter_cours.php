<?php
require_once '../../backend/read.php';

$pageTitle = 'Ajouter Cours - Gestion des Auditoires';
$currentPage = 'add-course';

$promotions = obtenirPromotions();
$options = obtenirOptions();

include '../components/header.php';
?>

<div class="page-container">
	<div class="page-header">
		<div class="page-icon bg-purple">
			<i class="fas fa-book-open"></i>
		</div>
		<div>
			<h1 class="page-title page-title-tight">Ajouter un Cours</h1>
			<p class="page-lead">Un cours doit toujours être rattaché à une promotion. Les cours optionnels doivent en plus référencer une option.</p>
		</div>
	</div>

	<?php if (empty($promotions)): ?>
		<div class="alert alert-error">
			Aucune promotion n'est disponible. <a href="<?php echo appUrl('/frontend/pages/ajouter_promotion.php'); ?>" class="alert-link">Crée d'abord une promotion</a> avant d'ajouter un cours.
		</div>
	<?php elseif (empty($options)): ?>
		<div class="alert alert-info">
			Aucune option n'est encore enregistrée. Les cours obligatoires peuvent être ajoutés, mais un cours optionnel exigera d'abord une option.
		</div>
	<?php endif; ?>

	<div class="form-card">
		<form action="<?php echo appUrl('/routes/traiter_cours.php'); ?>" method="POST" class="form">
			<div class="form-group">
				<label for="nom" class="form-label">Nom du Cours</label>
				<input type="text" id="nom" name="nom" class="form-input" placeholder="ex: Mathématiques" required>
			</div>

			<div class="form-group">
				<label for="type" class="form-label">Type de Cours</label>
				<select id="type" name="type" class="form-input" required>
					<option value="obligatoire">Obligatoire</option>
					<option value="optionnel">Optionnel</option>
				</select>
			</div>

			<div class="form-group">
				<label for="promotionId" class="form-label">Promotion Assignée</label>
				<select id="promotionId" name="promotionId" class="form-input" required>
					<option value="">Sélectionnez une promotion</option>
					<?php foreach ($promotions as $promo): ?>
						<option value="<?php echo htmlspecialchars($promo['id'] ?? ''); ?>">
							<?php echo htmlspecialchars($promo['nom'] ?? 'Promotion'); ?>
							(<?php echo (int)($promo['nombreEtudiants'] ?? 0); ?> étudiants)
						</option>
					<?php endforeach; ?>
				</select>
			</div>

			<div class="form-group">
				<label for="optionId" class="form-label">Option liée</label>
				<select id="optionId" name="optionId" class="form-input">
					<option value="">Aucune option</option>
					<?php foreach ($options as $option): ?>
						<option value="<?php echo htmlspecialchars($option['id'] ?? ''); ?>">
							<?php echo htmlspecialchars($option['nom'] ?? 'Option'); ?>
						</option>
					<?php endforeach; ?>
				</select>
				<p class="form-hint">Ce champ devient obligatoire uniquement si le type du cours est optionnel.</p>
			</div>

			<div class="form-actions">
				<button type="submit" class="btn btn-primary" <?php echo empty($promotions) ? 'disabled' : ''; ?>>Ajouter Cours</button>
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

