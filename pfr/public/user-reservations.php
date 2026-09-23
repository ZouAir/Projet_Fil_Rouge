<?php
session_start();
$pdo = require_once('../includes/bdd.php');

$id = $_SESSION['id'];
$name = $_SESSION['name'];
$first_name = $_SESSION['first_name'];
$initials = strtoupper(substr($first_name, 0, 1) . '.' . substr($name, 0, 1));

$query =  $pdo->prepare("SELECT COUNT(*) 
FROM orders o
WHERE o.users_id = ?");
$query->execute([$id]);
$totalOrders = $query->fetchColumn();

$query =  $pdo->prepare("SELECT COUNT(*) 
FROM orders o
INNER JOIN events e ON e.id = o.events_id
WHERE o.users_id = ?
AND e.date > NOW()");
$query->execute([$id]);
$nextOrders = $query->fetchColumn();

$query = $pdo->prepare("SELECT o.id, o.status, o.seats, o.users_id, o.events_id, e.name, e.date, e.scene  
FROM orders o
INNER JOIN events e ON e.id = o.events_id
WHERE e.date > NOW() 
AND o.users_id = ?
ORDER BY date");
$query->execute([$id]);
$orders = $query->fetchAll(PDO::FETCH_ASSOC);
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
    <link rel="icon" type="image/png" href="../assets/images/mon_logo.png">
    <script src="../assets/js/password.js" defer></script>
    <script src="../assets/js/script.js" defer></script>
    <title>MNS FC - Dashboard</title>
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
                        <li><a href="user-events.php">Évènements</a></li>
                        <li><a href="user-reservations.php">Réservations</a></li>
                        <li><a href="#">Mes amis</a></li>
                        <li><a href="#">Mon mvp</a></li>
                    </ul>
                    <div>Compte</div>
                    <a href="account.php">Mon compte</a>
                    <a href="logout.php">Déconnexion</a>
                </div>
                <div class="dash-content">
                    <div class="dash-head">
                        <div>
                            <span><?= $initials ?></span>
                            <span><?= ucfirst($first_name) . " " . strtoupper($name) ?></span>
                        </div>
                        <div>Mon tableau de bord</div>
                    </div>
                    <div class="kpi">
                        <div>
                            <span>Réservations</span>
                            <span><?= $totalOrders ?></span>
                        </div>
                        <div>
                            <span>Réservations à venir</span>
                            <span><?= $nextOrders ?></span>
                        </div>
                        <div>
                            <span>Amis</span>
                            <span>81</span>
                        </div>
                    </div>
                    <div class="title">
                        Mes réservations
                    </div>
                    <?php
                    foreach ($orders as $order) {
                        $date = new DateTime($order['date']);
                    ?>
                        <div class="event">
                            <div class="event-status">
                                <span><?= htmlspecialchars($order['status']) ?></span>
                            </div>
                            <div class="event-data">
                                <span><?= ucfirst(htmlspecialchars($order['name'])) ?></span>
                                <span><?= htmlspecialchars($date->format('d-m-Y')) ?></span>
                                <span><?= htmlspecialchars($order['scene']) . " - " . htmlspecialchars($order['seats']) . " place";
                                        if ((int)($order['seats']) > 1) {
                                            echo "s";
                                        } ?></span>
                            </div>
                            <div class="event-modify">
                                <div class="event-change">
                                    <a href="user-modify-reservation.php?id=<?= $order['id'] ?>">Modifier</a>
                                </div>
                                <div class=" event-delete">
                                    <a href="user-delete-reservation.php?id=<?= $order['id'] ?>">Supprimer</a>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </main>
    <?php include_once('../includes/footer.php') ?>
</body>

</html>