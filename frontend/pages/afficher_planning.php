<?php
require_once '../../backend/read.php';

$pageTitle = 'Planning - Gestion des Auditoires';
$currentPage = 'schedule';

$planning = obtenirPlanning();
$jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'];
$horaires = ['08:00', '10:00', '12:00', '14:00', '16:00'];
$totalCreneaux = count($jours) * count($horaires);
$tauxOccupation = $totalCreneaux > 0 ? (int)round((count($planning) / $totalCreneaux) * 100) : 0;

function obtenirCreneau($planning, $jour, $horaire) {
	foreach ($planning as $creneau) {
		if (($creneau['jour'] ?? '') === $jour && ($creneau['horaire'] ?? '') === $horaire) {
			return $creneau;
		}
	}
	return null;
}

include '../components/header.php';
?>

<div class="page-container">
	<div class="page-header">
		<div class="page-icon bg-blue">
			<i class="fas fa-calendar"></i>
		</div>
		<div>
			<h1 class="page-title page-title-tight">Planning Hebdomadaire</h1>
			<p class="page-lead">Visualise l'occupation hebdomadaire des salles sur les créneaux disponibles.</p>
		</div>
	</div>

	<div class="summary-grid">
		<div class="summary-card">
			<span class="summary-label">Créneaux occupés</span>
			<strong><?php echo count($planning); ?></strong>
		</div>
		<div class="summary-card">
			<span class="summary-label">Créneaux disponibles</span>
			<strong><?php echo $totalCreneaux; ?></strong>
		</div>
		<div class="summary-card">
			<span class="summary-label">Taux d'occupation</span>
			<strong><?php echo $tauxOccupation; ?>%</strong>
		</div>
	</div>

	<div class="page-toolbar">
		<form action="<?php echo appUrl('/routes/generer_planning.php'); ?>" method="POST" class="inline-form">
			<button type="submit" class="btn btn-primary"><i class="fas fa-rotate"></i> Régénérer le planning</button>
		</form>
		<a href="<?php echo appUrl('/frontend/pages/afficher_donnees.php'); ?>" class="btn btn-secondary"><i class="fas fa-database"></i> Revenir aux données</a>
	</div>

	<?php if (empty($planning)): ?>
		<div class="empty-state-card">
			<i class="fas fa-calendar empty-icon"></i>
			<p class="empty-message">Aucun planning généré</p>
			<p class="empty-description">Allez au Dashboard puis cliquez sur "Générer Planning"</p>
			<div class="empty-state-actions">
				<a href="<?php echo appUrl('/index.php'); ?>" class="btn btn-primary"><i class="fas fa-th-large"></i> Ouvrir le dashboard</a>
			</div>
		</div>
	<?php else: ?>
		<div class="schedule-card">
			<div class="table-wrapper">
				<table class="schedule-table">
					<thead>
						<tr>
							<th class="time-column">Horaire</th>
							<?php foreach ($jours as $jour): ?>
								<th><?php echo $jour; ?></th>
							<?php endforeach; ?>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($horaires as $horaire): ?>
							<tr>
								<td class="time-cell"><?php echo $horaire; ?></td>
								<?php foreach ($jours as $jour): ?>
									<?php $creneau = obtenirCreneau($planning, $jour, $horaire); ?>
									<td>
										<?php if ($creneau): ?>
											<div class="schedule-slot">
												<div class="slot-course"><?php echo htmlspecialchars($creneau['cours'] ?? ''); ?></div>
												<div class="slot-details">
													<?php echo htmlspecialchars($creneau['promotion'] ?? ''); ?> • <?php echo htmlspecialchars($creneau['salle'] ?? ''); ?>
												</div>
											</div>
										<?php else: ?>
											<div class="slot-empty">—</div>
										<?php endif; ?>
									</td>
								<?php endforeach; ?>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	<?php endif; ?>

	<?php if (isset($_GET['message'])): ?>
		<div class="alert alert-success"><?php echo htmlspecialchars($_GET['message']); ?></div>
	<?php endif; ?>

	<?php if (isset($_GET['error'])): ?>
		<div class="alert alert-error"><?php echo htmlspecialchars($_GET['error']); ?></div>
	<?php endif; ?>
</div>

<?php include '../components/footer.php'; ?>

