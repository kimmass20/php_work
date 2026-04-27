<?php
require_once '../../backend/read.php';

$pageTitle = 'Voir Données - Gestion des Auditoires';
$currentPage = 'view-data';

$salles = obtenirSalles();
$promotions = obtenirPromotions();
$options = obtenirOptions();
$cours = obtenirCours();

include '../components/header.php';
?>

<div class="page-container">
	<div class="page-header">
		<div class="page-icon bg-blue">
			<i class="fas fa-database"></i>
		</div>
		<h1 class="page-title">Aperçu des Données</h1>
	</div>

	<div class="page-toolbar">
		<a href="<?php echo appUrl('/frontend/pages/ajouter_salle.php'); ?>" class="btn btn-secondary"><i class="fas fa-door-open"></i> Ajouter Salle</a>
		<a href="<?php echo appUrl('/frontend/pages/ajouter_promotion.php'); ?>" class="btn btn-secondary"><i class="fas fa-users"></i> Ajouter Promotion</a>
		<a href="<?php echo appUrl('/frontend/pages/ajouter_option.php'); ?>" class="btn btn-secondary"><i class="fas fa-tags"></i> Ajouter Option</a>
		<a href="<?php echo appUrl('/frontend/pages/ajouter_cours.php'); ?>" class="btn btn-primary"><i class="fas fa-book-open"></i> Ajouter Cours</a>
	</div>

	<div class="tables-container">
		<div class="table-card">
			<div class="table-header"><h2>Salles</h2></div>
			<div class="table-wrapper">
				<table class="data-table">
					<thead><tr><th>Nom</th><th>Capacité</th><th>Actions</th></tr></thead>
					<tbody>
						<?php if (empty($salles)): ?>
							<tr><td colspan="3" class="empty-state">Aucune salle ajoutée</td></tr>
						<?php else: ?>
							<?php foreach ($salles as $salle): ?>
								<tr>
									<td><?php echo htmlspecialchars($salle['nom'] ?? ''); ?></td>
									<td><?php echo (int)($salle['capacite'] ?? 0); ?></td>
									<td>
										<div class="table-actions">
											<a href="<?php echo appUrl('/frontend/pages/modifier_salle.php'); ?>?id=<?php echo urlencode($salle['id'] ?? ''); ?>" class="btn btn-secondary btn-sm"><i class="fas fa-pen"></i> Modifier</a>
											<form action="<?php echo appUrl('/routes/supprimer_salle.php'); ?>" method="POST" class="inline-form" onsubmit="return confirm('Supprimer cette salle ?');">
												<input type="hidden" name="id" value="<?php echo htmlspecialchars($salle['id'] ?? ''); ?>">
												<button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Supprimer</button>
											</form>
										</div>
									</td>
								</tr>
							<?php endforeach; ?>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>

		<div class="table-card">
			<div class="table-header"><h2>Promotions</h2></div>
			<div class="table-wrapper">
				<table class="data-table">
					<thead><tr><th>Nom</th><th>Nombre d'Étudiants</th><th>Actions</th></tr></thead>
					<tbody>
						<?php if (empty($promotions)): ?>
							<tr><td colspan="3" class="empty-state">Aucune promotion ajoutée</td></tr>
						<?php else: ?>
							<?php foreach ($promotions as $promo): ?>
								<tr>
									<td><?php echo htmlspecialchars($promo['nom'] ?? ''); ?></td>
									<td><?php echo (int)($promo['nombreEtudiants'] ?? 0); ?></td>
									<td>
										<div class="table-actions">
											<a href="<?php echo appUrl('/frontend/pages/modifier_promotion.php'); ?>?id=<?php echo urlencode($promo['id'] ?? ''); ?>" class="btn btn-secondary btn-sm"><i class="fas fa-pen"></i> Modifier</a>
											<form action="<?php echo appUrl('/routes/supprimer_promotion.php'); ?>" method="POST" class="inline-form" onsubmit="return confirm('Supprimer cette promotion ?');">
												<input type="hidden" name="id" value="<?php echo htmlspecialchars($promo['id'] ?? ''); ?>">
												<button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Supprimer</button>
											</form>
										</div>
									</td>
								</tr>
							<?php endforeach; ?>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>

		<div class="table-card">
			<div class="table-header"><h2>Options</h2></div>
			<div class="table-wrapper">
				<table class="data-table">
					<thead><tr><th>Nom</th><th>Actions</th></tr></thead>
					<tbody>
						<?php if (empty($options)): ?>
							<tr><td colspan="2" class="empty-state">Aucune option ajoutée</td></tr>
						<?php else: ?>
							<?php foreach ($options as $option): ?>
								<tr>
									<td><?php echo htmlspecialchars($option['nom'] ?? ''); ?></td>
									<td>
										<div class="table-actions">
											<a href="<?php echo appUrl('/frontend/pages/modifier_option.php'); ?>?id=<?php echo urlencode($option['id'] ?? ''); ?>" class="btn btn-secondary btn-sm"><i class="fas fa-pen"></i> Modifier</a>
											<form action="<?php echo appUrl('/routes/supprimer_option.php'); ?>" method="POST" class="inline-form" onsubmit="return confirm('Supprimer cette option ?');">
												<input type="hidden" name="id" value="<?php echo htmlspecialchars($option['id'] ?? ''); ?>">
												<button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Supprimer</button>
											</form>
										</div>
									</td>
								</tr>
							<?php endforeach; ?>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>

		<div class="table-card">
			<div class="table-header"><h2>Cours</h2></div>
			<div class="table-wrapper">
				<table class="data-table">
					<thead><tr><th>Nom</th><th>Type</th><th>Promotion</th><th>Option</th><th>Actions</th></tr></thead>
					<tbody>
						<?php if (empty($cours)): ?>
							<tr><td colspan="5" class="empty-state">Aucun cours ajouté</td></tr>
						<?php else: ?>
							<?php foreach ($cours as $c): ?>
								<?php $promo = trouverPromotion($c['promotionId'] ?? ''); ?>
								<?php $option = trouverOption($c['optionId'] ?? ''); ?>
								<tr>
									<td><?php echo htmlspecialchars($c['nom'] ?? ''); ?></td>
									<td>
										<span class="badge badge-<?php echo ($c['type'] ?? '') === 'obligatoire' ? 'blue' : 'purple'; ?>">
											<?php echo ucfirst($c['type'] ?? ''); ?>
										</span>
									</td>
									<td><?php echo $promo ? htmlspecialchars($promo['nom'] ?? '') : 'N/A'; ?></td>
									<td><?php echo $option ? htmlspecialchars($option['nom'] ?? '') : 'Aucune'; ?></td>
									<td>
										<div class="table-actions">
											<a href="<?php echo appUrl('/frontend/pages/modifier_cours.php'); ?>?id=<?php echo urlencode($c['id'] ?? ''); ?>" class="btn btn-secondary btn-sm"><i class="fas fa-pen"></i> Modifier</a>
											<form action="<?php echo appUrl('/routes/supprimer_cours.php'); ?>" method="POST" class="inline-form" onsubmit="return confirm('Supprimer ce cours ?');">
												<input type="hidden" name="id" value="<?php echo htmlspecialchars($c['id'] ?? ''); ?>">
												<button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Supprimer</button>
											</form>
										</div>
									</td>
								</tr>
							<?php endforeach; ?>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<?php if (isset($_GET['message'])): ?>
		<div class="alert alert-success"><?php echo htmlspecialchars($_GET['message']); ?></div>
	<?php endif; ?>

	<?php if (isset($_GET['error'])): ?>
		<div class="alert alert-error"><?php echo htmlspecialchars($_GET['error']); ?></div>
	<?php endif; ?>
</div>

<?php include '../components/footer.php'; ?>

