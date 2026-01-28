<?php
require_once __DIR__ . '/../config/database.php';
include 'entete.php';

/* ===========================
   KPI PRINCIPAUX
=========================== */
$nbCommandes = $pdo->query("SELECT COUNT(*) FROM commande")->fetchColumn();
$nbVentes    = $pdo->query("SELECT COUNT(*) FROM vente")->fetchColumn();

$revenu = $pdo->query("
    SELECT IFNULL(SUM(prix * quantite),0)
    FROM vente
")->fetchColumn();

$profit = $revenu * 0.20; // 20% exemple

/* ===========================
   VENTES RÉCENTES
=========================== */
$ventesRecentes = $pdo->query("
    SELECT 
        v.date_vente,
        c.nom AS client,
        a.nom_article AS produit,
        (v.prix * v.quantite) AS total
    FROM vente v
    JOIN client c ON v.id_client = c.id
    JOIN article a ON v.id_article = a.id
    ORDER BY v.date_vente DESC
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

/* ===========================
   AUJOURD’HUI vs HIER
=========================== */
$venteToday = $pdo->query("
    SELECT IFNULL(SUM(prix * quantite),0)
    FROM vente
    WHERE DATE(date_vente) = CURDATE()
")->fetchColumn();

$venteYesterday = $pdo->query("
    SELECT IFNULL(SUM(prix * quantite),0)
    FROM vente
    WHERE DATE(date_vente) = CURDATE() - INTERVAL 1 DAY
")->fetchColumn();

$evolution = 0;
if ($venteYesterday > 0) {
  $evolution = (($venteToday - $venteYesterday) / $venteYesterday) * 100;
}

/* ===========================
   GRAPHE 7 JOURS
=========================== */
$stats = $pdo->query("
    SELECT DATE(date_vente) AS jour,
           SUM(prix * quantite) AS total
    FROM vente
    WHERE date_vente >= CURDATE() - INTERVAL 6 DAY
    GROUP BY jour
    ORDER BY jour
")->fetchAll(PDO::FETCH_ASSOC);

$labels = array_column($stats, 'jour');
$data   = array_column($stats, 'total');

/* ===========================
   TOP PRODUITS
=========================== */
$topProduits = $pdo->query("
    SELECT a.nom_article, SUM(v.quantite) AS qte
    FROM vente v
    JOIN article a ON v.id_article = a.id
    GROUP BY a.nom_article
    ORDER BY qte DESC
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

$pLabels = array_column($topProduits, 'nom_article');
$pData   = array_column($topProduits, 'qte');
?>

<div class="home-content">
  <!-- ================= KPI ================= -->
  <div class="overview-boxes">

    <div class="box" style="background:linear-gradient(135deg,#2563eb,#1e40af)">
      <div class="right-side">
        <div class="box-topic">Commandes</div>
        <div class="number"><?= $nbCommandes ?></div>
      </div>
      <i class="bx bx-cart-alt cart"></i>
    </div>

    <div class="box" style="background:linear-gradient(135deg,#16a34a,#166534)">
      <div class="right-side">
        <div class="box-topic">Ventes</div>
        <div class="number"><?= $nbVentes ?></div>
      </div>
      <i class="bx bxs-cart-add cart two"></i>
    </div>

    <div class="box" style="background:linear-gradient(135deg,#f59e0b,#b45309)">
      <div class="right-side">
        <div class="box-topic">Revenu</div>
        <div class="number"><?= number_format($revenu, 2) ?> DH</div>
      </div>
      <i class="bx bx-money cart three"></i>
    </div>

    <div class="box" style="background:linear-gradient(135deg,#dc2626,#7f1d1d)">
      <div class="right-side">
        <div class="box-topic">Profit</div>
        <div class="number"><?= number_format($profit, 2) ?> DH</div>
      </div>
      <i class="bx bx-trending-up cart four"></i>
    </div>

  </div>

  <!-- ================= COMPARAISON ================= -->
  <div class="box">
    <div class="right-side">
      <div class="box-topic">Revenu aujourd'hui</div>
      <div class="number"><?= number_format($venteToday, 2) ?> DH</div>
      <div class="indicator">
        <?php if ($evolution >= 0): ?>
          <i class="bx bx-up-arrow-alt"></i>
          <span class="text">+<?= number_format($evolution, 1) ?> % vs hier</span>
        <?php else: ?>
          <i class="bx bx-down-arrow-alt down"></i>
          <span class="text"><?= number_format($evolution, 1) ?> % vs hier</span>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- ================= GRAPHES ================= -->
  <div class="sales-boxes">
    <div class="box">
      <h3>Ventes - 7 derniers jours</h3>
      <canvas id="venteChart"></canvas>
    </div>

    <div class="box">
      <h3>Top produits vendus</h3>
      <canvas id="produitChart"></canvas>
    </div>
  </div>

  <!-- ================= TABLE ================= -->
  <div class="recent-sales box">
    <h3>Ventes récentes</h3>
    <table>
      <tr>
        <th>Date</th>
        <th>Client</th>
        <th>Produit</th>
        <th>Total (DH)</th>
      </tr>

      <?php foreach ($ventesRecentes as $v): ?>
        <tr>
          <td><?= $v['date_vente'] ?></td>
          <td><?= $v['client'] ?></td>
          <td><?= $v['produit'] ?></td>
          <td><?= number_format($v['total'], 2) ?></td>
        </tr>
      <?php endforeach; ?>

    </table>
  </div>
  <style>
    /* ===== DASHBOARD GLOBAL ===== */
    .home-content {
      padding: 20px;
      background: #f5f7fb;
      min-height: 100vh;
      
    }

    /* ===== KPI ===== */
    .overview-boxes {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
      gap: 20px;
      margin-bottom: 30px;
    }

    .overview-boxes .box {
      background: linear-gradient(135deg, #0a2558, #1e3a8a);
      color: #fff;
      padding: 22px;
      border-radius: 16px;
      position: relative;
      overflow: hidden;
      transition: transform .3s ease, box-shadow .3s ease;
    }

    .overview-boxes .box:hover {
      transform: translateY(-6px);
      box-shadow: 0 15px 30px rgba(0, 0, 0, .15);
    }

    .overview-boxes .box-topic {
      font-size: 14px;
      opacity: .85;
    }

    .overview-boxes .number {
      font-size: 30px;
      font-weight: bold;
      margin-top: 8px;
    }

    .overview-boxes i {
      position: absolute;
      right: 18px;
      bottom: 18px;
      font-size: 42px;
      opacity: .25;
    }

    /* ===== BOX GENERIQUE ===== */
    .box {
      background: #fff;
      padding: 20px;
      border-radius: 16px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, .06);
      margin-bottom: 25px;
    }

    /* ===== COMPARAISON ===== */
    .indicator {
      display: flex;
      align-items: center;
      gap: 8px;
      margin-top: 8px;
      font-weight: 500;
    }

    .indicator i {
      font-size: 22px;
      color: #22c55e;
    }

    .indicator .down {
      color: #ef4444;
    }

    /* ===== GRAPHES ===== */
    .sales-boxes {
      display: grid;
      grid-template-columns: 2fr 1fr;
      gap: 20px;
      margin-bottom: 30px;
    }

    .sales-boxes h3 {
      margin-bottom: 15px;
    }

    /* ===== TABLE ===== */
    .recent-sales table {
      width: 100%;
      border-collapse: collapse;
    }

    .recent-sales th {
      text-align: left;
      padding: 12px;
      background: #f1f5f9;
      font-size: 14px;
    }

    .recent-sales td {
      padding: 12px;
      border-bottom: 1px solid #eee;
      font-size: 14px;
    }

    .recent-sales tr:hover {
      background: #f9fafb;
    }

    /* ===== RESPONSIVE ===== */
    @media(max-width: 900px) {
      .sales-boxes {
        grid-template-columns: 1fr;
      }
      .home-content {
      margin-left: 260px;
      }
    }
  </style>

</div>
<script>
  new Chart(document.getElementById('venteChart'), {
    type: 'line',
    data: {
      labels: <?= json_encode($labels) ?>,
      datasets: [{
        label: 'Ventes (DH)',
        data: <?= json_encode($data) ?>,
        borderWidth: 2,
        fill: true
      }]
    },
    options: {
      responsive: true
    }
  });

  new Chart(document.getElementById('produitChart'), {
    type: 'bar',
    data: {
      labels: <?= json_encode($pLabels) ?>,
      datasets: [{
        label: 'Quantité vendue',
        data: <?= json_encode($pData) ?>
      }]
    }
  });
</script>

<?php include 'pied.php'; ?>