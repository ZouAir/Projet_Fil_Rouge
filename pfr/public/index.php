<?php
session_start();
$pdo = require_once('../includes/bdd.php');
// Démarrer la session
// Inclure bdd.php

// Récupérer les réservations du user connecté depuis la table orders
// Structure de base HTML
// Require Header avec nav + bouton déconnexion
// Section "Events à venir" → boucle foreach sur les events
// Require Footer
// Vérifier si connecté
$email = isset($_SESSION['email']) ? $_SESSION['email'] : 'Invité';

// Récupérer les events à venir depuis la table events
$query = $pdo->prepare("SELECT * FROM events WHERE date >= now() 
Order BY date DESC");
$query->execute();
$events = $query->fetchALL(PDO::FETCH_ASSOC);
// $seats_taken = /*Logique métier : requette SQL total places reservées*/ ;
// $category = /*Logique métier : requette SQL nom de catégorie de event*/;

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/variables.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <!-- <link href="https://cdn.boxicons.com/3.0.8/fonts/basic/boxicons.min.css" rel="stylesheet"> -->
    <script src="assets/js/script.js" defer></script>
    <title>Login</title>
</head>

<body>
    <header>
        <?php include_once('../includes/header.php') ?>
    </header>
    <span>Bonjour <?= isset($_SESSION['email']) ? $_SESSION['email'] : 'Invité' ?> !</span>
    <h1>Bienvenue au MNS Football Club</h1>
    <p>Réservez vos places pour les événements sportifs et sociaux de votre club en quelques clics</p>
    <div class="cta">
        <button><a href="">Voir les événements</a></button>
        <button><a href="">Nous rejoindre</a></button>
        <button><a href="logout.php">Déconnexion</a></button>
    </div>
    <!-- Events à venir -->
    <section>
        <?php
        foreach ($events as $row) { ?>
            <div class="card">
                <div class="card-head">
                    <!--<p>echo la categorie de l'event</p> -->
                    <p><?= $row['date'] ?></p>
                </div>
                <div class="card-body">
                    <p><?= htmlspecialchars($row['name']) ?></p>
                    <p><?= htmlspecialchars(substr($row['description'], 0, 50)) ?></p>
                    <p><?= $row['capacity'] ?> places disponibles</p>
                    <p><?= $row['status'] ?></p>
                </div>
                <div>
                    <a href="reservation.php?id=<?= $row['id'] ?>">
                        <button>Réserver</button>
                    </a>
                </div>
            </div>
        <?php } ?>
    </section>
    <!-- Mes réservations -->
    <footer>
        <?php include_once('../includes/footer.php') ?>
    </footer>

</body>

</html>