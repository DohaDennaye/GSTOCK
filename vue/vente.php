<?php
include 'entete.php';
require_once __DIR__ . '/../models/Client.php';
require_once __DIR__ . '/../models/Vente.php';
require_once __DIR__ . '/../models/Article.php';

// ---------------------
// EDIT VENTE
// ---------------------
$venteEdit = null;
if (isset($_GET['edit'])) {
    $ventes = Vente::getAll();
    foreach ($ventes as $v) {
        if ($v['id'] == $_GET['edit']) {
            $venteEdit = $v;
            break;
        }
    }
}

// ---------------------
// AJOUT VENTE
// ---------------------
if (isset($_POST['add'])) {
    // Récupérer le prix unitaire de l'article sélectionné
    $article = Article::getAll();
    $prix_unitaire = 0;
    foreach ($article as $a) {
        if ($a['id'] == $_POST['id_article']) {
            $prix_unitaire = $a['prix_unitaire'];
            break;
        }
    }
    $_POST['prix'] = $_POST['quantite'] * $prix_unitaire;

    if (Vente::create($_POST)) {
        $_SESSION['success'] = "Vente ajoutée avec succès !";
    } else {
        $_SESSION['error'] = "Erreur lors de l'ajout de la vente.";
    }
    header("Location: vente.php");
    exit;
}

// ---------------------
// MODIFIER VENTE
// ---------------------
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $article = Article::getAll();
    $prix_unitaire = 0;
    foreach ($article as $a) {
        if ($a['id'] == $_POST['id_article']) {
            $prix_unitaire = $a['prix_unitaire'];
            break;
        }
    }
    $_POST['prix'] = $_POST['quantite'] * $prix_unitaire;

    if (Vente::update($id, $_POST)) {
        $_SESSION['success'] = "Vente modifiée avec succès !";
    } else {
        $_SESSION['error'] = "Erreur lors de la modification de la vente.";
    }
    header("Location: vente.php");
    exit;
}

// ---------------------
// SUPPRIMER VENTE
// ---------------------
if (isset($_GET['delete'])) {
    if (Vente::delete($_GET['delete'])) {
        $_SESSION['success'] = "Vente supprimée !";
    } else {
        $_SESSION['error'] = "Erreur lors de la suppression.";
    }
    header("Location: vente.php");
    exit;
}

// ---------------------
// LISTE VENTES
// ---------------------
$ventes = Vente::getAll();
$articles = Article::getAll();
$clients = Client::getAll();
?>

<div class="home-content">
    <h2><?= $venteEdit ? "Modifier une vente" : "Ajouter une vente" ?></h2><br>

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
                <?php if($venteEdit): ?>
                    <input type="hidden" name="id" value="<?= $venteEdit['id'] ?>">
                <?php endif; ?>

                <label>Client</label>
                <select name="id_client" required>
                    <option value="">--Choisir un client--</option>
                    <?php foreach($clients as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= $venteEdit && $venteEdit['id_client']==$c['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['nom'].' '.$c['prenom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>Article</label>
                <select name="id_article" required>
                    <option value="">--Choisir un article--</option>
                    <?php foreach($articles as $a): ?>
                        <option value="<?= $a['id'] ?>" <?= $venteEdit && $venteEdit['id_article']==$a['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($a['nom_article']) ?> (Prix unitaire: <?= $a['prix_unitaire'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>Quantité</label>
                <input type="number" name="quantite" value="<?= $venteEdit['quantite'] ?? '' ?>" placeholder="Quantité" required>

                <button type="submit" name="<?= $venteEdit ? 'update' : 'add' ?>">
                    <?= $venteEdit ? 'Modifier' : 'Ajouter' ?>
                </button>
            </form>
        </div>
    </div>

    <h2>Liste des ventes</h2>
    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Article</th>
            <th>Client</th>
            <th>Quantité</th>
            <th>Prix unitaire</th>
            <th>Prix total</th>
            <th>Date vente</th>
            <th>Actions</th>
        </tr>
        <?php if($ventes): ?>
            <?php foreach($ventes as $v): ?>
                <tr>
                    <td><?= $v['id'] ?></td>
                    <td><?= htmlspecialchars($v['nom_article']) ?></td>
                    <td><?= htmlspecialchars($v['client_nom'].' '.$v['client_prenom']) ?></td>
                    <td><?= $v['quantite'] ?></td>
                    <td><?= $v['prix'] / $v['quantite'] ?></td>
                    <td><?= $v['prix'] ?></td>
                    <td><?= $v['date_vente'] ?></td>
                    <td>
                        <a href="vente.php?edit=<?= $v['id'] ?>">✏️ Modifier</a>
                        <a href="vente.php?delete=<?= $v['id'] ?>" onclick="return confirm('Supprimer cette vente ?')">🗑️ Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="8">Aucune vente trouvée</td>
            </tr>
        <?php endif; ?>
    </table>
    <?php include 'pied.php'; ?>
</div>