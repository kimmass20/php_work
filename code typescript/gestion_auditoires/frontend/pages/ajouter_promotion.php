<?php
$pageTitle = 'Ajouter Promotion - Gestion des Auditoires';
$currentPage = 'add-group';

include '../components/header.php';
?>

<div class="page-container">
    <div class="page-header">
        <div class="page-icon bg-green">
            <i class="fas fa-users"></i>
        </div>
        <h1 class="page-title">Ajouter une Promotion</h1>
    </div>

    <div class="form-card">
        <form action="/routes/traiter_promotion.php" method="POST" class="form">
            <div class="form-group">
                <label for="nom" class="form-label">Nom de la Promotion</label>
                <select id="nom" name="nom" class="form-input" required>
                    <option value="">Sélectionnez un niveau</option>
                    <option value="L1">L1</option>
                    <option value="L2">L2</option>
                    <option value="L3">L3</option>
                    <option value="L4">L4</option>
                </select>
            </div>

            <div class="form-group">
                <label for="nombreEtudiants" class="form-label">Nombre d'Étudiants</label>
                <input
                    type="number"
                    id="nombreEtudiants"
                    name="nombreEtudiants"
                    class="form-input"
                    placeholder="ex: 45"
                    min="1"
                    required
                >
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    Ajouter Promotion
                </button>
                <a href="/frontend/pages/afficher_donnees.php" class="btn btn-secondary">
                    Annuler
                </a>
            </div>
        </form>
    </div>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-error">
            <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>
</div>

<?php include '../components/footer.php'; ?>
