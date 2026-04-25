<?php
require_once __DIR__ . '/backend/utils.php';
require_once __DIR__ . '/backend/read.php';
exigerAuthentification();

$pageTitle = 'Dashboard - Gestion des Auditoires';
$currentPage = 'dashboard';

$salles = obtenirSalles();
$promotions = obtenirPromotions();
$options = obtenirOptions();
$cours = obtenirCours();
$planning = obtenirPlanning();

$totalCreneaux = 25;
$coursPlanifies = count($planning);
$tauxOccupation = $totalCreneaux > 0 ? (int)round(($coursPlanifies / $totalCreneaux) * 100) : 0;

include __DIR__ . '/frontend/components/header.php';
?>

<div class="page-container">
    <section class="hero-card">
        <div>
            <span class="hero-badge">Tableau de bord</span>
            <h1 class="page-title page-title-tight">Gestion des Auditoires</h1>
            <p class="page-lead">Pilote les salles, promotions, options et cours depuis une seule interface, puis génère un planning hebdomadaire sans conflit de salle ni de promotion.</p>
        </div>
        <div class="hero-meta">
            <div class="hero-metric">
                <span class="hero-metric-label">Cours planifiés</span>
                <strong><?php echo $coursPlanifies; ?></strong>
            </div>
            <div class="hero-metric">
                <span class="hero-metric-label">Occupation</span>
                <strong><?php echo $tauxOccupation; ?>%</strong>
            </div>
        </div>
    </section>

    <section class="shortcut-grid">
        <a href="<?php echo appUrl('/frontend/pages/ajouter_salle.php'); ?>" class="shortcut-card">
            <span class="shortcut-icon bg-blue"><i class="fas fa-door-open"></i></span>
            <div>
                <h2>Ajouter une salle</h2>
                <p>Déclare un nouvel auditoire avec sa capacité.</p>
            </div>
        </a>
        <a href="<?php echo appUrl('/frontend/pages/ajouter_promotion.php'); ?>" class="shortcut-card">
            <span class="shortcut-icon bg-green"><i class="fas fa-users"></i></span>
            <div>
                <h2>Ajouter une promotion</h2>
                <p>Crée un niveau d'études avec son effectif.</p>
            </div>
        </a>
        <a href="<?php echo appUrl('/frontend/pages/ajouter_option.php'); ?>" class="shortcut-card">
            <span class="shortcut-icon bg-purple"><i class="fas fa-tags"></i></span>
            <div>
                <h2>Ajouter une option</h2>
                <p>Définit les parcours utilisés par les cours optionnels.</p>
            </div>
        </a>
        <a href="<?php echo appUrl('/frontend/pages/ajouter_cours.php'); ?>" class="shortcut-card">
            <span class="shortcut-icon bg-purple"><i class="fas fa-book-open"></i></span>
            <div>
                <h2>Ajouter un cours</h2>
                <p>Associe un cours à une promotion et, si besoin, à une option.</p>
            </div>
        </a>
    </section>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-info">
                    <p class="stat-label">Salles</p>
                    <p class="stat-value"><?php echo count($salles); ?></p>
                </div>
                <div class="stat-icon bg-blue"><i class="fas fa-door-open"></i></div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-info">
                    <p class="stat-label">Cours</p>
                    <p class="stat-value"><?php echo count($cours); ?></p>
                </div>
                <div class="stat-icon bg-purple"><i class="fas fa-book-open"></i></div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-info">
                    <p class="stat-label">Promotions</p>
                    <p class="stat-value"><?php echo count($promotions); ?></p>
                </div>
                <div class="stat-icon bg-green"><i class="fas fa-users"></i></div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-info">
                    <p class="stat-label">Options</p>
                    <p class="stat-value"><?php echo count($options); ?></p>
                </div>
                <div class="stat-icon bg-purple"><i class="fas fa-tags"></i></div>
            </div>
        </div>
    </div>

    <div class="dashboard-grid">
    <div class="action-card">
        <div class="action-content">
            <h2 class="action-title">Générateur de Planning</h2>
            <p class="action-description">Générez automatiquement un planning optimisé selon les salles, promotions et cours.</p>
        </div>
        <form action="<?php echo appUrl('/routes/generer_planning.php'); ?>" method="POST">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-calendar"></i>
                Générer Planning
            </button>
        </form>
    </div>

    <div class="info-card">
        <h2 class="action-title">État actuel</h2>
        <ul class="info-list">
            <li><span>Salles disponibles</span><strong><?php echo count($salles); ?></strong></li>
            <li><span>Promotions actives</span><strong><?php echo count($promotions); ?></strong></li>
            <li><span>Options configurées</span><strong><?php echo count($options); ?></strong></li>
            <li><span>Cours enregistrés</span><strong><?php echo count($cours); ?></strong></li>
        </ul>
        <a href="<?php echo appUrl('/frontend/pages/afficher_donnees.php'); ?>" class="btn btn-secondary btn-block">
            <i class="fas fa-database"></i>
            Voir toutes les données
        </a>
    </div>
    </div>

    <?php if (isset($_GET['message'])): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($_GET['message']); ?></div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($_GET['error']); ?></div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/frontend/components/footer.php'; ?>
