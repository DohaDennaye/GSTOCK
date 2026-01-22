<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/Article.php';
require_once __DIR__ . '/Client.php';

class Vente {

    // 🔹 Récupérer toutes les ventes avec les noms des clients et articles
    public static function getAll() {
        global $pdo;
        $sql = "
            SELECT 
                v.id,
                v.id_article,
                v.id_client,
                v.quantite,
                v.prix,
                v.date_vente,
                a.nom_article,
                c.nom AS client_nom,
                c.prenom AS client_prenom
            FROM vente v
            JOIN article a ON v.id_article = a.id
            JOIN client c ON v.id_client = c.id
            ORDER BY v.id DESC
        ";
        return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // 🔹 Ajouter une vente
    public static function create($data) {
        global $pdo;
        $sql = "INSERT INTO vente (id_article, id_client, quantite, prix) VALUES (?,?,?,?)";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            $data['id_article'],
            $data['id_client'],
            $data['quantite'],
            $data['prix']
        ]);
    }

    // 🔹 Mettre à jour une vente
    public static function update($id, $data) {
        global $pdo;
        $sql = "UPDATE vente SET id_article=?, id_client=?, quantite=?, prix=? WHERE id=?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            $data['id_article'],
            $data['id_client'],
            $data['quantite'],
            $data['prix'],
            $id
        ]);
    }

    // 🔹 Supprimer une vente
    public static function delete($id) {
        global $pdo;
        $sql = "DELETE FROM vente WHERE id=?";
        return $pdo->prepare($sql)->execute([$id]);
    }

    // 🔹 Récupérer une vente par ID (optionnel pour l'édition)
    public static function find($id) {
        global $pdo;
        $sql = "
            SELECT 
                v.id,
                v.id_article,
                v.id_client,
                v.quantite,
                v.prix,
                v.date_vente,
                a.nom_article,
                c.nom AS client_nom,
                c.prenom AS client_prenom
            FROM vente v
            JOIN article a ON v.id_article = a.id
            JOIN client c ON v.id_client = c.id
            WHERE v.id = ?
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>