<?php
session_start();
$pdo = require_once('../includes/bdd.php');

if (!isset($_SESSION['id']) || !in_array($_SESSION['profil'], ['administrateur', 'service'])) {
    header('Location: login.php');
    exit;
} else {
    if ($_SERVER['REQUEST_METHOD'] === "GET") {
        $id = $_GET['id'] ? (int)$_GET['id'] : null;
    } else {
        $id = $_POST['id'] ? (int)$_POST['id'] : null;
    }
}

$error = null;

$query = $pdo->prepare("SELECT o.id, o.status, o.seats, o.events_id, o.users_id, u.name, u.first_name, u.phone, u.email 
FROM orders o
INNER JOIN users u ON u.id = o.users_id
WHERE o.id = ?
");
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
    $status = $_POST['status'];
    $users_id = $_SESSION['id'];

    try {
        $query = $pdo->prepare(
            "UPDATE orders SET 
                seats = :seats,
                status = :status
                WHERE id = :id"
        );
        $query->execute([
            ':seats' => $seats,
            ':status' => $status,
            ':id' => $id,
        ]);

        header('Location: staff-reservations.php');
        exit;
    } catch (PDOException $e) {
        $error = "Erreur lors de la mise à jour des données, veuillez réessayer svp.";
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
    <script src="../assets/js/password.js" defer></script>
    <title>MNS Football Club - Réservation</title>
</head>

<body>
    <?php include_once('../includes/header.php') ?>
    <main class="event-wrap">
        <div class="event">
            <h3> Modifier une réservation</h3>
            <p>-- Veuillez modifier les détails de la réservation --</p>
            <div class="event-item">
                <p>Évènement : <?= htmlspecialchars($event['name']) ?>.</p>
            </div>
            <div class="event-item">
                <p>Date : <?= $date->format('d-m-Y') ?>.</p>
            </div>
            <div class="event-item">
                <p>Places disponibles : <?= $seats_free ?>.</p>
            </div>
            <div class="event-item">
                <p>Abonné : <?= htmlspecialchars(ucfirst($currentOrder['first_name']) . " " . strtoupper($currentOrder['name'])) ?>.</p>
            </div>
            <div class="event-item">
                <p>Email : <?= htmlspecialchars($currentOrder['email']) ?></p>
            </div>
            <div class="event-item">
                <p>Téléphone : <?= htmlspecialchars($currentOrder['phone']) ?></p>
            </div>
            <?php
            if ($seats_free >= 1) {
            ?>
                <form action="staff-modify-reservation.php" method="POST">
                    <div class="event-item">
                        <label for="status">Statut</label>
                        <div>
                            <select name="status" id="status">
                                <option value="">-</option>
                                <option value="En attente" <?= htmlspecialchars($currentOrder['status']) === 'En attente' ? 'selected' : '' ?>>En attente</option>
                                <option value="Validée" <?= htmlspecialchars($currentOrder['status']) === 'Validée' ? 'selected' : '' ?>>Validée</option>
                                <option value="Annulée" <?= htmlspecialchars($currentOrder['status']) === 'Annulée' ? 'selected' : '' ?>>Annulée</option>
                            </select>
                        </div>
                    </div>
                    <div class="event-item">
                        <label for="seats">Nombre places :</label>
                        <div>
                            <select name="seats" id="seats">
                                <option value="">-</option>
                                <option value="1" <?= htmlspecialchars($currentOrder['seats']) === '1' ? 'selected' : '' ?>>1</option>
                                <?php
                                if ($seats_free >= 2) {
                                ?>
                                    <option value="2" <?= htmlspecialchars($currentOrder['seats']) === '2' ? 'selected' : '' ?>>2</option>
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