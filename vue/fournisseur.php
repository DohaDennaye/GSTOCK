<?php
include 'entete.php';
require_once __DIR__ . '/../models/Fournisseur.php';

// 🔹 AJOUT FOURNISSEUR
if (isset($_POST['add'])) {
    if(Fournisseur::create($_POST)) {
        $_SESSION['success'] = "Fournisseur ajouté avec succès !";
    } else {
        $_SESSION['error'] = "Erreur lors de l'ajout du fournisseur.";
    }
    header("Location: fournisseur.php");
    exit;
}

// 🔹 MODIFICATION FOURNISSEUR
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    if(Fournisseur::update($id, $_POST)) {
        $_SESSION['success'] = "Fournisseur modifié avec succès !";
    } else {
        $_SESSION['error'] = "Erreur lors de la modification du fournisseur.";
    }
    header("Location: fournisseur.php");
    exit;
}

// 🔹 SUPPRESSION FOURNISSEUR
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    if(Fournisseur::delete($id)) {
        $_SESSION['success'] = "Fournisseur supprimé !";
    } else {
        $_SESSION['error'] = "Erreur lors de la suppression du fournisseur.";
    }
    header("Location: fournisseur.php");
    exit;
}

// 🔹 RÉCUPÉRER FOURNISSEUR POUR EDIT
$fournisseurEdit = null;
if (isset($_GET['edit'])) {
    $fournisseurEdit = Fournisseur::getById($_GET['edit']);
}

// 🔹 LISTE DES FOURNISSEURS
$fournisseurs = Fournisseur::getAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des fournisseurs</title>
</head>
<body>

<h2><?= $fournisseurEdit ? "Modifier le fournisseur" : "Ajouter un fournisseur" ?></h2>

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
    <?php if ($fournisseurEdit): ?>
        <input type="hidden" name="id" value="<?= $fournisseurEdit['id'] ?>">
    <?php endif; ?>

    <input type="text" name="nom" placeholder="Nom"
           value="<?= $fournisseurEdit['nom'] ?? '' ?>" required><br><br>

    <input type="text" name="prenom" placeholder="Prénom"
           value="<?= $fournisseurEdit['prenom'] ?? '' ?>" required><br><br>

    <input type="text" name="telephone" placeholder="Téléphone"
           value="<?= $fournisseurEdit['telephone'] ?? '' ?>" required><br><br>

    <input type="text" name="adresse" placeholder="Adresse"
           value="<?= $fournisseurEdit['adresse'] ?? '' ?>" required><br><br>

    <button type="submit" name="<?= $fournisseurEdit ? 'update' : 'add' ?>">
        <?= $fournisseurEdit ? 'Modifier' : 'Ajouter' ?>
    </button>
</form>

<hr>

<h2>Liste des fournisseurs</h2>

<table border="1" cellpadding="8">
    <tr>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Téléphone</th>
        <th>Adresse</th>
        <th>Actions</th>
    </tr>

    <?php if($fournisseurs): ?>
        <?php foreach ($fournisseurs as $f): ?>
            <tr>
                <td><?= htmlspecialchars($f['nom']) ?></td>
                <td><?= htmlspecialchars($f['prenom']) ?></td>
                <td><?= htmlspecialchars($f['telephone']) ?></td>
                <td><?= htmlspecialchars($f['adresse']) ?></td>
                <td>
                    <a href="fournisseur.php?edit=<?= $f['id'] ?>">✏️</a>
                    <a href="fournisseur.php?delete=<?= $f['id'] ?>" 
                       onclick="return confirm('Supprimer ce fournisseur ?')">🗑️</a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="5">Aucun fournisseur trouvé</td>
        </tr>
    <?php endif; ?>
</table>
<?php include 'pied.php'; ?>
</body>
</html>
