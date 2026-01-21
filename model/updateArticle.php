<?php
include '../model/connexion.php';
session_start();

if(isset($_POST['id'], $_POST['nom-article'], $_POST['categorie'], $_POST['quantite'], $_POST['prix_unitaire'], $_POST['date_fabrication'], $_POST['date_expiration'])){
    $id = intval($_POST['id']);
    $nomArticle = $_POST['nom-article'];
    $categorie = $_POST['categorie'];
    $quantite = $_POST['quantite'];
    $prixUnitaire = $_POST['prix_unitaire'];
    $dateFabrication = $_POST['date_fabrication'];
    $dateExpiration = $_POST['date_expiration'];

    $sql = "UPDATE article SET nom_article=?, categorie=?, quantite=?, prix_unitaire=?, date_fabrication=?, date_expiration=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssiddsi", $nomArticle, $categorie, $quantite, $prixUnitaire, $dateFabrication, $dateExpiration, $id);

    if($stmt->execute()){
        $_SESSION['success'] = "Article modifié avec succès.";
    } else {
        $_SESSION['error'] = "Erreur lors de la modification : ".$stmt->error;
    }

    $stmt->close();
    $conn->close();
    header("Location: article.php");
    exit;
}else{
    $_SESSION['error'] = "Tous les champs sont requis.";
    header("Location: article.php");
    exit;
}
?>