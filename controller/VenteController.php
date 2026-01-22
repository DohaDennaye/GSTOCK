<?php
require_once __DIR__ . '/../models/Vente.php';
require_once __DIR__ . '/../models/Article.php';

class VenteController {

    public static function index() {
        return Vente::getAll();
    }

    public static function store($data) {

        // Vérifier stock
        $article = Article::find($data['id_article']);

        if (!$article) {
            $_SESSION['error'] = "Article introuvable";
            return false;
        }

        if ($article['quantite'] < $data['quantite']) {
            $_SESSION['error'] = "Stock insuffisant";
            return false;
        }

        // Calcul prix total
        $prixTotal = $data['quantite'] * $article['prix_unitaire'];

        // Ajouter vente
        Vente::create([
            'id_article' => $data['id_article'],
            'id_client'  => $data['id_client'],
            'quantite'   => $data['quantite'],
            'prix'       => $prixTotal
        ]);

        // Mettre à jour stock
        Article::decrementStock($data['id_article'], $data['quantite']);

        $_SESSION['success'] = "Vente enregistrée avec succès";
        return true;
    }

    public static function delete($id) {
        return Vente::delete($id);
    }
}
?>