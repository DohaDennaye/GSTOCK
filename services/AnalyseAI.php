<?php
require_once __DIR__ . '/../models/Article.php';
require_once __DIR__ . '/../models/Vente.php';
require_once __DIR__ . '/../models/Fournisseur.php';

class AnalyseAI
{

    public static function chat($question, $history)
    {
        $_SESSION['ai_context'][] = $question;

        $q = strtolower(trim($question));

        // 🔍 Récupération des données
        $articles = Article::getAll();
        $ventes = Vente::getAll();
        $fournisseurs = Fournisseur::getAll();

        /* ======================
           ANALYSE INTELLIGENTE
        ====================== */

        // 👉 Rupture / stock
        if (self::contains($q, ['rupture', 'manque', 'faible', 'stock', 'vide'])) {
            return self::analyseStock($articles);
        }

        // 👉 Prévision / futur
        if (self::contains($q, ['prévoir', 'avenir', 'futur', 'demain', 'semaine', 'mois', 'projection'])) {
            return self::prediction($articles, $ventes);
        }

        // 👉 Vente / chiffre
        if (self::contains($q, ['vente', 'vendu', 'argent', 'chiffre', 'revenu'])) {
            return self::analyseVentes($ventes);
        }

        // 👉 Fournisseur
        if (self::contains($q, ['fournisseur', 'approvisionnement', 'commande'])) {
            return self::analyseFournisseurs($fournisseurs);
        }

        // 👉 Conseil global
        if (self::contains($q, ['conseil', 'recommande', 'optimiser', 'améliorer', 'avis'])) {
            return self::conseilGlobal($articles, $ventes);
        }

        // 🧠 QUESTION GÉNÉRALE → ANALYSE AUTOMATIQUE
        return self::analyseGlobale($articles, $ventes);
    }

    /* ======================
       MÉTHODES INTELLIGENTES
    ====================== */

    private static function contains($text, $keywords)
    {
        foreach ($keywords as $word) {
            if (strpos($text, $word) !== false) {
                return true;
            }
        }
        return false;
    }

    private static function analyseStock($articles)
    {
        $msg = "<b>📦 Analyse experte du stock :</b><br>";
        $danger = false;

        foreach ($articles as $a) {
            if ($a['quantite'] <= 5) {
                $danger = true;
                $msg .= "⚠ {$a['nom_article']} : {$a['quantite']} unités restantes<br>";
            }
        }

        return $danger
            ? $msg . "<br><b>Recommandation :</b> réapprovisionnement urgent."
            : "✅ Aucun risque de rupture immédiat détecté.";
    }

    private static function analyseVentes($ventes)
    {
        $total = array_sum(array_column($ventes, 'prix'));
        $nb = count($ventes);

        return "
        💰 <b>Analyse des ventes :</b><br>
        • Total : <b>$total DH</b><br>
        • Nombre de ventes : <b>$nb</b><br>
        • Performance : " . ($nb > 5 ? "Bonne ↗" : "Faible ⚠") . "
        ";
    }

    private static function prediction($articles, $ventes)
    {
        $risque = 0;
        foreach ($articles as $a) {
            if ($a['quantite'] <= 5) $risque++;
        }

        return "
        🔮 <b>Prévision intelligente :</b><br>
        • Articles à risque : <b>$risque</b><br>
        • Tendance : hausse de la demande<br><br>
        👉 Anticipez vos commandes pour éviter les ruptures.
        ";
    }

    private static function analyseFournisseurs($fournisseurs)
    {
        return "
        🚚 <b>Analyse fournisseurs :</b><br>
        • Fournisseurs actifs : <b>" . count($fournisseurs) . "</b><br>
        • Conseil : diversifier pour sécuriser l’approvisionnement.
        ";
    }

    private static function conseilGlobal($articles, $ventes)
    {
        return "
        🧠 <b>Conseil d’expert :</b><br>
        • Définir un seuil automatique de stock<br>
        • Prioriser les produits à forte rotation<br>
        • Analyser les ventes hebdomadaires<br><br>
        👉 Cela améliore la rentabilité et la disponibilité.
        ";
    }

    private static function analyseGlobale($articles, $ventes)
    {
        return "
        🤖 <b>Analyse globale automatique :</b><br>
        • Articles : " . count($articles) . "<br>
        • Ventes : " . count($ventes) . "<br><br>
        Posez-moi une question précise ou demandez un conseil.
        ";
    }
}
