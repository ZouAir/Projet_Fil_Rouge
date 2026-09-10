<?php
session_start();
$pdo = require_once('../includes/bdd.php');

//Si pas connecté
if (!isset($_SESSION['id'])) {
    header('Location: ./login.php');
    exit;
} else {
    //Si connecté
    if ($_SERVER['REQUEST_METHOD'] === "GET") {
        $id = $_GET['id'] ? (int)$_GET['id'] : null;
    } else {
        $id = $_POST['id'] ? (int)$_POST['id'] : null;
    }

    $error = null;

    $query = $pdo->prepare("SELECT * FROM events WHERE id = ?");
    $query->execute([$id]);
    $event = $query->fetch(PDO::FETCH_ASSOC);
    if (!$event) {
        header('Location: user-events.php');
        exit;
    }

    $query = $pdo->prepare("SELECT SUM(seats) 
    FROM orders
    WHERE events_id = ?
    AND status NOT IN ('Annulé')
    ");
    $query->execute([$id]);
    $seats_taken = $query->fetchColumn();

    $query = $pdo->prepare("SELECT SUM(seats) 
    FROM orders
    WHERE events_id = ?
    AND users_id = ?
    AND status NOT IN ('Annulé')
    ");
    $query->execute([$id, $_SESSION['id']]);
    $seats_booked = $query->fetchColumn();

    if ($seats_booked === null) {
        $seats_booked = 0;
    }
    $seats_free = $event['capacity'] - $seats_taken;

    if ($_SERVER['REQUEST_METHOD'] === "POST") {
        $seats = $_POST['seats'] ?? '';
        try {
            if ($seats) {
                if ((int)$seats_booked + $_POST['seats'] > 2) {
                    $error = "Vous avez atteint le nombre maximum de réservations possible";
                }

                if (empty($error)) {
                    $query = $pdo->prepare("INSERT INTO orders (date, status, seats, events_id, users_id) VALUES (?, ?, ?, ?, ?)");
                    $query->execute([date('Y-m-d'), 'En attente', $seats, $id, $_SESSION['id']]);

                    if ($_SESSION['profil'] === 'administrateur' || $_SESSION['profil'] === 'service') {
                        header("Location: staff-reservations.php");
                        exit;
                    } elseif ($_SESSION['profil'] === 'abonne') {
                        header("Location: user-reservations.php");
                        exit;
                    } else {
                        header("Location: index.php");
                        exit;
                    }
                }
            }
        } catch (PDOException $e) {
            $error = "Erreur lors de la réservation, veuillez contacter le service réservation";
        }
    }
}
$date = new DateTime($event['date']);
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
    <!-- <link href="../assets/css/login.css" rel="stylesheet">
    <link href="../assets/css/dashboard.css" rel="stylesheet"> -->
    <link href="https://cdn.boxicons.com/3.0.8/fonts/basic/boxicons.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../assets/images/mon_logo.png">
    <title>MNS FC Réservation</title>
</head>
<!-- Ceci est un commentaire -->

<body>
    <?php include_once('../includes/header.php') ?>
    <main class="event-wrap">
        <div class="event">
            <h3> Réservation évènement</h3>
            <p>-- Veuillez choisir le nombre de places que vous souhaitez réserver --</p>
            <div class="event-item">
                <p>Catégorie :<?php //requete SQL avec jointure pour avoir la catégorie de l'event.
                                ?>.</p>
            </div>
            <div class="event-item">
                <p>Évènement : <?= ucfirst(htmlspecialchars($event['name'])) ?>.</p>
            </div>
            <div class="event-item">
                <p>Description : <?= ucfirst(htmlspecialchars($event['description'])) ?>.</p>
            </div>
            <div class="event-item">
                <p>Date : <?= $date->format('d-m-Y') ?>.</p>
            </div>
            <div class="event-item">
                <p>Tarif : <?= htmlspecialchars($event['price']) . " euro";
                            if ((int)$event['price'] > 1) {
                                echo "s";
                            } ?>.</p>
            </div>
            <div class="event-item">
                <p>Statut : <?= htmlspecialchars($event['status']) ?>.</p>
            </div>
            <div class="event-item">
                <p>Places disponibles : <?= $seats_free ?>.</p>
            </div>
            <?php
            if ($seats_free >= 1 && $seats_booked <= 1) {
            ?>
                <form action="./reservation.php" method="POST">
                    <div class="event-item">
                        <label for="seats">Nombre de places </label>
                        <div>
                            <select name="seats" id="seats">
                                <option value="">-</option>
                                <option value="1">1</option>
                                <?php
                                if ($seats_free >= 2 && $seats_booked === 0) {
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
                        <button type="submit" id="sub-btn" value="Réserver">Réserver</button>
                    </div>
                </form>
            <?php
            } else {
            ?>
                <p class="error">Désolé, vous avez atteint le nombre maximum de réservations possible</p>
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