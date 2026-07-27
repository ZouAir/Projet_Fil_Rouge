<?php
session_start();
$pdo = require_once('./includes/bdd.php');

$id = $_SESSION['id'];
$name = $_SESSION['name'];
$firstname = $_SESSION['firstname'];
$initials = strtoupper(substr($firstname, 0, 1) . ' ' . substr($name, 0, 1));

$query = $pdo->prepare("SELECT * FROM events WHERE date > NOW() LIMIT 5");
$query->execute();
$events = $query->fetchAll();

?>



<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/variables.css">
    <link rel="stylesheet" href="assets/css/login.css">
    <link href="https://cdn.boxicons.com/3.0.8/fonts/basic/boxicons.min.css" rel="stylesheet">
    <script src="assets/js/script.js" defer></script>
    <title>Login</title>
</head>

<body>
    <?php include_once('../includes/header.php') ?>
    <main>
        <section>
            <h2>Mon tableau de bord</h2>
            <div>
                <span><?= $initials ?></span>
                <p><?= $firstname . " " . $name ?></p>
            </div>
        </section>
        <section class="kpi">
            <div class="kpi-card">
                <span>Réservations</span>
                <p> le nombre de réservations</p>
                <p>à venir</p>
            </div>
        </section>
        <section>
            <h4>Mes prochains évènements</h4>
            <?php
            foreach ($events as $event) {
            ?>
                <div class="card">
                    <h3><?= htmlspecialchars($event['name']); ?></h3>
                    <p><?= htmlspecialchars($event['date']); ?></p>
                </div>
            <?php
            }
            ?>
        </section>
    </main>
    <?php include_once('../includes/footer.php') ?>

</body>

</html>