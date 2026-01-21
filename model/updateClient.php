<?php
include '../model/connexion.php';
session_start();

if (isset($_POST['id'], $_POST['nom'], $_POST['prenom'], $_POST['telephone'], $_POST['adresse'])) {
    $id = intval($_POST['id']);
    $nomClient = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $telephone = $_POST['telephone'];
    $adresse = $_POST['adresse'];

    $sql = "UPDATE client SET nom=?, prenom=?, telephone=?, adresse=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $nomClient, $prenom, $telephone, $adresse, $id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Client modifié avec succès.";
    } else {
        $_SESSION['error'] = "Erreur lors de la modification : " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    header("Location: ../vue/client.php");
    exit;
} else {
    $_SESSION['error'] = "Tous les champs sont requis.";
    header("Location: ../vue/client.php");
    exit;
}
