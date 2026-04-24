<?php
require_once 'backend/read.php';

$pageTitle = 'Dashboard - Gestion des Auditoires';
$currentPage = 'dashboard';

$salles = obtenirSalles();
$promotions = obtenirPromotions();
$cours = obtenirCours();

include 'frontend/components/header.php';
?>

<div class="page-container">
    <h1 class="page-title">Gestion des Auditoires</h1>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-info">
                    <p class="stat-label">Salles</p>
                    <p class="stat-value"><?php echo count($salles); ?></p>
                </div>
                <div class="stat-icon bg-blue">
                    <i class="fas fa-door-open"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-info">
                    <p class="stat-label">Cours</p>
                    <p class="stat-value"><?php echo count($cours); ?></p>
                </div>
                <div class="stat-icon bg-purple">
                    <i class="fas fa-book-open"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-info">
                    <p class="stat-label">Promotions</p>
                    <p class="stat-value"><?php echo count($promotions); ?></p>
                </div>
                <div class="stat-icon bg-green">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="action-card">
        <div class="action-content">
            <div>
                <h2 class="action-title">Générateur de Planning</h2>
                <p class="action-description">
                    Générez automatiquement un planning optimisé basé sur les salles, promotions et cours
                </p>
            </div>
        </div>
        <form action="/routes/generer_planning.php" method="POST">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-calendar"></i>
                Générer Planning
            </button>
        </form>
    </div>

    <?php if (isset($_GET['message'])): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($_GET['message']); ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'frontend/components/footer.php'; ?>
