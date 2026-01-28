<?php
require_once __DIR__ . '/../config/database.php';

class Client {

    public static function getAll() {
        global $pdo;
        return $pdo->query("SELECT * FROM client ORDER BY id DESC")->fetchAll();
    }

    public static function find($id) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM client WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function create($data) {
        global $pdo;
        $sql = "INSERT INTO client (nom, email, telephone, adresse) VALUES (?,?,?,?)";

        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            $data['nom'],
            $data['email'],
            $data['telephone'],
            $data['adresse']
        ]);
    }

    public static function update($id, $data) {
        global $pdo;
        $sql = "UPDATE client SET nom=?, email=?, telephone=?, adresse=? WHERE id=?";

        return $pdo->prepare($sql)->execute([
            $data['nom'],
            $data['email'],
            $data['telephone'],
            $data['adresse'],
            $id
        ]);
    }

    public static function delete($id) {
        global $pdo;
        return $pdo->prepare("DELETE FROM client WHERE id=?")->execute([$id]);
    }
}
?>
