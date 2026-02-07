<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">

<head>
    <meta charset="UTF-8" />
    <title>GStock</title>
    <link rel="stylesheet" href="../public/css/style.css" />
    <!-- Boxicons CDN Link -->
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<style>
    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        width: 260px;
        height: 100vh;
        background: linear-gradient(180deg, #0a2558, #071b3d);
        transition: all 0.4s ease;
    }

    /* SIDEBAR FERMÉ */
    .sidebar.active {
        left: -260px;
        /* ⬅️ disparaît complètement */
    }

    /* ===============================
   RESPONSIVE SIDEBAR FIX
================================ */

    /* Home section */
    .home-section {
        position: relative;
        min-height: 100vh;
        width: calc(100% - 260px);
        left: 260px;
        transition: all 0.4s ease;
    }

    /* Sidebar fermée */
    .sidebar.active~.home-section {
        width: 100%;
        left: 0;
    }

    /* MOBILE */
    @media (max-width: 768px) {

        .sidebar {
            left: -260px;
            /* cachée par défaut */
            z-index: 1000;
        }

        .sidebar.active {
            left: 0;
        }

        .home-section {
            width: 100%;
            left: 0;
        }

        nav {
            width: 100%;
            left: 0;
        }

        .search-box {
            display: none;
            /* optionnel */
        }
    }
</style>

<body>
    <div class="sidebar">
        <div class="logo-details">
            <i class="bx bx-package"></i>
            <span class="logo_name">GStock</span>
        </div>
        <ul class="nav-links">
            <li>
                <a href="dashboard.php" class="<?= ($currentPage == 'dashboard.php') ? 'active' : '' ?>">
                    <i class="bx bx-grid-alt"></i>
                    <span class="links_name">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="client.php" class="<?= ($currentPage == 'client.php') ? 'active' : '' ?>">
                    <i class="bx bx-user"></i>
                    <span class="links_name">Client</span>
                </a>
            </li>
            <li>
                <a href="vente.php" class="<?= ($currentPage == 'vente.php') ? 'active' : '' ?>">
                    <i class='bx  bx-shopping-bag'></i>
                    <span class="links_name">Vente</span>
                </a>
            </li>
            <li>
                <a href="article.php" class="<?= ($currentPage == 'article.php') ? 'active' : '' ?>">
                    <i class="bx bx-box"></i>
                    <span class="links_name">Article</span>
                </a>
            </li>
            <li>
                <a href="commande.php" class="<?= ($currentPage == 'commande.php') ? 'active' : '' ?>">
                    <i class="bx bx-list-ul"></i>
                    <span class="links_name">Commandes</span>
                </a>
            </li>
            <li>
                <a href="analyse.php" class="<?= ($currentPage == 'analyse.php') ? 'active' : '' ?>">
                    <i class="bx bx-pie-chart-alt-2"></i>
                    <span class="links_name">Analyses</span>
                </a>
            </li>
            <li>
                <a href="stock.php" class="<?= ($currentPage == 'stock.php') ? 'active' : '' ?>">
                    <i class="bx bx-coin-stack"></i>
                    <span class="links_name">Stock</span>
                </a>
            </li>
            <li>
                <a href="fournisseur.php" class="<?= ($currentPage == 'fournisseur.php') ? 'active' : '' ?>">
                    <i class='bx  bx-user-check'></i>
                    <span class="links_name">Fournisseur</span>
                </a>
            </li>
            <!-- <li>
          <a href="#">
            <i class="bx bx-message" ></i>
            <span class="links_name">Messages</span>
          </a>
        </li>
        <li>
          <a href="#">
            <i class="bx bx-heart" ></i>
            <span class="links_name">Favrorites</span>
          </a>
        </li> -->
            <li class="log_out">
                <a href="../logout.php">
                    <i class="bx bx-log-out"></i>
                    <span class="links_name">Déconnexion</span>
                </a>
            </li>
        </ul>
    </div>
    <section class="home-section">
        <nav>
            <div class="sidebar-button">
                <i class="bx bx-menu sidebarBtn"></i>
                <span class="dashboard">
                    <?php
                    switch ($currentPage) {
                        case 'dashboard.php':
                            echo 'Dashboard';
                            break;
                        case 'client.php':
                            echo 'Client';
                            break;
                        case 'vente.php':
                            echo 'Vente';
                            break;
                        case 'article.php':
                            echo 'Article';
                            break;
                        case 'commande.php':
                            echo 'Commandes';
                            break;
                        case 'analyse.php':
                            echo 'Analyses';
                            break;
                        case 'stock.php':
                            echo 'Stock';
                            break;
                        case 'fournisseur.php':
                            echo 'Fournisseur';
                            break;
                        default:
                            echo 'Dashboard';
                    }
                    ?>
                </span>
            </div>

            <div class="profile-details">
                <!--<img src="images/profile.jpg" alt="">-->
                <span class="admin_name"><?= $_SESSION['user']['username'] ?? 'Admin' ?></span>
                <i class="bx bx-user-circle"></i>
            </div>
        </nav>