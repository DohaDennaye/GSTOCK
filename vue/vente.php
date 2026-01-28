<?php
include 'entete.php';
require_once __DIR__ . '/../models/Client.php';
require_once __DIR__ . '/../models/Vente.php';
require_once __DIR__ . '/../models/Article.php';

/* ===== AJOUT VENTE ===== */
if (isset($_POST['add'])) {
    $id_article = (int) $_POST['id_article'];
    $id_client  = (int) $_POST['id_client'];
    $quantite   = max(1, (int) $_POST['quantite']);

    $stock = Article::getStock($id_article);
    if ($stock < $quantite) {
        $_SESSION['error'] = "Stock insuffisant (actuel : $stock)";
        header("Location: vente.php"); exit;
    }

    $article = Article::find($id_article);
    $prix = $quantite * $article['prix_unitaire'];

    if (Vente::create([
        'id_article'=>$id_article,
        'id_client'=>$id_client,
        'quantite'=>$quantite,
        'prix'=>$prix
    ])) {
        Article::decreaseStock($id_article, $quantite);
        $_SESSION['success'] = "Vente ajoutée avec succès";
    }
    header("Location: vente.php"); exit;
}

/* ===== DELETE ===== */
if (isset($_GET['delete'])) {
    $v = Vente::find($_GET['delete']);
    if ($v) {
        Article::decreaseStock($v['id_article'], -$v['quantite']);
        Vente::delete($_GET['delete']);
    }
    header("Location: vente.php"); exit;
}

$ventes   = Vente::getAll();
$articles = Article::getAll();
$clients  = Client::getAll();

$totalVentes = count($ventes);
$chiffreAffaire = array_sum(array_column($ventes,'prix'));
?>

<div class="home-content">

<h2>Gestion des ventes</h2><br/>

<!-- KPI -->
<div class="overview-boxes">
    <div class="box">
        <div class="right-side">
            <div class="box-topic">Ventes</div>
            <div class="number"><?= $totalVentes ?></div>
        </div>
    </div>

    <div class="box">
        <div class="right-side">
            <div class="box-topic">Chiffre d'affaires</div>
            <div class="number"><?= number_format($chiffreAffaire,2) ?> DH</div>
        </div>
    </div>
</div>

<?php
if(isset($_SESSION['success'])){ echo "<p style='color:green'>".$_SESSION['success']."</p>"; unset($_SESSION['success']); }
if(isset($_SESSION['error'])){ echo "<p style='color:red'>".$_SESSION['error']."</p>"; unset($_SESSION['error']); }
?>
<br/>
<!-- FORM -->
<div class="overview-boxes">
<div class="box">
<h3>Ajouter une vente</h3><br/>

<form method="post" class="form-article">
    <label>Client</label>
    <select name="id_client" required>
        <option value="">-- Choisir --</option>
        <?php foreach($clients as $c): ?>
            <option value="<?= $c['id'] ?>"><?= $c['nom'].' '.$c['prenom'] ?></option>
        <?php endforeach; ?>
    </select>

    <label>Article</label>
    <select name="id_article" required>
        <?php foreach($articles as $a): ?>
            <option value="<?= $a['id'] ?>">
                <?= $a['nom_article'] ?> (Stock <?= $a['quantite'] ?>)
            </option>
        <?php endforeach; ?>
    </select>

    <label>Quantité</label>
    <input type="number" name="quantite" min="1" required>

    <button name="add">Valider</button>
</form>
</div>
</div><br/>

<!-- TABLE -->
<h2>Historique des ventes</h2>

<table border="1" cellpadding="10" cellspacing="0">
<tr>
    <th>Date</th>
    <th>Article</th>
    <th>Client</th>
    <th>Qté</th>
    <th>Total</th>
    <th>Action</th>
</tr>

<?php foreach($ventes as $v): ?>
<tr>
    <td><?= $v['date_vente'] ?></td>
    <td><?= $v['nom_article'] ?></td>
    <td><?= $v['client_nom'].' '.$v['client_prenom'] ?></td>
    <td><?= $v['quantite'] ?></td>
    <td><?= number_format($v['prix'],2) ?></td>
    <td>
        <a href="?delete=<?= $v['id'] ?>" onclick="return confirm('Supprimer ?')">🗑️</a>
    </td>
</tr>
<?php endforeach; ?>
</table>

</div>
<?php include 'pied.php'; ?>
