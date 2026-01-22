<?php
include 'entete.php';
require_once __DIR__ . '/../models/Client.php';
// 🔹 AJOUT CLIENT
if (isset($_POST['add'])) {
    Client::create($_POST);
    header("Location: clients.php");
    exit;
}

// 🔹 MODIFICATION CLIENT
if (isset($_POST['update'])) {
    Client::update($_POST['id'], $_POST);
    header("Location: clients.php");
    exit;
}

// 🔹 SUPPRESSION CLIENT
if (isset($_GET['delete'])) {
    Client::delete($_GET['delete']);
    header("Location: clients.php");
    exit;
}

// 🔹 RÉCUPÉRER CLIENT POUR EDIT
$clientEdit = null;
if (isset($_GET['edit'])) {
    $clientEdit = Client::find($_GET['edit']);
}

// 🔹 LISTE DES CLIENTS
$clients = Client::getAll();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Gestion des clients</title>
</head>

<body>
    <style>
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 260px;
            height: 100vh;
            background: #1e40af;
            transition: all 0.4s ease;
        }

        /* SIDEBAR FERMÉ */
        .sidebar.active {
            left: -260px;
            /* ⬅️ disparaît complètement */
        }

        .page-container {
            padding: 25px;
        }

        h2 {
            margin-bottom: 15px;
            color: #1e293b;
        }

        /* CARD */
        .card {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }

        /* FORM */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-size: 13px;
            color: #475569;
            margin-bottom: 5px;
        }

        .form-group input {
            padding: 10px 12px;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            font-size: 14px;
        }

        .form-group input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, .15);
        }

        .form-actions {
            margin-top: 20px;
            text-align: right;
        }

        .btn {
            padding: 10px 18px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
        }

        .btn-primary {
            background: #2563eb;
            color: #fff;
        }

        .btn-primary:hover {
            background: #1e40af;
        }

        /* TABLE */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        thead {
            background: #f1f5f9;
        }

        th,
        td {
            padding: 12px 10px;
            font-size: 14px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        th {
            color: #1e293b;
        }

        tbody tr:hover {
            background: #f8fafc;
        }

        .actions a {
            margin-right: 8px;
            text-decoration: none;
            font-size: 16px;
        }

        .actions a.edit {
            color: #2563eb;
        }

        .actions a.delete {
            color: #dc2626;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
    </head>

    <body>

        <div class="page-container">

            <!-- FORM CARD -->
            <div class="card">
                <h2><?= $clientEdit ? "Modifier le client" : "Ajouter un client" ?></h2>

                <form method="post">
                    <?php if ($clientEdit): ?>
                        <input type="hidden" name="id" value="<?= $clientEdit['id'] ?>">
                    <?php endif; ?>

                    <div class="form-grid">
                        <div class="form-group">
                            <label>Nom</label>
                            <input type="text" name="nom" value="<?= $clientEdit['nom'] ?? '' ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Prénom</label>
                            <input type="text" name="prenom" value="<?= $clientEdit['prenom'] ?? '' ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Téléphone</label>
                            <input type="text" name="telephone" value="<?= $clientEdit['telephone'] ?? '' ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Adresse</label>
                            <input type="text" name="adresse" value="<?= $clientEdit['adresse'] ?? '' ?>" required>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button class="btn btn-primary" type="submit"
                            name="<?= $clientEdit ? 'update' : 'add' ?>">
                            <?= $clientEdit ? 'Modifier' : 'Ajouter' ?>
                        </button>
                    </div>
                </form>
            </div>

            <!-- TABLE CARD -->
            <div class="card">
                <h2>Liste des clients</h2>

                <table>
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Téléphone</th>
                            <th>Adresse</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clients as $c): ?>
                            <tr>
                                <td><?= htmlspecialchars($c['nom']) ?></td>
                                <td><?= htmlspecialchars($c['prenom']) ?></td>
                                <td><?= htmlspecialchars($c['telephone']) ?></td>
                                <td><?= htmlspecialchars($c['adresse']) ?></td>
                                <td class="actions">
                                    <a class="edit" href="clients.php?edit=<?= $c['id'] ?>">✏️</a>
                                    <a class="delete" href="clients.php?delete=<?= $c['id'] ?>"
                                        onclick="return confirm('Supprimer ce client ?')">🗑️</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php include 'pied.php'; ?>
        </div>

    </body>

</html>