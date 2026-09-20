<?php
session_start();
$pdo = require_once('../includes/bdd.php');

if (isset($_SESSION['success'])) {
    $modal_message = $_SESSION['success'] ?? '';
    unset($_SESSION['success']);
    $modal_icon = "<i class='bx bxs-party'></i>";
} elseif (isset($_SESSION['error'])) {
    $modal_message = $_SESSION['error'] ?? '';
    unset($_SESSION['error']);
    $modal_icon = "<i class='bx bxs-x-circle'></i>";
} else {
    $modal_message = null;
    $modal_icon = null;
}

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
LIMIT 1");
$query->execute();
$event = $query->fetch(PDO::FETCH_ASSOC);

$query = $pdo->prepare("SELECT o.id, o.status, o.seats, o.users_id, o.events_id, e.name AS event_name, e.date AS event_date, u.name AS user_name, u.first_name   
FROM orders o
INNER JOIN events e ON e.id = o.events_id
INNER JOIN users u ON u.id = o.users_id 
WHERE e.date > NOW()
ORDER BY e.date ASC, u.name, u.first_name
");
$query->execute();
$orders = $query->fetchAll(PDO::FETCH_ASSOC);

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
$date = new DateTime($event['date']);

$query = $pdo->prepare("SELECT count(*) FROM users");
$query->execute([]);
$abonnes = $query->fetchColumn();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../assets/css/variables.css" rel="stylesheet">
    <link href="../assets/css/header.css" rel="stylesheet">
    <link href="../assets/css/footer.css" rel="stylesheet">
    <link href="../assets/css/modal.css" rel="stylesheet">
    <!-- <link href="../assets/css/style.css" rel="stylesheet"> -->
    <link href="../assets/css/dashboard.css" rel="stylesheet">
    <link href="https://cdn.boxicons.com/3.0.8/fonts/basic/boxicons.min.css" rel="stylesheet">
    <link href="../assets/images/mon_logo.png" rel="icon" type="image/png">
    <script src="../assets/js/script.js" defer></script>
    <script src="../assets/js/password.js" defer></script>
    <script src="../assets/js/modal.js" defer></script>
    <title>MNS Football Club - Réservations</title>
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
                            <li><a href="admin-users.php">Utilisateurs</a></li>
                        <?php endif; ?>
                        <li><a href="staff-events.php">Évènements</a></li>
                        <li><a href="staff-reservations.php">Réservations</a></li>
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
                        Les réservations
                    </div>
                    <?php
                    foreach ($orders as $order) {
                        $date2 = new DateTime($order['event_date']);
                    ?>
                        <div class="event">
                            <div class="event-status">
                                <span><?= htmlspecialchars($order['status']) ?></span>
                            </div>
                            <div class="event-data">
                                <span><?= ucfirst(htmlspecialchars($order['first_name'])) . " " . strtoupper(htmlspecialchars($order['user_name'])) ?></span>
                                <span><?= $date2->format('d-m-Y') ?></span>
                                <span><?= ucwords(htmlspecialchars($order['event_name'])) . " - " . htmlspecialchars($order['seats']) . " place";
                                        if ((int)($order['seats']) > 1) {
                                            echo "s";
                                        } ?></span>
                            </div>
                            <div class="event-modify">
                                <div class="event-change">
                                    <a href="staff-modify-reservation.php?id=<?= $order['id'] ?>">Modifier</a>
                                </div>
                                <div class="event-delete">
                                    <form action="staff-delete-reservation.php" method="POST" class="form-delete" novalidate>
                                        <input type="hidden" name="id" value="<?= $order['id'] ?>">
                                        <button>Supprimer</button>
                                    </form>
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
    <?php include_once('../includes/modal.php') ?>
    <script>
        let modalMessage = <?= json_encode($modal_message) ?>;
        let modalIcon = <?= json_encode($modal_icon) ?>;
    </script>
</body>

</html>