<?php
session_start();
// Vérifications session + profil admin
// Inclusion de bdd.php
// Structure if/else sur REQUEST_METHOD
// Si POST : INSERT INTO events + redirection
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

if ($_SESSION['profil'] !== 'administrateur') {
    header('Location: index.php');
    exit;
}

$pdo = require_once('../includes/bdd.php');
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim(strtolower($_POST['name']));
    $first_name = trim(strtolower($_POST['first_name']));
    $email = trim(strtolower($_POST['email']));
    $phone = (int)trim(strtolower($_POST['phone']));
    $birthday = $_POST['birthday'];
    $adress = trim(strtolower($_POST['adress']));
    $postal = trim(strtolower($_POST['postal']));
    $city = trim(strtolower($_POST['city']));
    $profil = $_POST['profil'];
    $is_actif = $_POST['is_actif'];

    try {
        $query = $pdo->prepare("INSERT INTO users (name, first_name, email, phone, birthday, adress, postal, city, profil) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $query->execute([$name, $first_name, $email, $phone, $birthday, $adress, $postal, $city, $profil]);
        header('Location: dash-admin-abonnes.php');
        exit;
    } catch (PDOException $e) {
        $error = "Erreur : " . $e->getMessage();
    }
}
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
    <!-- <link href="../assets/css/login.css" rel="stylesheet"> -->
    <!-- <link href="../assets/css/dashboard.css" rel="stylesheet"> -->
    <link href="https://cdn.boxicons.com/3.0.8/fonts/basic/boxicons.min.css" rel="stylesheet">
    <title>MNS Football Club - Abonnés</title>
</head>

<body>
    <?php require_once('../includes/header.php') ?>
    <main class="event-wrap">
        <div class="event">
            <h3>Ajouter un abonné</h3>
            <p> * = champs obligatoires</p>
            <form action="admin-add-user.php" method="post"
                id="id-form" class="form">
                <input type="hidden" name="id">
                <div class="event-item">
                    <label for="name">Nom</label>
                    <div>
                        <input type="text" id="name" name="name" ?>
                    </div>
                </div>
                <div class="event-item">
                    <label for="first_name">Prénom</label>
                    <div>
                        <input type="text" id="first_name" name="first_name">
                    </div>
                </div>
                <div class="event-item">
                    <label for="email">Email</label>
                    <div>
                        <input type="email" id="email" name="email">
                    </div>
                </div>
                <div class="event-item">
                    <label for="phone">Téléphone</label>
                    <div>
                        <input type="text" id="phone" name="phone">
                    </div>
                </div>
                <div class="event-item">
                    <label for="birthday">Anniversaire</label>
                    <div>
                        <input type="date" id="birthday" name="birthday">
                    </div>
                </div>
                <div class="event-item">
                    <label for="adress">Adresse</label>
                    <div>
                        <input type="text" id="adress" name="adress">
                    </div>
                </div>
                <div class="event-item">
                    <label for="postal">Code Postal</label>
                    <div>
                        <input type="text" id="postal" name="postal">
                    </div>
                </div>
                <div class="event-item">
                    <label for="city">Ville</label>
                    <div>
                        <input type="text" id="city" name="city">
                    </div>
                </div>
                <div class="event-item">
                    <label for="profil">Profil</label>
                    <div>
                        <select name="profil" id="profil">
                            <option value="">Choisir</option>
                            <option value="administrateur">Administrateur</option>
                            <option value="service">Service</option>
                            <option value="abonne">Abonné</option>
                        </select>
                    </div>
                </div>
                <div class="event-item">
                    <label for="is_actif">Actif</label>
                    <div>
                        <select name="is_actif" id="is_actif">
                            <option value="">Choisir</option>
                            <option value="1">Actif</option>
                            <option value="0">Inactif</option>
                        </select>
                    </div>
                </div>
                <div class="event-item">
                    <button type="submit" id="sub-btn">Valider</button>
                </div>
            </form>
            <?php if ($error): ?>
                <p style="color:red"><?= $error ?></p>
            <?php endif; ?>
        </div>
    </main>
    <?php require_once('../includes/footer.php') ?>
</body>

</html>