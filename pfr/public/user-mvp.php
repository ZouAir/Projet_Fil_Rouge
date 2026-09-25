<?php
session_start();
$pdo = require_once('../includes/bdd.php');
$error = '';
// Jointure entre orders et events
// Filtre sur la date de l'évènement => dans le passé
// Filtre sur le statut de la réservation => validée
// Filtre sur l'utilisateur connecté
// Filtre sur les évènements sportifs => exclure l'équipe => Loisirs
// Tri par date décroissante, limite une seule ligne

$id = $_SESSION['id'];
$name = $_SESSION['name'];
$first_name = $_SESSION['first_name'];
$initials = strtoupper(substr($first_name, 0, 1) . '.' . substr($name, 0, 1));

// Recherche évènement votable
$query = $pdo->prepare("
SELECT *, e.date AS event_date FROM orders o 
INNER JOIN events e ON o.events_id = e.id
WHERE e.date < NOW() 
AND o.status = 'Validée'
AND o.users_id = ?
AND e.team != 'Loisirs'
ORDER BY event_date DESC
LIMIT 1
  ");
$query->execute([$id]);
$event = $query->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    $error = "Aucun évènement disponible pour le vote.";
} else {
    // Recherche vote déjà existant
    $query = $pdo->prepare("SELECT * FROM notation WHERE events_id = ? AND users_id = ?");
    $query->execute([$event['id'], $id]);
    $notation = $query->fetch(PDO::FETCH_ASSOC);

    // Recherche liste athlètes pour le form
    $team = $event['team'];
    $date = new DateTime($event['event_date']);

    $query = $pdo->prepare("SELECT * FROM athlets WHERE team = ?");
    $query->execute([$team]);
    $athlets = $query->fetchAll(PDO::FETCH_ASSOC);

    // Traitement vote & note
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $note = trim($_POST['note'] ?? '');
        $vote = trim($_POST['vote'] ?? '');
        $event_id = trim($event['id'] ?? '');
        $user_id = $_SESSION['id'];

        if ($notation) {
            $error = "Erreur : Vous avez déjà voté pour cet évènement.";
        } else {
            try {
                $query = $pdo->prepare("
            INSERT INTO `notation` (mvp_player, event_rating, events_id, users_id)
            VALUES (?, ?, ?, ?)
            ");
                $query->execute([$vote, $note, $event_id, $user_id]);
            } catch (PDOException $e) {
                $error = "Erreur : Désolé vote impossible";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../assets/css/variables.css" rel="stylesheet">
    <link href="../assets/css/header.css" rel="stylesheet">
    <link href="../assets/css/footer.css" rel="stylesheet">
    <link href="../assets/css/dashboard.css" rel="stylesheet">
    <link href="https://cdn.boxicons.com/3.0.8/fonts/basic/boxicons.min.css" rel="stylesheet">
    <link href="../assets/images/mon_logo.png" rel="icon" type="image/png">
    <script src="../assets/js/password.js" defer></script>
    <script src="../assets/js/script.js" defer></script>
    <title>MNS FC - MVP</title>
</head>

<body>
    <?php include_once('../includes/header.php') ?>
    <main>
        <div class="container">
            <div class="dashboard">
                <div class="dash-sidebar">
                    <div>Navigation</div>
                    <ul>
                        <li><a href="index.php">Accueil</a></li>
                        <li><a href="user-events.php">Évènements</a></li>
                        <li><a href="user-reservations.php">Réservations</a></li>
                        <li><a href="#">Amis</a></li>
                        <li><a href="user-mvp.php">MVP</a></li>
                    </ul>
                    <div>Compte</div>
                    <a href="account.php">Mon compte</a>
                    <a href="logout.php">Déconnexion</a>
                </div>
                <div class="dash-content">
                    <div class="dash-head">
                        <div>
                            <span><?= $initials ?></span>
                            <span><?= ucfirst($first_name) . " " . strtoupper($name) ?></span>
                        </div>
                        <div>Mon tableau de bord</div>
                    </div>
                    <div class="title">
                        Vote MVP & Note du match
                    </div>
                    <?php
                    if ($event) {
                    ?>
                        <div class="vote">
                            <form action="user-mvp.php" method="post">
                                <div>
                                    <label for="vote">Mon MVP</label>
                                    <select name="vote" id="vote">
                                        <?php
                                        foreach ($athlets as $athlet) {
                                        ?>
                                            <option value="<?= $athlet['id'] ?>"><?= ucfirst(htmlspecialchars($athlet['first_name'])) . ' ' . strtoupper(htmlspecialchars($athlet['name'])) ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div>
                                    <label for="note">Note du match</label>
                                    <select name="note" id="note">
                                        <option value="">-</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                </div>
                                <div>
                                    <button type="submit" id="mvp-btn">Valider</button>
                                </div>
                            </form>
                        </div>
                        <div class="title">
                            Résultats des votes
                        </div>
                        <div class="vote">
                            <span>MVP du match :</span>
                            <span class="mvp">Ali REGHAI</span>
                            <span>Note du match :</span>
                            <span class="star">
                                <i class="bxf bx-star"></i>
                                <i class="bxf bx-star"></i>
                                <i class="bxf bx-star"></i>
                                <i class="bxf bx-star"></i>
                                <i class="bx bx-star"></i>
                            </span>
                        </div>
                    <?php
                    }
                    if ($error):
                    ?>
                        <p class="error"><?= $error ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
    <?php include_once('../includes/footer.php') ?>
</body>

</html>