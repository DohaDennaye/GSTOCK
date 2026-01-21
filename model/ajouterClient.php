<?php
include 'connexion.php';

if (
    !empty($_POST['nom']) &&
    !empty($_POST['prenom']) &&
    !empty($_POST['telephone']) &&
    !empty($_POST['adresse'])
) {
    $sql = "INSERT INTO client 
        (nom, prenom, telephone, adresse)
        VALUES (?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "ssss",
        $_POST['nom'],
        $_POST['prenom'],
        $_POST['telephone'],
        $_POST['adresse'],
    );

    if ($stmt->execute()) {
        $_SESSION['success'] = "Client ajouté avec succès.";
        header("Location: ../vue/client.php");
        exit;
    } else {
        $_SESSION['error'] = "Erreur lors de l'ajout.";
        header("Location: ../model/ajouterClient.php");
        exit;
    }


    $stmt->close();
} else {
    echo "Veuillez remplir tous les champs.";
}

if (isset($conn)) {
    $conn->close();
}
