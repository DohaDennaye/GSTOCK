<?php 
include 'entete.php';
include '../model/connexion.php'; // Connexion à la DB

// Récupérer tous les articles
$sql = "SELECT * FROM client ORDER BY id DESC";
$result = $conn->query($sql);
?>

<div class="home-content">
    <h2>Ajouter un nouvel client</h2></br>
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
            <!-- Formulaire d'ajout -->
            <form action="../model/ajouterClient.php" method="POST" class="form-article">
                <label for="nom-client">Nom de client</label>
                <input type="text" id="nom-client" name="nom" placeholder="Nom de client" required />

                <label for="Prenom">Prénom</label>
                <input type="text" id="Prenom" name="prenom" placeholder="Prénom" required />

                <label for="telephone">Téléphone</label>
                <input type="text" id="telephone" name="telephone" placeholder="Téléphone" required />

                <label for="adresse">Adresse</label>
                <input type="text" id="adresse" name="adresse" placeholder="Adresse" required />

                <button type="submit" class="btn btn-primary">Ajouter le Client</button>
            </form>
        </div>
    </div>

    <h2>Liste des clients</h2>
    <table border="1" class="table-articles" cellpadding="10" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Téléphone</th>
            <th>Adresse</th>
            <th>Actions</th>
        </tr>
        <?php if($result && $result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['nom']) ?></td>
                    <td><?= htmlspecialchars($row['prenom']) ?></td>
                    <td><?= $row['telephone'] ?></td>
                    <td><?= $row['adresse'] ?></td>
                    <td>
                        <a href="modifierClient.php?id=<?= $row['id'] ?>" class="edit"> Modifier</a>
                        <a href="../model/supprimerClient.php?id=<?= $row['id'] ?>" class="delete" onclick="return confirm('Supprimer cet article ?')">Supprimer</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="8">Aucun client trouvé</td>
            </tr>
        <?php endif; ?>
    </table>
</div>

<?php
$conn->close();
include 'pied.php';
?>