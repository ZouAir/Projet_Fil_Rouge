<?php
// // Démarrer la session
// // Vérifier que l'utilisateur est connecté
// // Inclure bdd.php
// // Récupérer les events à venir depuis la table events
// // Récupérer les réservations du user connecté depuis la table orders

//  Structure de base HTML
//  Header avec nav + bouton déconnexion
//  Section "Events à venir" → boucle foreach sur les events
//  Section "Mes réservations" → boucle foreach sur les orders

session_start();
$pdo = require_once('bdd.php');

//Si pas connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
} else {
    //Si déjà connecté
    $query = $pdo->prepare("SELECT * FROM events WHERE date >= now() 
    Order BY date DESC");
    $query->execute([$_SESSION['user_id']]);
    $events = $query->fetchALL(PDO::FETCH_ASSOC);

    $query2 = $pdo->prepare("SELECT * FROM orders WHERE id_user = ?");
    $query2->execute([$_SESSION['user_id']]);
    $orders = $query2->fetchALL(PDO::FETCH_ASSOC);

    $query3 = $pdo->prepare("SELECT * FROM events WHERE id = ? INNER JOIN categories ON events.categories_id = categories.id");
    $query3->execute([$_SESSION['user_id']]);
    $title = $query3->fetch(PDO::FETCH_ASSOC);

    $capacity = null;
    $orders = [];
    $seats_taken = $events['total_seats']; //tkharbi9a tzerbi9a.
    $seats_free = $capacity - $seats_taken;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>MNS Football Club</title>
</head>

<body>
    <h1>Bienvenue <?= $_SESSION['name'] ?> !</h1>
    <!-- Events à venir -->
    <section>
        <div class="card">
            <p><?= $title ?></p>
            <?php
            foreach ($events as $row) {
                echo "name: " . $row['name'] . "<br>";
                echo "description: " . $row['description'] . "<br>";
                echo "date: " . $row['date'] . "<br>";
                echo "price: " . $row['price'] . "<br>";
                echo "capacity: " . $row['capacity'] . "<br>";
                echo "status: " . $row['status'] . "<br>";
            }
            ?>
        </div>
    </section>
    <!-- Mes réservations -->
    <section>
        <div class="card">
            <p><?= $title ?></p>
            <?php
            foreach ($orders as $row) {
                echo "Event: " . $row['events_id'] . "<br>";
                echo "Date: " . $row['date'] . "<br>";
                echo "Status: " . $row['status'] . "<br>";
                echo "Number of seats: " . $row['date'] . "<br>";
            }
            ?>
        </div>
    </section>

</body>

</html>