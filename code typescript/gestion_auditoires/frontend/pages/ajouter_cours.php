<?php
require_once '../../backend/read.php';

$pageTitle = 'Ajouter Cours - Gestion des Auditoires';
$currentPage = 'add-course';

$promotions = obtenirPromotions();

include '../components/header.php';
?>

<div class="page-container">
    <div class="page-header">
        <div class="page-icon bg-purple">
            <i class="fas fa-book-open"></i>
        </div>
        <h1 class="page-title">Ajouter un Cours</h1>
    </div>

    <div class="form-card">
        <form action="/routes/traiter_cours.php" method="POST" class="form">
            <div class="form-group">
                <label for="nom" class="form-label">Nom du Cours</label>
                <input
                    type="text"
                    id="nom"
                    name="nom"
                    class="form-input"
                    placeholder="ex: Mathématiques"
                    required
                >
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
                        <option value="<?php echo htmlspecialchars($promo['id']); ?>">
                            <?php echo htmlspecialchars($promo['nom']); ?>
                            (<?php echo $promo['nombreEtudiants']; ?> étudiants)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    Ajouter Cours
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
