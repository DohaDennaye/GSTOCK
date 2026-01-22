<?php
include 'entete.php';
require_once __DIR__ . '/../models/Commande.php';
require_once __DIR__ . '/../models/Article.php';
require_once __DIR__ . '/../models/Fournisseur.php';

// ---------------------
// AJOUT COMMANDE
// ---------------------
if (isset($_POST['add'])) {
    if (Commande::create($_POST)) {
        $_SESSION['success'] = "Commande ajoutée avec succès !";
    } else {
        $_SESSION['error'] = "Erreur lors de l'ajout de la commande.";
    }
    header("Location: commande.php");
    exit;
}

// ---------------------
// MODIFIER COMMANDE
// ---------------------
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    if (Commande::update($id, $_POST)) {
        $_SESSION['success'] = "Commande modifiée avec succès !";
    } else {
        $_SESSION['error'] = "Erreur lors de la modification de la commande.";
    }
    header("Location: commande.php");
    exit;
}

// ---------------------
// SUPPRIMER COMMANDE
// ---------------------
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    if (Commande::delete($id)) {
        $_SESSION['success'] = "Commande supprimée !";
    } else {
        $_SESSION['error'] = "Erreur lors de la suppression.";
    }
    header("Location: commande.php");
    exit;
}

// ---------------------
// EDIT COMMANDE (remplir formulaire)
// ---------------------
$commandeEdit = null;
if (isset($_GET['edit'])) {
    $allCommandes = Commande::getAll();
    foreach ($allCommandes as $c) {
        if ($c['id'] == $_GET['edit']) {
            $commandeEdit = $c;
            break;
        }
    }
}

// ---------------------
// LISTE COMMANDES
// ---------------------
$commandes = Commande::getAll();
$articles = Article::getAll();
$fournisseurs = Fournisseur::getAll();
?>

<div class="home-content">
    <h2><?= $commandeEdit ? "Modifier une commande" : "Ajouter une commande" ?></h2><br>

    <div class="overview-boxes">
        <div class="box">
            <?php
            if(isset($_SESSION['success'])){
                echo '<p style="color:green">'.$_SESSION['success'].'</p>';
                unset($_SESSION['success']);
            }
            if(isset($_SESSION['error'])){
                echo '<p style="color:red">'.$_SESSION['error'].'</p>';
                unset($_SESSION['error']);
            }
            ?>

            <form method="post">
                <?php if($commandeEdit): ?>
                    <input type="hidden" name="id" value="<?= $commandeEdit['id'] ?>">
                <?php endif; ?>

                <label>Article</label>
                <select name="id_article" required>
                    <option value="">--Choisir un article--</option>
                    <?php foreach($articles as $a): ?>
                        <option value="<?= $a['id'] ?>" <?= isset($commandeEdit['id_article']) && $commandeEdit['id_article']==$a['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($a['nom_article']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>Fournisseur</label>
                <select name="id_fournisseur" required>
                    <option value="">--Choisir un fournisseur--</option>
                    <?php foreach($fournisseurs as $f): ?>
                        <option value="<?= $f['id'] ?>" <?= isset($commandeEdit['id_fournisseur']) && $commandeEdit['id_fournisseur']==$f['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($f['nom'].' '.$f['prenom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>Quantité</label>
                <input type="number" name="quantite" value="<?= $commandeEdit['quantite'] ?? '' ?>" required>

                <label>Prix</label>
                <input type="number" name="prix" value="<?= $commandeEdit['prix'] ?? '' ?>" required>

                <button type="submit" name="<?= $commandeEdit ? 'update' : 'add' ?>">
                    <?= $commandeEdit ? 'Modifier' : 'Ajouter' ?>
                </button>
            </form>
        </div>
    </div>

    <h2>Liste des commandes</h2>
    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Article</th>
            <th>Fournisseur</th>
            <th>Quantité</th>
            <th>Prix</th>
            <th>Date commande</th>
            <th>Actions</th>
        </tr>
        <?php if($commandes): ?>
            <?php foreach($commandes as $c): ?>
                <tr>
                    <td><?= $c['id'] ?></td>
                    <td><?= htmlspecialchars($c['nom_article']) ?></td>
                    <td><?= htmlspecialchars($c['fournisseur_nom'].' '.$c['fournisseur_prenom']) ?></td>
                    <td><?= $c['quantite'] ?></td>
                    <td><?= $c['prix'] ?></td>
                    <td><?= $c['date_commande'] ?></td>
                    <td>
                        <a href="commande.php?edit=<?= $c['id'] ?>">✏️ Modifier</a>
                        <a href="commande.php?delete=<?= $c['id'] ?>" onclick="return confirm('Supprimer cette commande ?')">🗑️ Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7">Aucune commande trouvée</td>
            </tr>
        <?php endif; ?>
    </table>
    <?php include 'pied.php'; ?>
</div>