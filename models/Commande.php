<?php
require_once __DIR__ . '/../config/database.php';

class Commande {

    // Récupérer toutes les commandes
    public static function getAll() {
        global $pdo;
        $sql = "
            SELECT co.id, a.nom_article, f.nom AS fournisseur_nom, f.prenom AS fournisseur_prenom, co.quantite, co.prix, co.date_commande
            FROM commande co
            JOIN article a ON co.id_article = a.id
            JOIN fournisseur f ON co.id_fournisseur = f.id
            ORDER BY co.id DESC
        ";
        return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Créer une commande
    public static function create($data) {
        global $pdo;
        $sql = "INSERT INTO commande (id_article, id_fournisseur, quantite, prix) VALUES (?,?,?,?)";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            $data['id_article'],
            $data['id_fournisseur'],
            $data['quantite'],
            $data['prix']
        ]);
    }

    // Modifier une commande
    public static function update($id, $data) {
        global $pdo;
        $sql = "UPDATE commande SET id_article=?, id_fournisseur=?, quantite=?, prix=? WHERE id=?";
        return $pdo->prepare($sql)->execute([
            $data['id_article'],
            $data['id_fournisseur'],
            $data['quantite'],
            $data['prix'],
            $id
        ]);
    }

    // Supprimer une commande
    public static function delete($id) {
        global $pdo;
        return $pdo->prepare("DELETE FROM commande WHERE id=?")->execute([$id]);
    }

    // Récupérer une commande par ID
    public static function getById($id) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM commande WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
