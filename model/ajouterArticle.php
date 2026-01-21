<?php
include 'connexion.php';

if (
    !empty($_POST['nom-article']) &&
    !empty($_POST['categorie']) &&
    !empty($_POST['quantite']) &&
    !empty($_POST['prix_unitaire']) &&
    !empty($_POST['date_fabrication']) &&
    !empty($_POST['date_expiration'])
) {
    $sql = "INSERT INTO article 
        (nom_article, categorie, quantite, prix_unitaire, date_fabrication, date_expiration)
        VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "ssidss",
        $_POST['nom-article'],
        $_POST['categorie'],
        $_POST['quantite'],
        $_POST['prix_unitaire'],
        $_POST['date_fabrication'],
        $_POST['date_expiration']
    );

    if ($stmt->execute()) {
        $_SESSION['success'] = "Article ajouté avec succès.";
        header("Location: ../vue/articles.php");
        exit;
    } else {
        $_SESSION['error'] = "Erreur lors de l'ajout.";
        header("Location: ../model/ajouterArticle.php");
        exit;
    }


    $stmt->close();
} else {
    echo "Veuillez remplir tous les champs.";
}

if (isset($conn)) {
    $conn->close();
}
