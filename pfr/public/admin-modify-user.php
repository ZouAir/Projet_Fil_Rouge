<?php
session_start();
$pdo = require_once('../includes/bdd.php');
$error = null;
// Vérifications session + profil admin
// Inclusion de bdd.php
// Récupérer l'ID de l'event via $_GET['id']
// Vérifier que l'ID existe en BDD (sinon rediriger)
// Si GET : afficher formulaire pré-rempli avec les données actuelles
// Si POST : UPDATE l'event en BDD + redirection vers admin-events.php
// Récupérer les catégories pour le select (comme dans ajouter)
//  Gestion des erreurs avec $error

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

if ($_SESSION['profil'] !== 'administrateur') {
    header('Location: index.php');
    exit;
} else {
    if ($_SERVER['REQUEST_METHOD'] === "GET") {
        $id = $_GET['id'] ? (int)$_GET['id'] : null;
    } else {
        $id = $_POST['id'] ? (int)$_POST['id'] : null;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_GET['id'])) {
        header('Location: dash-admin-abonnes.php');
        exit;
    }
    $query = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $query->execute([$id]);
    $currentUser = $query->fetch(PDO::FETCH_ASSOC);
}

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
        $query = $pdo->prepare(
            "UPDATE users SET 
                name = :name, 
                first_name = :first_name,  
                email = :email, 
                phone = :phone, 
                birthday = :birthday, 
                adress = :adress,
                postal = :postal,
                city = :city, 
                profil = :profil,
                is_actif = :is_actif
                WHERE id = :id"
        );
        $query->execute([
            ':name' => $name,
            ':first_name' => $first_name,
            ':email' => $email,
            ':phone' => $phone,
            ':birthday' => $birthday,
            ':adress' => $adress,
            ':postal' => $postal,
            ':city' => $city,
            ':profil' => $profil,
            ':is_actif' => $is_actif,
            ':id' => $id
        ]);
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
    <title>MNS Football Club - évènement</title>
</head>

<body>
    <?php require_once('../includes/header.php') ?>
    <main class="event-wrap">
        <div class="event">
            <h3>Modifier un abonné</h3>
            <p>Veuillez modifier les informations de l'abonné</p>
            <form action="admin-modify-user.php" method="post"
                id="id-form" class="form">
                <input type="hidden" name="id" value="<?= $currentUser['id'] ?>">
                <div class="event-item">
                    <label for="name">Nom</label>
                    <div>
                        <input type="text" id="name" name="name" value="<?= strtoupper(htmlspecialchars($currentUser['name'])) ?>">
                    </div>
                </div>
                <div class="event-item">
                    <label for="first_name">Prénom</label>
                    <div>
                        <input type="text" id="first_name" name="first_name" value="<?= ucfirst(htmlspecialchars($currentUser['first_name'])) ?>">
                    </div>
                </div>
                <div class="event-item">
                    <label for="email">Email</label>
                    <div>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($currentUser['email']) ?>">
                    </div>
                </div>
                <div class="event-item">
                    <label for="phone">Téléphone</label>
                    <div>
                        <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($currentUser['phone']) ?>">
                    </div>
                </div>
                <div class="event-item">
                    <label for="birthday">Anniversaire</label>
                    <div>
                        <input type="date" id="birthday" name="birthday" value="<?= $currentUser['birthday'] ?>">
                    </div>
                </div>
                <div class="event-item">
                    <label for="adress">Adresse</label>
                    <div>
                        <input type="text" id="adress" name="adress" value="<?= htmlspecialchars($currentUser['adress']) ?>">
                    </div>
                </div>
                <div class="event-item">
                    <label for="postal">Code Postal</label>
                    <div>
                        <input type="text" id="postal" name="postal" value="<?= htmlspecialchars($currentUser['postal']) ?>">
                    </div>
                </div>
                <div class="event-item">
                    <label for="city">Ville</label>
                    <div>
                        <input type="text" id="city" name="city" value="<?= htmlspecialchars($currentUser['city']) ?>">
                    </div>
                </div>
                <div class="event-item">
                    <label for="profil">Profil</label>
                    <div>
                        <select name="profil" id="profil">
                            <option value="">Choisir</option>
                            <option value="administrateur" <?= ($currentUser['profil']) === 'administrateur' ? 'selected' : '' ?>>Administrateur</option>
                            <option value="service" <?= htmlspecialchars($currentUser['profil']) === 'service' ? 'selected' : '' ?>>Service</option>
                            <option value="abonne" <?= htmlspecialchars($currentUser['profil']) === 'abonne' ? 'selected' : '' ?>>Abonné</option>
                        </select>
                    </div>
                </div>
                <div class="event-item">
                    <label for="is_actif">Actif</label>
                    <div>
                        <select name="is_actif" id="is_actif">
                            <option value="">Choisir</option>
                            <option value="1" <?= $currentUser['is_actif'] == '1' ? 'selected' : '' ?>>Actif</option>
                            <option value="0" <?= $currentUser['is_actif'] == '0' ? 'selected' : '' ?>>Inactif</option>
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