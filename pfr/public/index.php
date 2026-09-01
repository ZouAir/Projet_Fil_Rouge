<?php
session_start();
$pdo = require_once('../includes/bdd.php');

$name = isset($_SESSION['name']) ? $_SESSION['name'] : '';
$first_name = isset($_SESSION['first_name']) ? $_SESSION['first_name'] : 'Invité';

$query = $pdo->prepare("SELECT e.id AS id, e.date, e.name AS evenement, e.description, e.image, e.capacity, e.price, e.status, c.name AS categorie 
FROM events e  
INNER JOIN categories c ON e.categories_id = c.id   
WHERE date >= now()   
ORDER BY date ASC");
$query->execute();
$events = $query->fetchALL(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../assets/css/header.css" rel="stylesheet">
    <link href="../assets/css/footer.css" rel="stylesheet">
    <link href="../assets/css/variables.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
    <!-- <link href="../assets/css/login.css" rel="stylesheet"> -->
    <!-- <link href="../assets/css/dashboard.css" rel="stylesheet"> -->
    <link href="https://cdn.boxicons.com/3.0.8/fonts/basic/boxicons.min.css" rel="stylesheet">
    <title>MNS Football Club</title>
</head>

<body>
    <?php include_once('../includes/header.php') ?>
    <main>
        <div class="container">
            <section class="hero">
                <img src="../assets/images/stade.jpg" alt="">
                <p>Bienvenue <strong><?= strtoupper(substr($first_name, 0, 1)) . substr($first_name, 1,) . " " . strtoupper($name) ?></strong> au MNS Football Club <br>
                    Réservez vos places en quelques clics pour les évènements sportifs et sociaux de votre club.
                </p>
                <?php
                if (!isset($_SESSION['email'])) { ?>
                    <a href="inscription.php">Nous rejoindre</a>
                    <?php } else {
                    if ($_SESSION['profil'] === 'administrateur' || $_SESSION['profil'] === 'service') { ?>
                        <a href="staff-events.php">mon dashboard</a>
                    <?php } elseif ($_SESSION['profil'] === 'abonne') { ?>
                        <a href="user-events.php">mon dashboard</a>
                    <?php } else {
                        header("Location: login.php");
                        exit;
                    }
                    ?>
                <?php } ?>
            </section>
            <section class="hero2">
                <h3 class="title">Évènements à venir</h3>
                <span>Réservez vos places avant qu'il n'y en ait plus.</span>
                <ul>
                    <?php
                    foreach ($events as $event) {
                        $query = $pdo->prepare("SELECT SUM(seats) FROM orders WHERE events_id = ? AND status NOT IN ('annulé')");
                        $query->execute([$event['id']]);
                        $seats_taken = $query->fetchColumn();
                        if ($seats_taken === null) {
                            $seats_taken = 0;
                        }
                        $seats_free = $event['capacity'] - $seats_taken;
                        $date = new DateTime($event['date']);
                    ?>
                        <li>
                            <div class="card">
                                <p><?= $date->format('d-m-Y') . " - " . $event['categorie'] ?></p>
                                <img src="<?= $event['image'] ?>" alt="">
                                <p><?= htmlspecialchars($event['evenement']) ?></p>
                                <p><?= htmlspecialchars(substr($event['description'], 0, 50) . "...") ?></p>
                                <p><?= $seats_free ?> places disponibles - <?= $event['price'] ?> euros</p>
                                <a href="reservation.php?id=<?= $event['id'] ?>">Réserver</a>
                                <p><?= $event['status'] ?></p>
                            </div>
                        </li>
                    <?php } ?>
                </ul>
            </section>
        </div>
        <section class="hero3">
            <div class="spon-left">
                <h4>nos partenaires</h4>
            </div>
            <div class="spon-right">
                <span>sponsor A</span>
                <span>sponsor B</span>
                <span>sponsor C</span>
            </div>
        </section>
    </main>
    <?php include_once('../includes/footer.php') ?>

</body>

</html>