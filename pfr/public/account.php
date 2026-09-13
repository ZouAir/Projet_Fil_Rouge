<?php
session_start();
$pdo = require_once('../includes/bdd.php');

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

$id = $_SESSION['id'];
$profil = $_SESSION['profil'];
$error = null;

if ($profil === 'administrateur' || $profil === 'service') {
    $header = 'Location: staff-events.php';
} else {
    $header = 'Location: user-events.php';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name       = trim(mb_strtolower($_POST['name'] ?? ''));
    $first_name = trim(mb_strtolower($_POST['first_name'] ?? ''));
    $email      = trim(mb_strtolower($_POST['email'] ?? ''));
    $phone      = trim($_POST['phone'] ?? '');
    $birthday   = $_POST['birthday'] ?? '';
    $adress     = trim(mb_strtolower($_POST['adress'] ?? ''));
    $postal     = trim($_POST['postal'] ?? '');
    $city       = trim(mb_strtolower($_POST['city'] ?? ''));
    $status     = $_POST['status'] ?? '';

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Veuillez saisir une adresse e-mail valide.";
    }

    if ($birthday === '') {
        $birthday = null;
    }

    if ($adress === '') {
        $adress = "adresse à compléter";
    }

    if ($postal === '') {
        $postal = "00000";
    }

    if ($city === '') {
        $city = "ville";
    }

    if (empty($error)) {
        try {
            $query = $pdo->prepare("UPDATE users 
            SET name    = :name, 
            first_name  = :first_name, 
            email       = :email, 
            phone       = :phone, 
            birthday    = :birthday, 
            adress      = :adress, 
            postal      = :postal, 
            city        = :city, 
            status      = :status
            WHERE id    = :id");

            $query->execute([
                ':id'       => $id,
                ':name'     => $name,
                ':first_name' => $first_name,
                ':email'    => $email,
                ':phone'    => $phone,
                ':birthday' => $birthday,
                ':adress'   => $adress,
                ':postal'   => $postal,
                ':city'     => $city,
                ':status'   => $status
            ]);

            header($header);
            exit;
        } catch (PDOException $e) {
            $error = "Erreur lors de la mise à jour des données, veuillez réessayer svp.";
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
    <link href="../assets/css/style.css" rel="stylesheet">
    <!-- <link href="../assets/css/login.css" rel="stylesheet"> -->
    <!-- <link href="../assets/css/dashboard.css" rel="stylesheet"> -->
    <link href="https://cdn.boxicons.com/3.0.8/fonts/basic/boxicons.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../assets/images/mon_logo.png">
    <script src="../assets/js/header.js" defer></script>
    <script src="../assets/js/account.js" defer></script>
    <title>MNS FC - Compte</title>
</head>

<body>
    <?php require_once('../includes/header.php') ?>
    <main class="event-wrap">
        <div class="event">
            <h3>Modifier mon compte</h3>
            <p>-- Veuillez modifier les informations de votre compte --</p>
            <form action="account.php" method="post"
                id="form" class="form" novalidate>
                <div class="event-item">
                    <label for="name">Nom</label>
                    <div>
                        <input type="text" id="name" name="name" value="<?= htmlspecialchars(strtoupper($user['name'])) ?>">
                    </div>
                </div>
                <div class="event-item">
                    <label for="first_name">Prénom</label>
                    <div>
                        <input type="text" id="firstname" name="first_name" value="<?= htmlspecialchars(ucfirst($user['first_name'])) ?>">
                    </div>
                </div>
                <div class="event-item">
                    <label for="email">Email</label>
                    <div>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>">
                    </div>
                </div>
                <div class="event-item">
                    <label for="phone">Téléphone</label>
                    <div>
                        <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($user['phone']) ?>">
                    </div>
                </div>
                <div class="event-item">
                    <label for="birthday">Date naissance</label>
                    <div>
                        <input type="date" id="birthday" name="birthday" value="<?= htmlspecialchars($user['birthday']) ?>">
                    </div>
                </div>
                <div class="event-item">
                    <label for="adress">Adresse</label>
                    <div>
                        <input type="text" id="adress" name="adress" value="<?= htmlspecialchars($user['adress']) ?>">
                    </div>
                </div>
                <div class="event-item">
                    <label for="postal">Code postal</label>
                    <div>
                        <input type="text" id="postal" name="postal" value="<?= htmlspecialchars($user['postal']) ?>">
                    </div>
                </div>
                <div class="event-item">
                    <label for="city">Ville</label>
                    <div>
                        <input type="text" id="city" name="city" value="<?= htmlspecialchars(ucfirst($user['city'])) ?>">
                    </div>
                </div>
                <div class="event-item">
                    <label for="status">Statut</label>
                    <div class="form-item">
                        <select name="status" id="status" aria-label="Statut du compte">
                            <option value="Fermé" <?= $user['status'] === "Fermé" ? 'selected' : '' ?>>Fermé</option>
                            <option value="Privé" <?= $user['status'] === "Privé" ? 'selected' : '' ?>>Privé</option>
                            <option value="Public" <?= $user['status'] === "Public" ? 'selected' : '' ?>>Public</option>
                        </select>
                    </div>
                </div>
                <div class="event-item">
                    <button type="submit" id="sub-btn">Valider</button>
                </div>
            </form>
            <?php if ($error): ?>
                <p class="error"><?= $error ?></p>
            <?php endif; ?>
            <div class="link">
                <a href="password.php">Modifier mon mot de passe</a>
            </div>
        </div>
    </main>
    <?php require_once('../includes/footer.php') ?>
</body>

</html>