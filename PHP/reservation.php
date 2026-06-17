<?php
// // Session start
// // Vérifier connexion → sinon login.php
// // Inclure bdd.php
// // Récupérer $id de l'event depuis $_GET
// // Vérifier que $id existe et est valide
// // Récupérer les infos de l'event depuis la BDD
// // Vérifier que l'event existe
// // Si POST → insérer la réservation dans orders
// // Si POST → rediriger vers index.php
// HTML en bas :
// // Afficher le nom et la date de l'event
// // Formulaire avec select "Nombre de places" (1 ou 2)
// // Bouton "Confirmer la réservation"
session_start();
$pdo = require_once('../includes/bdd.php');

//Si pas connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: ./login.php');
    exit;
} else {
    //Si connecté
    $error = null;
    $seats_taken = null; //Traitement à faire sur la page orders.php
    $id = $_GET['id'] ? (int)$_GET['id'] : null;
    $query = $pdo->prepare("SELECT * FROM events WHERE id = ?");
    $query->execute([$id]);
    $event = $query->fetch(PDO::FETCH_ASSOC);
    if (!$event) {
        die("Erreur : Évènement introuvable");
    }

    $seats_free = $event['capacity'] - $seats_taken;

    if ($_SERVER['REQUEST_METHOD'] === "POST") {
        try {
            $seats = $_POST['number_of_seats'] ?? '';
            if ($seats) {
                $query = $pdo->prepare("INSERT INTO orders (date, status, number_of_seats, events_id, user_id) VALUES (?, ?, ?, ?, ?)");
                $query->execute([date('Y-m-d'), 'en attente', $seats, $id, $_SESSION['user_id']]);
                header('Location: ../public/index.php');
                exit;
            }
        } catch (PDOException $e) {
            $error = "Erreur : " . $e->getMessage();
            die("Erreur : " . $e->getMessage());
        }
    }
}
?>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/variables.css">
    <link rel="stylesheet" href="assets/css/login.css">
    <link href="https://cdn.boxicons.com/3.0.8/fonts/basic/boxicons.min.css" rel="stylesheet">
    <script src="assets/js/script.js" defer></script>
    <title>Login</title>

</head>
<!-- Ceci est un commentaire -->

<body>
    <header>
        <?php include_once('../includes/header.php')
        ?>
    </header>
    <main>
        <form action="./reservation.php" method="POST">
            <h2>Mon tableau de bord</h2>
            <h3>Réservation évènement</h3>
            <p>Veuillez choisir le nombre de places que vous souhaitez réserver</p>
            <div class="form-group">
                <label for="">Catégorie :</label>
                <div>
                    <input><?php //requete SQL avec jointure pour avoir la catégorie de l'event 
                            ?></input>
                </div>
            </div>
            <div class="form-group">
                <label for="name"> :</label>
                <div>
                    <input value="<?= $event['name'] ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="description"> :</label>
                <div>
                    <input value="<?= substr($event['description'], 0, 100) ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="date">Date :</label>
                <div>
                    <input value="<?= $event['date'] ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="hour">Heure :</label>
                <div>
                    <input value="<?= $event['hour'] ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="price">Tarif :</label>
                <div>
                    <input value="<?= $event['price'] ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="status">Statut :</label>
                <div>
                    <input value="<?= $event['status'] ?>">
                </div>
            </div>
            <div class="form-group">
                <p>Places disponibles : <?= $seats_free ?></p>
            </div>
            <div class="form-group">
                <label for="number_of_seats">Nombre de places :</label>
                <div>
                    <select name="number_of_seats" id="number_of_seats">
                        <option value="">-</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <input type="submit" id="sub-btn" value="Réserver">
            </div>
        </form>
        <?php if (!empty($error)): ?>
            <p style="color:red"><?= $error ?></p>
        <?php endif; ?>
    </main>
    <footer>
        <?php //require_once('../includes/footer.php')
        ?>
    </footer>
</body>

</html>