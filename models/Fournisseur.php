<?php
require_once __DIR__ . '/../config/database.php';

class Fournisseur {

    // Récupérer tous les fournisseurs
    public static function getAll() {
        global $pdo;
        return $pdo->query("SELECT * FROM fournisseur ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
    }

    // Créer un fournisseur
    public static function create($data) {
        global $pdo;
        $sql = "INSERT INTO fournisseur (nom, prenom, telephone, adresse) VALUES (?,?,?,?)";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            $data['nom'],
            $data['prenom'],
            $data['telephone'],
            $data['adresse']
        ]);
    }

    // Modifier un fournisseur
    public static function update($id, $data) {
        global $pdo;
        $sql = "UPDATE fournisseur SET nom=?, prenom=?, telephone=?, adresse=? WHERE id=?";
        return $pdo->prepare($sql)->execute([
            $data['nom'],
            $data['prenom'],
            $data['telephone'],
            $data['adresse'],
            $id
        ]);
    }

    // Supprimer un fournisseur
    public static function delete($id) {
        global $pdo;
        return $pdo->prepare("DELETE FROM fournisseur WHERE id=?")->execute([$id]);
    }

    // Récupérer un fournisseur par ID
    public static function getById($id) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM fournisseur WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>