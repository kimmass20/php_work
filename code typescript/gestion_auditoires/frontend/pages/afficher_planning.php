<?php
require_once '../../backend/read.php';

$pageTitle = 'Planning - Gestion des Auditoires';
$currentPage = 'schedule';

$planning = obtenirPlanning();

$jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'];
$horaires = ['08:00', '10:00', '12:00', '14:00', '16:00'];

function obtenirCreneau($planning, $jour, $horaire) {
    foreach ($planning as $creneau) {
        if ($creneau['jour'] === $jour && $creneau['horaire'] === $horaire) {
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
        <h1 class="page-title">Planning Hebdomadaire</h1>
    </div>

    <?php if (empty($planning)): ?>
        <div class="empty-state-card">
            <i class="fas fa-calendar empty-icon"></i>
            <p class="empty-message">Aucun planning généré</p>
            <p class="empty-description">Allez au Dashboard et cliquez sur "Générer Planning" pour en créer un</p>
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
                                                <div class="slot-course"><?php echo htmlspecialchars($creneau['cours']); ?></div>
                                                <div class="slot-details">
                                                    <?php echo htmlspecialchars($creneau['promotion']); ?> •
                                                    <?php echo htmlspecialchars($creneau['salle']); ?>
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
        <div class="alert alert-success">
            <?php echo htmlspecialchars($_GET['message']); ?>
        </div>
    <?php endif; ?>
</div>

<?php include '../components/footer.php'; ?>
