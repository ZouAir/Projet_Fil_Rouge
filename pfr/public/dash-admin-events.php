<?php
session_start();
$pdo = require_once('../includes/bdd.php');

if (!isset($_SESSION['id']) || !in_array($_SESSION['profil'], ['administrateur', 'service'])) {
    header('Location: login.php');
    exit;
}

$id = $_SESSION['id'];
$name = $_SESSION['name'];
$first_name = $_SESSION['first_name'];
$initials = strtoupper(substr($first_name, 0, 1) . '.' . substr($name, 0, 1));
$profil = $_SESSION['profil'];

$query = $pdo->prepare("SELECT * FROM events 
WHERE date > NOW() 
ORDER BY date ASC
LIMIT 10");
$query->execute();
$events = $query->fetchAll();

$query = $pdo->prepare("SELECT * FROM events 
WHERE date > NOW() 
ORDER BY date ASC 
LIMIT 1");
$query->execute();
$event = $query->fetch(PDO::FETCH_ASSOC);

$query = $pdo->prepare("SELECT SUM(seats) 
    FROM orders
    WHERE events_id = ?
    AND status NOT IN ('Annulé')
    ");
$query->execute([$event['id']]);
$seats = $query->fetchColumn();

if ($seats === null) {
    $seats = 0;
}
$seats_free = $event['capacity'] - $seats;

$query = $pdo->prepare("SELECT count(*) FROM users");
$query->execute([]);
$abonnes = $query->fetchColumn();

$date = new DateTime($event['date']);
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
    <title>MNS Football Club - Dashboard</title>
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
                        <?php if ($profil === 'administrateur'): ?>
                            <li><a href="dash-admin-users.php">Utilisateurs</a></li>
                        <?php endif; ?>
                        <li><a href="dash-admin-events.php">Évènements</a></li>
                        <li><a href="dash-admin-reservations.php">Réservations</a></li>
                        <li><a href="#">Présences</a></li>
                    </ul>
                    <div>Compte</div>
                    <a href="account.php">Mon compte</a>
                    <a href="logout.php">Déconnexion</a>
                </div>
                <div class="dash-content">
                    <div class="dash-head">
                        <div>
                            <span><?= htmlspecialchars($initials) ?></span>
                            <span><?= ucfirst($first_name) . " " . strtoupper($name) ?></span>
                        </div>
                        <div>Tableau de bord : <?= ucfirst(htmlspecialchars($profil)) ?></div>
                    </div>
                    <div class="kpi">
                        <div>
                            <span>Prochain évènement</span>
                            <span><?= $date->format('d-m-Y') ?></span>
                            <span><?= htmlspecialchars($event['name']) ?></span>
                        </div>
                        <div>
                            <span>Réservations</span>
                            <span><?= htmlspecialchars($seats) ?> réservations</span>
                            <span>Places libres : <?= $seats_free ?></span>
                        </div>
                        <div>
                            <span>Abonnés</span>
                            <span>Total abonnés: <?= htmlspecialchars($abonnes) ?></span>
                            <!-- <span>Nouveaux abonnés (<?= date('m') ?>):</span> -->
                        </div>
                    </div>
                    <div class="title">
                        Les évènements
                    </div>
                    <?php
                    foreach ($events as $row) {
                        $query = $pdo->prepare("SELECT SUM(seats) 
                        FROM orders
                        WHERE events_id = ?
                        AND status NOT IN ('Annulé')
                    ");
                        $query->execute([$row['id']]);
                        $seats = $query->fetchColumn();
                        if ($seats === null) {
                            $seats = 0;
                        }
                        $free_seats = $row['capacity'] - $seats;
                    ?>
                        <div class="event">
                            <div class="event-status">
                                <span><?= htmlspecialchars($row['status']) ?></span>
                            </div>
                            <div class="event-data">
                                <span><?= htmlspecialchars($row['name']) ?></span>
                                <span><?= htmlspecialchars($row['date']) ?></span>
                                <span><?= htmlspecialchars($row['scene']) . " - Places libres : " . htmlspecialchars($free_seats) . " place";
                                        if ((int)($free_seats) > 1) {
                                            echo "s";
                                        } ?></span>
                            </div>
                            <div class="event-modify">
                                <div class="event-change">
                                    <a href="admin-modify-event.php?id=<?= $row['id'] ?>">Modifier</a>
                                </div>
                                <div class="event-delete">
                                    <a href="admin-delete-event.php?id=<?= $row['id'] ?>">Supprimer</a>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                    <div class="cta">
                        <a href="admin-ajouter-event.php">Ajouter un évènement</a>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php include_once('../includes/footer.php') ?>

</body>

</html>