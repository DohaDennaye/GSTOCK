<?php
include 'entete.php';
require_once __DIR__ . '/../models/Client.php';

$search = $_GET['q'] ?? '';
$sql = "SELECT * FROM client";
$params = [];

if (!empty($search)) {
    $sql .= " WHERE nom LIKE ? OR email LIKE ?";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$clients = $stmt->fetchAll();

// AJOUT CLIENT
if (isset($_POST['add'])) {
    Client::create($_POST);
    $_SESSION['success'] = "Client ajouté avec succès";
    header("Location: clients.php");
    exit;
}

// MODIFICATION CLIENT
if (isset($_POST['update'])) {
    Client::update($_POST['id'], $_POST);
    $_SESSION['success'] = "Client modifié avec succès";
    header("Location: clients.php");
    exit;
}

// SUPPRESSION CLIENT
if (isset($_GET['delete'])) {
    Client::delete($_GET['delete']);
    $_SESSION['success'] = "Client supprimé";
    header("Location: clients.php");
    exit;
}

// CLIENT À MODIFIER
$clientEdit = null;
if (isset($_GET['edit'])) {
    $clientEdit = Client::find($_GET['edit']);
}

// LISTE CLIENTS
$clients = Client::getAll();
?>

<div class="home-content">

    <!-- ===== FORMULAIRE ===== -->
    <div class="box">
        <h2><?= $clientEdit ? "Modifier le client" : "Ajouter un client" ?></h2><br />

        <?php if (isset($_SESSION['success'])): ?>
            <p style="color:green"><?= $_SESSION['success'];
                                    unset($_SESSION['success']); ?></p>
        <?php endif; ?>

        <form method="post" class="form-article">
            <?php if ($clientEdit): ?>
                <input type="hidden" name="id" value="<?= $clientEdit['id'] ?>">
            <?php endif; ?>

            <label>Nom</label>
            <input type="text" name="nom" value="<?= $clientEdit['nom'] ?? '' ?>" required>

            <label>Prénom</label>
            <input type="text" name="prenom" value="<?= $clientEdit['prenom'] ?? '' ?>" required>

            <label>Téléphone</label>
            <input type="text" name="telephone" value="<?= $clientEdit['telephone'] ?? '' ?>" required>

            <label>Adresse</label>
            <input type="text" name="adresse" value="<?= $clientEdit['adresse'] ?? '' ?>" required>

            <button type="submit" name="<?= $clientEdit ? 'update' : 'add' ?>">
                <?= $clientEdit ? 'Modifier' : 'Ajouter' ?>
            </button>
        </form>
    </div><br />

    <!-- ===== TABLE CLIENTS ===== -->
    <div class="recent-sales box">
        <h2>Liste des clients</h2>

        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Téléphone</th>
                    <th>Adresse</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($clients): ?>
                    <?php foreach ($clients as $c): ?>
                        <tr>
                            <td><?= htmlspecialchars($c['nom']) ?></td>
                            <td><?= htmlspecialchars($c['prenom']) ?></td>
                            <td><?= htmlspecialchars($c['telephone']) ?></td>
                            <td><?= htmlspecialchars($c['adresse']) ?></td>
                            <td>
                                <a href="clients.php?edit=<?= $c['id'] ?>">✏️</a>
                                <a href="clients.php?delete=<?= $c['id'] ?>"
                                    onclick="return confirm('Supprimer ce client ?')">🗑️</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">Aucun client trouvé</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<?php include 'pied.php'; ?>