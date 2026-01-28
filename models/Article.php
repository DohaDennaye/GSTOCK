<?php
require_once __DIR__ . '/../config/database.php';

class Article {

    /* =======================
       CRUD BASIQUE
    ======================= */

    public static function getAll() {
        global $pdo;
        return $pdo->query("SELECT * FROM article ORDER BY id DESC")->fetchAll();
    }

    public static function find($id) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM article WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function create($data) {
        global $pdo;

        if (empty($data['nom_article'])) {
            throw new Exception("Le nom de l'article est obligatoire");
        }

        $sql = "INSERT INTO article
            (nom_article, categorie, quantite, prix_unitaire, date_fabrication, date_expiration)
            VALUES (?,?,?,?,?,?)";

        return $pdo->prepare($sql)->execute([
            $data['nom_article'],
            $data['categorie'],
            $data['quantite'],
            $data['prix_unitaire'],
            $data['date_fabrication'],
            $data['date_expiration']
        ]);
    }

    public static function update($id, $data) {
        global $pdo;

        $sql = "UPDATE article
                SET nom_article=?, categorie=?, quantite=?, prix_unitaire=?,
                    date_fabrication=?, date_expiration=?
                WHERE id=?";

        return $pdo->prepare($sql)->execute([
            $data['nom_article'],
            $data['categorie'],
            $data['quantite'],
            $data['prix_unitaire'],
            $data['date_fabrication'],
            $data['date_expiration'],
            $id
        ]);
    }

    public static function delete($id) {
        global $pdo;
        return $pdo->prepare("DELETE FROM article WHERE id=?")->execute([$id]);
    }

    /* =======================
       GESTION DE STOCK
    ======================= */

    // 🔍 Stock actuel
    public static function getStock($id_article) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT quantite FROM article WHERE id=?");
        $stmt->execute([$id_article]);
        return (int) $stmt->fetchColumn();
    }

    // ⬇️ Diminuer stock (vente)
    public static function decreaseStock($id_article, $quantite) {
        global $pdo;

        $sql = "
            UPDATE article
            SET quantite = quantite - ?
            WHERE id = ? AND quantite >= ?
        ";

        return $pdo->prepare($sql)->execute([
            $quantite,
            $id_article,
            $quantite
        ]);
    }

    // ⚠️ Articles en rupture ou stock faible
    public static function rupture($seuil = 5) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM article WHERE quantite <= ?");
        $stmt->execute([$seuil]);
        return $stmt->fetchAll();
    }
}
?>