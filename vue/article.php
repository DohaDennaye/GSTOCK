<?php
include 'entete.php';
require_once __DIR__ . '/../models/Article.php';
// ---------------------
// AJOUT ARTICLE
// ---------------------
if (isset($_POST['add'])) {
    if (Article::create($_POST)) {
        $_SESSION['success'] = "Article ajouté avec succès !";
    } else {
        $_SESSION['error'] = "Erreur lors de l'ajout de l'article.";
    }
    header("Location: article.php");
    exit;
}

// ---------------------
// MODIFIER ARTICLE
// ---------------------
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    if (Article::update($id, $_POST)) {
        $_SESSION['success'] = "Article modifié avec succès !";
    } else {
        $_SESSION['error'] = "Erreur lors de la modification.";
    }
    header("Location: article.php");
    exit;
}

// ---------------------
// SUPPRIMER ARTICLE
// ---------------------
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    if (Article::delete($id)) {
        $_SESSION['success'] = "Article supprimé !";
    } else {
        $_SESSION['error'] = "Erreur lors de la suppression.";
    }
    header("Location: article.php");
    exit;
}

// ---------------------
// EDIT ARTICLE (remplir formulaire)
// ---------------------
$articleEdit = null;
if (isset($_GET['edit'])) {
    $articleEdit = Article::getAll();
    foreach ($articleEdit as $a) {
        if ($a['id'] == $_GET['edit']) {
            $articleEdit = $a;
            break;
        }
    }
}

// ---------------------
// LISTE ARTICLES
// ---------------------
$articles = Article::getAll();
?>
<style>
.home-content {
    padding: 25px;
    margin-top: 70px; /* hauteur du nav */
    background: #f5f7fb;
    min-height: calc(100vh - 70px);
}
</style>
<div class="home-content">
    <h2><?= $articleEdit ? "Modifier un article" : "Ajouter un article" ?></h2><br>

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
            
            <form method="post" class="form-article">
                <?php if ($articleEdit): ?>
                    <input type="hidden" name="id" value="<?= $articleEdit['id'] ?>">
                <?php endif; ?>

                <label>Nom de l'article</label>
                <input type="text" name="nom_article" placeholder="Nom de l'article"
                       value="<?= $articleEdit['nom_article'] ?? '' ?>" required>

                <label>Catégorie</label>
                <select name="categorie" required>
                    <option value="">--Choisir--</option>
                    <option value="Ordinateur" <?= isset($articleEdit['categorie']) && $articleEdit['categorie']=='Ordinateur' ? 'selected' : '' ?>>Ordinateur</option>
                    <option value="Imprimante" <?= isset($articleEdit['categorie']) && $articleEdit['categorie']=='Imprimante' ? 'selected' : '' ?>>Imprimante</option>
                    <option value="Accessoire" <?= isset($articleEdit['categorie']) && $articleEdit['categorie']=='Accessoire' ? 'selected' : '' ?>>Accessoire</option>
                </select>

                <label>Quantité</label>
                <input type="number" name="quantite" value="<?= $articleEdit['quantite'] ?? '' ?>" required>

                <label>Prix unitaire</label>
                <input type="number" name="prix_unitaire" step="0.01" value="<?= $articleEdit['prix_unitaire'] ?? '' ?>" required>

                <label>Date de fabrication</label>
                <input type="datetime-local" name="date_fabrication" 
                       value="<?= isset($articleEdit['date_fabrication']) ? date('Y-m-d\TH:i', strtotime($articleEdit['date_fabrication'])) : '' ?>" required>

                <label>Date d'expiration</label>
                <input type="datetime-local" name="date_expiration" 
                       value="<?= isset($articleEdit['date_expiration']) ? date('Y-m-d\TH:i', strtotime($articleEdit['date_expiration'])) : '' ?>" required>

                <button type="submit" name="<?= $articleEdit ? 'update' : 'add' ?>">
                    <?= $articleEdit ? 'Modifier' : 'Ajouter' ?>
                </button>
            </form>
        </div>
    </div>

    <h2>Liste des articles</h2>
    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Catégorie</th>
            <th>Quantité</th>
            <th>Prix unitaire</th>
            <th>Date fabrication</th>
            <th>Date expiration</th>
            <th>Actions</th>
        </tr>
        <?php if($articles): ?>
            <?php foreach($articles as $row): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['nom_article']) ?></td>
                    <td><?= htmlspecialchars($row['categorie']) ?></td>
                    <td><?= $row['quantite'] ?></td>
                    <td><?= $row['prix_unitaire'] ?></td>
                    <td><?= $row['date_fabrication'] ?></td>
                    <td><?= $row['date_expiration'] ?></td>
                    <td>
                        <a href="article.php?edit=<?= $row['id'] ?>">✏️ Modifier</a>
                        <a href="article.php?delete=<?= $row['id'] ?>" onclick="return confirm('Supprimer cet article ?')">🗑️ Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="8">Aucun article trouvé</td>
            </tr>
        <?php endif; ?>
    </table>
</div>

<?php include 'pied.php'; ?>