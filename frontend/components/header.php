<?php
require_once __DIR__ . '/../../backend/utils.php';
exigerAuthentification();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Gestion des Auditoires'; ?></title>
    <link rel="stylesheet" href="<?php echo appUrl('/frontend/CSS/style.css'); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="app-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <h1>Gestion des Auditoires</h1>
            </div>
            <nav class="sidebar-nav">
                <a href="<?php echo appUrl('/index.php'); ?>" class="nav-item <?php echo ($currentPage ?? '') === 'dashboard' ? 'active' : ''; ?>">
                    <i class="fas fa-th-large"></i>
                    <span>Dashboard</span>
                </a>
                <a href="<?php echo appUrl('/frontend/pages/ajouter_salle.php'); ?>" class="nav-item <?php echo ($currentPage ?? '') === 'add-classroom' ? 'active' : ''; ?>">
                    <i class="fas fa-door-open"></i>
                    <span>Ajouter Salle</span>
                </a>
                <a href="<?php echo appUrl('/frontend/pages/ajouter_promotion.php'); ?>" class="nav-item <?php echo ($currentPage ?? '') === 'add-group' ? 'active' : ''; ?>">
                    <i class="fas fa-users"></i>
                    <span>Ajouter Promotion</span>
                </a>
                <a href="<?php echo appUrl('/frontend/pages/ajouter_option.php'); ?>" class="nav-item <?php echo ($currentPage ?? '') === 'add-option' ? 'active' : ''; ?>">
                    <i class="fas fa-tags"></i>
                    <span>Ajouter Option</span>
                </a>
                <a href="<?php echo appUrl('/frontend/pages/ajouter_cours.php'); ?>" class="nav-item <?php echo ($currentPage ?? '') === 'add-course' ? 'active' : ''; ?>">
                    <i class="fas fa-book-open"></i>
                    <span>Ajouter Cours</span>
                </a>
                <a href="<?php echo appUrl('/frontend/pages/afficher_donnees.php'); ?>" class="nav-item <?php echo ($currentPage ?? '') === 'view-data' ? 'active' : ''; ?>">
                    <i class="fas fa-database"></i>
                    <span>Voir Données</span>
                </a>
                <a href="<?php echo appUrl('/frontend/pages/afficher_planning.php'); ?>" class="nav-item <?php echo ($currentPage ?? '') === 'schedule' ? 'active' : ''; ?>">
                    <i class="fas fa-calendar"></i>
                    <span>Planning</span>
                </a>
            </nav>
            <div class="sidebar-footer">
                <p class="sidebar-user"><?php echo htmlspecialchars($_SESSION['auth_username'] ?? 'Utilisateur'); ?></p>
                <a href="<?php echo appUrl('/routes/logout.php'); ?>" class="btn btn-secondary btn-block">
                    <i class="fas fa-right-from-bracket"></i>
                    Déconnexion
                </a>
            </div>
        </aside>
        <main class="main-content">
