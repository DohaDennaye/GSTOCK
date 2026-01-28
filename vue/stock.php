<?php
include 'entete.php';
require_once __DIR__ . '/../models/Article.php';

$articles = Article::getAll();

$total = count($articles);
$rupture = $faible = 0;
$valeur = 0;

foreach($articles as $a){
    if($a['quantite'] <= 0) $rupture++;
    elseif($a['quantite'] <= 5) $faible++;
    $valeur += $a['quantite'] * $a['prix_unitaire'];
}
?>

<div class="home-content">

<h2>Gestion du stock</h2><br/>

<!-- KPI -->
<div class="overview-boxes">
    <div class="box">
        <div class="right-side">
            <div class="box-topic">Articles</div>
            <div class="number"><?= $total ?></div>
        </div>
    </div>

    <div class="box">
        <div class="right-side">
            <div class="box-topic">Rupture</div>
            <div class="number"><?= $rupture ?></div>
        </div>
    </div>

    <div class="box">
        <div class="right-side">
            <div class="box-topic">Valeur stock</div>
            <div class="number"><?= number_format($valeur,2) ?> DH</div>
        </div>
    </div>
</div><br/>

<h2>État du stock</h2>

<table border="1" cellpadding="10" cellspacing="0">
<tr>
    <th>Article</th>
    <th>Catégorie</th>
    <th>Stock</th>
    <th>Prix</th>
    <th>Statut</th>
</tr>

<?php foreach($articles as $a): ?>
<tr>
    <td><?= $a['nom_article'] ?></td>
    <td><?= $a['categorie'] ?></td>
    <td><?= $a['quantite'] ?></td>
    <td><?= $a['prix_unitaire'] ?> DH</td>
    <td>
        <?php
        if($a['quantite']<=0) echo "<span style='color:red'>RUPTURE</span>";
        elseif($a['quantite']<=5) echo "<span style='color:orange'>FAIBLE</span>";
        else echo "<span style='color:green'>OK</span>";
        ?>
    </td>
</tr>
<?php endforeach; ?>
</table>

</div>
<?php include 'pied.php'; ?>
