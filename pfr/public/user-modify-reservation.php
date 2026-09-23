<?php

session_start();
$pdo = require_once('../includes/bdd.php');

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
} else {
    if ($_SERVER['REQUEST_METHOD'] === "GET") {
        $id = $_GET['id'] ? (int)$_GET['id'] : null;
    } else {
        $id = $_POST['id'] ? (int)$_POST['id'] : null;
    }
}

if ($_SESSION['profil'] !== 'abonne') {
    header('Location: index.php');
    exit;
}

$error = null;

$query = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$query->execute([$id]);
$currentOrder = $query->fetch(PDO::FETCH_ASSOC);

$query = $pdo->prepare("SELECT * FROM events WHERE id = ?");
$query->execute([$currentOrder['events_id']]);
$event = $query->fetch(PDO::FETCH_ASSOC);
if (!$event) {
    die("Erreur : Évènement introuvable");
}

$query = $pdo->prepare("SELECT SUM(seats) 
    FROM orders
    WHERE orders.id != ?
    AND events_id = ?
    AND status NOT IN ('Annulé')
    ");
$query->execute([$currentOrder['id'], $currentOrder['events_id']]);
$seats_taken = $query->fetchColumn();



if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_GET['id'])) {
        header('Location: index.php');
        exit;
    }
}

if ($seats_taken === null) {
    $seats_taken = 0;
}
$seats_free = $event['capacity'] - $seats_taken;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $seats = $_POST['seats'];
    $users_id = $_SESSION['id'];

    try {
        $query = $pdo->prepare(
            "UPDATE orders SET 
                seats = :seats  
                WHERE id = :id
                AND users_id = :users_id"
        );
        $query->execute([
            ':seats' => $seats,
            ':id' => $id,
            ':users_id' => $users_id
        ]);

        header('Location: user-reservations.php');
        exit;
    } catch (PDOException $e) {
        $error = "Erreur : " . $e->getMessage();
    }
}
$date = new DateTime($currentOrder['date']);
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
    <link href="https://cdn.boxicons.com/3.0.8/fonts/basic/boxicons.min.css" rel="stylesheet">
    <link href="../assets/images/mon_logo.png" rel="icon" type="image/png">
    <script src="../assets/js/password.js" defer></script>
    <title>MNS FC - Réservation</title>
</head>

<body>
    <?php include_once('../includes/header.php') ?>
    <main class="event-wrap">
        <div class="event">
            <h3> Modifier une réservation</h3>
            <p>Veuillez modifier le nombre de places que vous souhaitez réserver</p>
            <div class="event-item">
                <p>Évènement : <?= htmlspecialchars($event['name']) ?>.</p>
            </div>
            <div class="event-item">
                <p>Date : <?= $date->format('d-m-Y') ?>.</p>
            </div>
            <div class="event-item">
                <p>Statut : <?= htmlspecialchars($currentOrder['status']) ?></p>
            </div>
            <div class="event-item">
                <p>Places disponibles : <?= $seats_free ?>.</p>
            </div>
            <?php
            if ($seats_free >= 1) {
            ?>
                <form action="user-modify-reservation.php" method="POST">
                    <div class="event-item">
                        <label for="seats">Nombre de places :</label>
                        <div>
                            <select name="seats" id="seats">
                                <option value="">-</option>
                                <option value="1">1</option>
                                <?php
                                if ($seats_free >= 2) {
                                ?>
                                    <option value="2">2</option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="id" value="<?= $id ?>">
                    <div class="event-item">
                        <button type="submit" id="sub-btn" value="Valider">Valider</button>
                    </div>
                </form>
            <?php
            } else {
            ?>
                <p class="error">Désolé, plus aucune place disponible !</p>
            <?php
            }
            ?>
            <?php if (!empty($error)): ?>
                <p class="error"><?= $error ?></p>
            <?php endif; ?>
        </div>
    </main>
    <?php require_once('../includes/footer.php') ?>
</body>

</html>