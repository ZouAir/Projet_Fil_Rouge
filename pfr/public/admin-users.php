<?php
session_start();
$pdo = require_once('../includes/bdd.php');

if ($_SESSION['profil'] !== 'administrateur') {
    header('Location: index.php');
    exit;
}

$id = $_SESSION['id'];
$name = $_SESSION['name'];
$first_name = $_SESSION['first_name'];
$initials = strtoupper(substr($first_name, 0, 1) . '.' . substr($name, 0, 1));
$profil = $_SESSION['profil'];

$query = $pdo->prepare("SELECT * FROM events WHERE date > NOW() LIMIT 10");
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

$query = $pdo->prepare("SELECT * FROM users ORDER BY name ASC, first_name ASC");
$query->execute();
$abonnes = $query->fetchAll();


$query = $pdo->prepare("SELECT count(*) FROM users");
$query->execute();
$nbre_abonnes = $query->fetchColumn();

if ($seats === null) {
    $seats = 0;
}

$seats_free = $event['capacity'] - $seats;
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
    <title>MNS FC - Abonnés</title>
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
                        <li><a href="#">Messages</a></li>
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
                        <div>Tableau de bord : <?= htmlspecialchars($profil) ?></div>
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
                            <span>Total abonnés: <?= htmlspecialchars($nbre_abonnes) ?></span>
                            <!-- <span>Nouveaux abonnés (<?= date('m') ?>):</span> -->
                        </div>
                    </div>
                    <div class="title">
                        Les abonnés
                    </div>
                    <?php
                    foreach ($abonnes as $abonne) {
                        $birthday = new DateTime($abonne['birthday']);
                        if ($abonne['is_actif'] == 1) {
                            $actif = "Actif";
                        } else {
                            $actif = "Inactif";
                        }
                    ?>
                        <div class="user">
                            <div class="user-data">
                                <span><?= ucfirst(htmlspecialchars($abonne['first_name'])) . " " . strtoupper(htmlspecialchars($abonne['name'])) ?></span>
                                <span><?= htmlspecialchars($abonne['email']) . " - " . htmlspecialchars($abonne['phone']) ?></span>
                                <span><?= $birthday->format('d-m-Y') . " - " . htmlspecialchars($abonne['profil']) . " - " . htmlspecialchars($actif) ?></span>
                            </div>
                            <div class="user-modify">
                                <div class="user-change">
                                    <a href="admin-modify-user.php?id=<?= $abonne['id'] ?>">Modifier</a>
                                </div>
                                <div class="user-delete">
                                    <a href="admin-delete-user.php?id=<?= $abonne['id'] ?>">Supprimer</a>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                    <div class="cta">
                        <a href="admin-add-user.php">Ajouter un abonné</a>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php include_once('../includes/footer.php') ?>
</body>

</html>