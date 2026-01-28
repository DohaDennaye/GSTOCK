<?php
require_once "../models/Vente.php";

class VenteController {

    public static function index() {
        return Vente::getAll();
    }

    public static function store($data) {
        if (empty($data['id_article']) || empty($data['id_client']) || empty($data['quantite']) || empty($data['prix'])) {
            $_SESSION['error'] = "Champs obligatoires manquants";
            return false;
        }

        return Vente::create($data);
    }

    public static function update($id, $data) {
        return Vente::update($id, $data);
    }

    public static function delete($id) {
        return Vente::delete($id);
    }
}
?>
