<?php
require_once __DIR__ . '/../config/database.php';

class Client {

    // 🔹 Récupérer tous les clients
    public static function getAll() {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM client ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 🔹 Récupérer un client par ID (EDIT)
    public static function find($id) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM client WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 🔹 Ajouter un client (CREATE)
    public static function create($data) {
        global $pdo;
        $sql = "INSERT INTO client (nom, prenom, telephone, adresse)
                VALUES (?, ?, ?, ?)";

        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            $data['nom'],
            $data['prenom'],
            $data['telephone'],
            $data['adresse']
        ]);
    }

    // 🔹 Modifier un client (UPDATE)
    public static function update($id, $data) {
        global $pdo;
        $sql = "UPDATE client 
                SET nom = ?, prenom = ?, telephone = ?, adresse = ?
                WHERE id = ?";

        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            $data['nom'],
            $data['prenom'],
            $data['telephone'],
            $data['adresse'],
            $id
        ]);
    }

    // 🔹 Supprimer un client (DELETE)
    public static function delete($id) {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM client WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>