<?php
include 'entete.php';
include '../model/connexion.php'; // Connexion à la DB

if(!isset($_GET['id']) || empty($_GET['id'])){
    $_SESSION['error'] = "Article introuvable.";
    header("Location: article.php");
    exit;
}

$id = intval($_GET['id']);

// Récupérer l'article
$sql = "SELECT * FROM article WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if($result->num_rows == 0){
    $_SESSION['error'] = "Article introuvable.";
    header("Location: article.php");
    exit;
}

$article = $result->fetch_assoc();
$stmt->close();
?>

<div class="home-content">
    <h2>Modifier l'article</h2>
    <div class="overview-boxes">
        <div class="box">
            <!-- Messages session -->
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
            <!-- Formulaire de modification -->
            <form action="../model/updateArticle.php" method="POST" class="form-article">
                <input type="hidden" name="id" value="<?= $article['id'] ?>">

                <label for="nom-article">Nom de l'article</label>
                <input type="text" id="nom-article" name="nom-article" value="<?= htmlspecialchars($article['nom_article']) ?>" required />

                <label for="categorie">Catégorie</label>
                <select name="categorie" id="categorie" required>
                    <option value="Ordinateur" <?= $article['categorie']=='Ordinateur' ? 'selected' : '' ?>>Ordinateur</option>
                    <option value="Imprimante" <?= $article['categorie']=='Imprimante' ? 'selected' : '' ?>>Imprimante</option>
                    <option value="Accessoire" <?= $article['categorie']=='Accessoire' ? 'selected' : '' ?>>Accessoire</option>
                </select>

                <label for="quantite">Quantité</label>
                <input type="number" id="quantite" name="quantite" value="<?= $article['quantite'] ?>" required />

                <label for="prix_unitaire">Prix unitaire</label>
                <input type="number" id="prix_unitaire" name="prix_unitaire" step="0.01" value="<?= $article['prix_unitaire'] ?>" required />

                <label for="date_fabrication">Date de fabrication</label>
                <input type="datetime-local" id="date_fabrication" name="date_fabrication" value="<?= date('Y-m-d\TH:i', strtotime($article['date_fabrication'])) ?>" required />

                <label for="date_expiration">Date d'expiration</label>
                <input type="datetime-local" id="date_expiration" name="date_expiration" value="<?= date('Y-m-d\TH:i', strtotime($article['date_expiration'])) ?>" required />

                <button type="submit" class="btn btn-primary">Modifier l'article</button>
            </form>
        </div>
    </div>
</div>

<?php
$conn->close();
include 'pied.php';
?>
