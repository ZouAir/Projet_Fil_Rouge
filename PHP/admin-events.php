<?php
//// Session start
////  Vérifier connexion → sinon login.php
////  Vérifier que $_SESSION['profil'] === 'administrateur' → sinon index.php
////  Inclure bdd.php
////  Récupérer tous les events depuis la BDD
//// Afficher dans un tableau HTML avec boutons Modifier/Supprimer
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SESSION['profil'] !== 'administrateur') {
    header('Location: index.php');
    exit;
}

$pdo = require_once('bdd.php');
$query = $pdo->prepare("SELECT * FROM events ORDER BY date ASC");
$query->execute();
$events = $query->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>MNS Football Club</title>
</head>

<body>
    <header><!--ici le header --></header>
    <main>
        <span>Bonjour <?= $_SESSION['email'] ?> !</span>
        <h1>Mon tableau de bord évènements</h1>
        <?php if (isset($_SESSION['error'])): ?>
            <p style="color : red"><?= $_SESSION['error'] ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        <table border="1">
            <caption>
                <p>Les évènements à venir</p>
            </caption>
            <thead>
                <tr>
                    <th scope="col">Nom</th>
                    <th scope="col">Date</th>
                    <th scope="col">Heure</th>
                    <th scope="col">Prix</th>
                    <th scope="col">Description</th>
                    <th scope="col">Capacité</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($events)) : ?>
                    <?php foreach ($events as $event): ?>
                        <tr>
                            <td><?= htmlspecialchars($event['name']) ?></td>
                            <td><?= htmlspecialchars($event['date']) ?></td>
                            <td><?= htmlspecialchars($event['hour']) ?></td>
                            <td><?= htmlspecialchars($event['price']) ?></td>
                            <td><?= htmlspecialchars($event['description']) ?></td>
                            <td><?= htmlspecialchars($event['capacity']) ?></td>
                            <td><?= htmlspecialchars($event['status']) ?></td>
                            <td>
                                <a href="admin-modifier-event.php?id=<?= htmlspecialchars($event['id']) ?>">Modifier</a>
                                <a href="admin-supprimer-event.php?id=<?= htmlspecialchars($event['id']) ?>">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="8">Aucun évènement trouvé !</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
</body>

</html>