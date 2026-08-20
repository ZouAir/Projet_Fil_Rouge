<?php
session_start();
$pdo = require_once('../includes/bdd.php');

$error = null;
$id = $_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars(trim($_POST['name'])) ?? '';
    $first_name = htmlspecialchars(trim($_POST['first_name'])) ?? '';
    $email = htmlspecialchars(trim($_POST['email'])) ?? '';
    $phone = htmlspecialchars(trim($_POST['phone'])) ?? '';
    $birthday = htmlspecialchars(trim($_POST['birthday'])) ?? '';
    $adress = htmlspecialchars(trim($_POST['adress'])) ?? '';
    $postal = htmlspecialchars(trim($_POST['postal'])) ?? '';
    $city = htmlspecialchars(trim($_POST['city'])) ?? '';
    $status = htmlspecialchars(trim($_POST['status'])) ?? '';

    if (empty($error)) {
        try {
            $query = $pdo->prepare("UPDATE users 
            SET name = :name, first_name = :first_name , email = :email, phone = :phone, birthday = :birthday, adress = :adress, postal = :postal, city = :city, status = :status
            WHERE id = :id");
            $query->execute([
                ':id' => $id,
                ':name' => $name,
                ':first_name' => $first_name,
                ':email' => $email,
                ':phone' => $phone,
                ':birthday' => $birthday,
                ':adress' => $adress,
                ':postal' => $postal,
                ':city' => $city,
                ':status' => $status
            ]);
            header('Location: dash-user-compte.php');
            exit;
        } catch (PDOException $e) {
            die("Erreur : " . $e->getMessage());
        }
    }
}

$query = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$query->execute([$id]);
$user = $query->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../assets/css/header.css" rel="stylesheet">
    <link href="../assets/css/footer.css" rel="stylesheet">
    <link href="../assets/css/variables.css" rel="stylesheet">
    <!-- <link href="../assets/css/style.css" rel="stylesheet"> -->
    <!-- <link href="../assets/css/login.css" rel="stylesheet"> -->
    <link href="../assets/css/dashboard.css" rel="stylesheet">
    <link href="https://cdn.boxicons.com/3.0.8/fonts/basic/boxicons.min.css" rel="stylesheet">
    <title>MNS Football Club - Dashboard</title>
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
                        <li><a href="dash-user-evenements.php">Évènements</a></li>
                        <li><a href="dash-user-reservations.php">Réservations</a></li>
                        <li><a href="#">Mes amis</a></li>
                        <li><a href="#">Mon mvp</a></li>
                    </ul>
                    <div>Compte</div>
                    <a href="dash-user-compte.php">Mon compte</a>
                    <a href="logout.php">Déconnexion</a>
                </div>
                <div class="dash-content">
                    <div class="dash-head">
                        <div>
                            <span>ZR</span>
                            <span>Zouhair REGHAI</span>
                        </div>
                    </div>
                    <div>
                        <form action="dash-user-compte.php" method="POST">
                            <div class="form-item">
                                <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
                            </div>
                            <div class="form-item">
                                <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>" required>
                            </div>
                            <div class="form-item">
                                <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                            </div>
                            <div class="form-item">
                                <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" required>
                            </div>
                            <div class="form-item">
                                <input type="date" id="birthday" name="birthday" value="<?= htmlspecialchars($user['birthday']) ?>" required>
                            </div>
                            <div class="form-item">
                                <input type="text" id="adress" name="adress" value="<?= htmlspecialchars($user['adress']) ?>" required>
                            </div>
                            <div class="form-item city">
                                <input type="text" id="postal" name="postal" value="<?= htmlspecialchars($user['postal']) ?>" required>
                                <input type="text" id="city" name="city" value="<?= htmlspecialchars($user['city']) ?>" required>
                            </div>
                            <div class="form-item">
                                <select name="status" id="status" aria-label="Statut du compte">
                                    <option value="<?= htmlspecialchars($user['status']) ?>"><?= htmlspecialchars($user['status']) ?></option>
                                    <option value="Fermé">Fermé</option>
                                    <option value="Privé">Privé</option>
                                    <option value="Public">Public</option>
                                </select>
                            </div>
                            <div class="form-item">
                                <button class="submit" type="submit">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                    <div class="cta">
                        <a href="#" class="count-change">Changer mon mdp</a>
                    </div>
                    <div class="cta">
                        <a href="#" class="count-delete">Supprimer mon compte</a>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php include_once('../includes/footer.php') ?>
</body>

</html>