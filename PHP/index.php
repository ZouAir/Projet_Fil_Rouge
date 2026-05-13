<?php
// // Démarrer la session
// // Vérifier que l'utilisateur est connecté
// // Inclure bdd.php
// // Récupérer les events à venir depuis la table events
// // Récupérer les réservations du user connecté depuis la table orders
// // Structure de base HTML
//  Require Header avec nav + bouton déconnexion
// // Section "Events à venir" → boucle foreach sur les events
// Require Footer

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
    $query->execute();
    $events = $query->fetchALL(PDO::FETCH_ASSOC);
    // $seats_taken = /*Logique métier : requette SQL total places reservées*/ ;
    // $category = /*Logique métier : requette SQL nom de catégorie de event*/;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>MNS Football Club</title>
</head>

<body>
    <header><!--ici le header --></header>
    <span>Bonjour <?= $_SESSION['email'] ?> !</span>
    <h1>Bienvenue au MNS Football Club</h1>
    <p>Réservez vos places pour les événements sportifs et sociaux de votre club en quelques clics</p>
    <div class="cta">
        <button>Voir les événements</button>
        <button>Nous rejoindre</button>
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
                    <button>Réserver</button>
                </div>
            </div>
        <?php } ?>
    </section>
    <!-- Mes réservations -->
    <section>
        <!--ici le footer -->
    </section>
    <footer><!--ici le footer --></footer>

</body>

</html>