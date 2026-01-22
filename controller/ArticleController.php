<?php
require_once "../gstock/models/Article.php";

class ArticleController {

    public static function index() {
        return Article::getAll();
    }

    public static function store($data) {
        if (empty($data['nom-article']) || empty($data['categorie'])) {
            $_SESSION['error'] = "Champs obligatoires manquants";
            return false;
        }

        return Article::create($data);
    }

    public static function update($id, $data) {
        return Article::update($id, $data);
    }

    public static function delete($id) {
        return Article::delete($id);
    }

    public static function rupture() {
        return Article::rupture();
    }
}
