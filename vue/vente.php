<?php
include 'entete.php';
include '../model/connexion.php';

// ventes (pour le tableau)
$sqlVentes = "SELECT * FROM vente ORDER BY id DESC";
$resultVentes = $conn->query($sqlVentes);

// ventes (pour le select)
$sqlVentesSelect = "SELECT * FROM vente ORDER BY id DESC";
$resultVentesSelect = $conn->query($sqlVentesSelect);

?>

<div class="home-content">
    <h2>Ajouter un nouvel article</h2></br>
    <div class="overview-boxes">
        <div class="box">
            <!-- Messages session -->
            <?php
            if (isset($_SESSION['success'])) {
                echo '<p style="color:green">' . $_SESSION['success'] . '</p>';
                unset($_SESSION['success']);
            }
            if (isset($_SESSION['error'])) {
                echo '<p style="color:red">' . $_SESSION['error'] . '</p>';
                unset($_SESSION['error']);
            }
            ?>
            <!-- Formulaire d'ajout -->
            <form action="../model/<?= isset($_GET['id']) ? 'modifierArticle.php' : 'ajouterArticle.php' ?>" method="POST" class="form-article">
                <input type="text" id="<?= $_GET['id'] ?>" name="nom-article" placeholder="Nom de l'article" required />

                <label for="id_article">Article</label>
                <select name="id_article" id="id_article" required>

                    <option value="">-- Choisir un article --</option>

                    <?php if ($resultVentesSelect && $resultVentesSelect->num_rows > 0): ?>
                        <?php while ($row = $resultVentesSelect->fetch_assoc()): ?>
                            <option value="<?= $row['id'] ?>">
                                <?= htmlspecialchars($row['nom_article']) ?>
                            </option>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <option disabled>Aucun article trouvé</option>
                    <?php endif; ?>

                </select>


                <label for="quantite">Quantité</label>
                <input type="number" id="quantite" name="quantite" placeholder="Quantité" required />

                <label for="prix_unitaire">Prix unitaire</label>
                <input type="number" id="prix_unitaire" name="prix_unitaire" placeholder="Prix unitaire" step="0.01" required />

                <label for="date_fabrication">Date de fabrication</label>
                <input type="datetime-local" id="date_fabrication" name="date_fabrication" required />

                <label for="date_expiration">Date d'expiration</label>
                <input type="datetime-local" id="date_expiration" name="date_expiration" required />

                <button type="submit" class="btn btn-primary">Ajouter l'article</button>
            </form>
        </div>
    </div>

    <h2>Liste des articles</h2>
    <table border="1" class="table-articles" cellpadding="10" cellspacing="0">
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
        <?php if ($resultVentes && $resultVentes->num_rows > 0): ?>
            <?php while ($row = $resultVentes->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['nom_article']) ?></td>
                    <td><?= htmlspecialchars($row['categorie']) ?></td>
                    <td><?= $row['quantite'] ?></td>
                    <td><?= $row['prix_unitaire'] ?></td>
                    <td><?= $row['date_fabrication'] ?></td>
                    <td><?= $row['date_expiration'] ?></td>
                    <td>
                        <a href="../model/modifierArticle.php?id=<?= $row['id'] ?>" class="edit"> Modifier</a>
                        <a href="../model/supprimerArticle.php?id=<?= $row['id'] ?>" class="delete" onclick="return confirm('Supprimer cet article ?')">Supprimer</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="8">Aucun article trouvé</td>
            </tr>
        <?php endif; ?>
    </table>
</div>

<?php
$conn->close();
include 'pied.php';
?>