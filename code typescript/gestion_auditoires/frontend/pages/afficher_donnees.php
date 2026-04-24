<?php
require_once '../../backend/read.php';

$pageTitle = 'Voir Données - Gestion des Auditoires';
$currentPage = 'view-data';

$salles = obtenirSalles();
$promotions = obtenirPromotions();
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

    <div class="tables-container">
        <!-- Table Salles -->
        <div class="table-card">
            <div class="table-header">
                <h2>Salles</h2>
            </div>
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Capacité</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($salles)): ?>
                            <tr>
                                <td colspan="2" class="empty-state">Aucune salle ajoutée</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($salles as $salle): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($salle['nom']); ?></td>
                                    <td><?php echo $salle['capacite']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Table Promotions -->
        <div class="table-card">
            <div class="table-header">
                <h2>Promotions</h2>
            </div>
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Nombre d'Étudiants</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($promotions)): ?>
                            <tr>
                                <td colspan="2" class="empty-state">Aucune promotion ajoutée</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($promotions as $promo): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($promo['nom']); ?></td>
                                    <td><?php echo $promo['nombreEtudiants']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Table Cours -->
        <div class="table-card">
            <div class="table-header">
                <h2>Cours</h2>
            </div>
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Type</th>
                            <th>Promotion</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($cours)): ?>
                            <tr>
                                <td colspan="3" class="empty-state">Aucun cours ajouté</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($cours as $c): ?>
                                <?php $promo = trouverPromotion($c['promotionId']); ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($c['nom']); ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo $c['type'] === 'obligatoire' ? 'blue' : 'purple'; ?>">
                                            <?php echo ucfirst($c['type']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo $promo ? htmlspecialchars($promo['nom']) : 'N/A'; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php if (isset($_GET['message'])): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($_GET['message']); ?>
        </div>
    <?php endif; ?>
</div>

<?php include '../components/footer.php'; ?>
