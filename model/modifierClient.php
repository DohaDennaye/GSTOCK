<?php
include 'entete.php';
include '../model/connexion.php'; // Connexion à la DB

if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "Client introuvable.";
    header("Location: ../vue/client.php");
    exit;
}

$id = intval($_GET['id']);

// Récupérer le client
$sql = "SELECT * FROM client WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 0) {
    $_SESSION['error'] = "Client introuvable.";
    header("Location: client.php");
    exit;
}

$client = $result->fetch_assoc();
$stmt->close();
?>

<div class="home-content">
    <h2>Modifier le client</h2>
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
            <!-- Formulaire de modification -->
            <form action="../model/updateClient.php" method="POST" class="form-article">
                <input type="hidden" name="id" value="<?= $client['id'] ?>">

                <label for="nom-client">Nom de client</label>
                <input type="text" id="nom-client" name="nom" value="<?= htmlspecialchars($client['nom']) ?>" required />
                <label for="Prenom">Prénom</label>
                <input type="text" id="Prenom" name="prenom" value="<?= htmlspecialchars($client['prenom']) ?>" required />

                <label for="telephone">Téléphone</label>
                <input type="text" id="telephone" name="telephone" value="<?= htmlspecialchars($client['telephone']) ?>" required />
                <label for="adresse">Adresse</label>
                <input type="text" id="adresse" name="adresse" value="<?= htmlspecialchars($client['adresse']) ?>" required />
                <button type="submit" class="btn btn-primary">Modifier le Client</button>
            </form>
        </div>
    </div>
</div>

<?php
$conn->close();
include 'pied.php';
?>