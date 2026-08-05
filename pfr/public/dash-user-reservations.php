<?php
session_start();
$pdo = require_once('../includes/bdd.php');

$id = $_SESSION['id'];
$name = $_SESSION['name'];
$first_name = $_SESSION['first_name'];
$initials = strtoupper(substr($first_name, 0, 1) . ' ' . substr($name, 0, 1));

$query = $pdo->prepare("SELECT * FROM orders WHERE date > NOW() LIMIT 10");
$query->execute();
$orders = $query->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../assets/css/variables.css" rel="stylesheet">
    <link href="../assets/css/header.css" rel="stylesheet">
    <link href="../assets/css/footer.css" rel="stylesheet">
    <!-- <link href="../assets/css/style.css" rel="stylesheet"> -->
    <link href="../assets/css/dashboard.css" rel="stylesheet">
    <link href="https://cdn.boxicons.com/3.0.8/fonts/basic/boxicons.min.css" rel="stylesheet">
    <script src="../assets/js/script.js" defer></script>
    <title>MNS Football Club</title>
</head>

<body>
    <?php include_once('../includes/header.php') ?>
    <main>
        <div class="container">
            <div class="dashboard">
                <div class="dash-sidebar">
                    <div>Navigation</div>
                    <ul>
                        <li><a href="index.php">Accueil</a></li>
                        <li><a href="dash-user-evenements.php">Évènements</a></li>
                        <li><a href="dash-user-reservations.php">Réservations</a></li>
                        <li><a href="dash-user-amis.php">Mes amis</a></li>
                        <li><a href="dash-user-mvp.php">Mon mvp</a></li>
                    </ul>
                    <div>Compte</div>
                    <a href="dash-user-compte.php">Mon compte</a>
                    <a href="logout.php">Déconnexion</a>
                </div>
                <div class="dash-content">
                    <div class="dash-head">
                        <div>
                            <span><?= $initials ?></span>
                            <span><?= strtoupper(substr($first_name, 0, 1)) . substr($first_name, 1,) . " " . strtoupper($name) ?></span>
                        </div>
                        <div>Mon tableau de bord</div>
                    </div>
                    <div class="kpi">
                        <div>
                            <span>Réservations</span>
                            <span>10</span>
                        </div>
                        <div>
                            <span>Réservations à venir</span>
                            <span>9</span>
                        </div>
                        <div>
                            <span>Amis</span>
                            <span>81</span>
                        </div>
                    </div>
                    <div class="title">
                        Mes réservations
                    </div>

                    <div class="event">
                        <div class="event-status">
                            <span>confirmé</span>
                        </div>
                        <div class="event-data">
                            <span>Edward Norton</span>
                            <span>05 juin 2026</span>
                            <span>Tribune Ouest - 2 places</span>
                        </div>
                        <div class="event-modify">
                            <div class="event-change">
                                <a href="events.php">Modifier</a>
                            </div>
                            <div class="event-delete">
                                <a href="events.php">Supprimer</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>
    <?php include_once('../includes/footer.php') ?>
</body>

</html>