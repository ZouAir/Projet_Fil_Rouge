<?php
// // Démarrer la session
// // Vérifier que l'utilisateur est connecté
// // Inclure bdd.php
// // Récupérer les réservations du user connecté depuis la table orders

// Structure de base HTML
// Require Header avec nav + bouton déconnexion
// Section "Mes réservations" → boucle foreach sur les orders
// Require Footer

$query2 = $pdo->prepare("SELECT * FROM orders WHERE id_user = ?");
$query2->execute([$_SESSION['user_id']]);
$orders = $query2->fetchALL(PDO::FETCH_ASSOC);

$query3 = $pdo->prepare("SELECT * FROM events INNER JOIN categories ON events.categories_id = categories.id WHERE event_id = ? ");
$query3->execute([$_SESSION['user_id']]);
$title = $query3->fetch(PDO::FETCH_ASSOC);

$seats_taken = $events['capacity']; //tkharbi9a tzerbi9a.
$seats_free = $capacity - $seats_taken;
