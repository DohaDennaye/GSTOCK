<?php
include 'entete.php';
require_once __DIR__ . '/../models/Fournisseur.php';

/* ========= ACTIONS ========= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['add'])) {
        Fournisseur::create($_POST);
        $_SESSION['success'] = "Fournisseur ajouté avec succès";
    }

    if (isset($_POST['update'])) {
        Fournisseur::update($_POST['id'], $_POST);
        $_SESSION['success'] = "Fournisseur modifié avec succès";
    }

    header("Location: fournisseur.php");
    exit;
}

if (isset($_GET['delete'])) {
    Fournisseur::delete($_GET['delete']);
    $_SESSION['success'] = "Fournisseur supprimé";
    header("Location: fournisseur.php");
    exit;
}

/* ========= EDIT ========= */
$fournisseurEdit = isset($_GET['edit'])
    ? Fournisseur::getById($_GET['edit'])
    : null;

$fournisseurs = Fournisseur::getAll();
?>

<style>
    /* ===== CONTENEUR ===== */
    .home-content {
        padding: 25px;
        margin-top: 70px;
        background: #f5f7fb;
        min-height: calc(100vh - 70px);
    }

    /* ===== CARD ===== */
    .card {
        background: #fff;
        padding: 22px;
        border-radius: 10px;
        margin-bottom: 25px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .08);
    }

    /* ===== FORM ===== */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }

    .form-grid label {
        font-size: 14px;
        color: #475569;
    }

    .form-grid input {
        padding: 8px;
        border-radius: 6px;
        border: 1px solid #cbd5e1;
    }

    .form-grid input:focus {
        outline: none;
        border-color: #2563eb;
    }

    .form-actions {
        grid-column: 1/-1;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    button {
        background: #0f172a;
        color: #fff;
        padding: 10px 22px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
    }

    .btn-cancel {
        background: #e5e7eb;
        color: #111827;
        padding: 10px 18px;
        border-radius: 8px;
        text-decoration: none;
    }

    /* ===== TABLE ===== */
    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table th {
        background: #11101d;
        padding: 12px;
        text-align: left;
    }

    .table td {
        padding: 10px;
        border-bottom: 1px solid #dddddd;
    }

    /* ===== ALERT ===== */
    .alert {
        padding: 12px;
        margin-bottom: 15px;
        border-radius: 8px;
    }

    .alert.success {
        background: #d4edda;
        color: #155724;
    }

    /* ===== ACTIONS ===== */
    .edit {
        color: #2563eb;
        margin-right: 10px;
        text-decoration: none
    }

    .delete {
        color: #dc2626;
        text-decoration: none
    }

    /* ===== RESPONSIVE ===== */
    @media(max-width:768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="home-content">

    <h2>Gestion des fournisseurs</h2><br />

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert success">
            <?= $_SESSION['success'];
            unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <!-- FORM -->
    <div class="card">
        <h3><?= $fournisseurEdit ? " Modifier le fournisseur" : " Ajouter un fournisseur" ?></h3><br>

        <form method="post" class="form-article">

            <?php if ($fournisseurEdit): ?>
                <input type="hidden" name="id" value="<?= $fournisseurEdit['id'] ?>">
            <?php endif; ?>

            <div>
                <label>Nom</label><br />
                <input type="text" name="nom" required value="<?= $fournisseurEdit['nom'] ?? '' ?>">
            </div>

            <div>
                <label>Prénom</label><br />
                <input type="text" name="prenom" required value="<?= $fournisseurEdit['prenom'] ?? '' ?>">
            </div>

            <div>
                <label>Téléphone</label><br />
                <input type="text" name="telephone" required value="<?= $fournisseurEdit['telephone'] ?? '' ?>">
            </div>

            <div>
                <label>Adresse</label><br />
                <input type="text" name="adresse" required value="<?= $fournisseurEdit['adresse'] ?? '' ?>">
            </div>

            <div class="form-actions">
                <button type="submit" name="<?= $fournisseurEdit ? 'update' : 'add' ?>">
                    <?= $fournisseurEdit ? 'Mettre à jour' : 'Ajouter' ?>
                </button>

                <?php if ($fournisseurEdit): ?>
                    <a href="fournisseur.php" class="btn-cancel">Annuler</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- TABLE -->
    <div class="card">
        <h3> Liste des fournisseurs</h3>

        <table class="table">
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Téléphone</th>
                <th>Adresse</th>
                <th width="120">Actions</th>
            </tr>

            <?php foreach ($fournisseurs as $f): ?>
                <tr>
                    <td><?= htmlspecialchars($f['nom']) ?></td>
                    <td><?= htmlspecialchars($f['prenom']) ?></td>
                    <td><?= htmlspecialchars($f['telephone']) ?></td>
                    <td><?= htmlspecialchars($f['adresse']) ?></td>
                    <td>
                        <a class="edit" href="?edit=<?= $f['id'] ?>">✏️</a>
                        <a class="delete" href="?delete=<?= $f['id'] ?>" onclick="return confirm('Supprimer ce fournisseur ?')">🗑️</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <?php include 'pied.php'; ?>
</div>