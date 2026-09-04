<?php
session_start();
$pdo = require_once('../includes/bdd.php');

$name = isset($_SESSION['name']) ? $_SESSION['name'] : '';
$first_name = isset($_SESSION['first_name']) ? $_SESSION['first_name'] : 'Invité';

$query = $pdo->prepare("SELECT e.id AS id, e.date, e.name AS evenement, e.description, e.image, e.capacity, e.price, e.status, c.name AS categorie 
FROM events e  
INNER JOIN categories c ON e.categories_id = c.id      
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
    <link rel="icon" type="image/png" href="../assets/images/mon_logo.png">
    <title>MNS Football Club</title>
</head>

<body>
    <?php include_once('../includes/header.php') ?>
    <main>
        <div class="container">
            <section class="hero">
                <p>Bienvenue <strong><?= strtoupper(substr($first_name, 0, 1)) . substr($first_name, 1,) . " " . strtoupper($name) ?></strong> au MNS Football Club <br>
                    Réservez vos places avant qu'il n'y en ait plus.</p>
            </section>
            <section class="hero2">
                <h3 class="title">Tous les évènements de l'année</h3>
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
                        $today = new DateTime();
                    ?>
                        <li>
                            <div class="card">
                                <p><?= $date->format('d-m-Y') . " - " . $event['categorie'] ?></p>
                                <img src="<?= $event['image'] ?>" alt="">
                                <p><?= ucfirst(htmlspecialchars($event['evenement'])) ?></p>
                                <p><?= ucfirst(htmlspecialchars(substr($event['description'], 0, 50) . "...")) ?></p>
                                <p><?= $seats_free . " place";
                                    if ((int)$seats_free > 1) {
                                        echo "s";
                                    } ?>
                                    <?= "disponible";
                                    if ((int)$seats_free > 1) {
                                        echo "s";
                                    } ?>
                                    <?= " - " . $event['price'] . " euro";
                                    if ((int)$event['price'] > 1) {
                                        echo "s";
                                    } ?></p>
                                <?php if ($today < $date) { ?>
                                    <a href="reservation.php?id=<?= $event['id'] ?>">Réserver</a>
                                <?php } else { ?>
                                    <a href="">X</a>
                                <?php } ?>
                                <?php if ($today < $date) { ?>
                                    <p><?= $event['status'] ?></p>
                                <?php } else { ?>
                                    <p><?= "Évènement passé" ?></p>
                                <?php } ?>
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