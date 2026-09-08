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
    $old_password = $_POST['old_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $pwd_confirm = $_POST['pwd_confirm'] ?? '';

    $query = $pdo->prepare("SELECT password FROM users WHERE id = ?");
    $query->execute([$id]);
    $currentHash = $query->fetchColumn();

    if (!password_verify($old_password, $currentHash)) {
        $error = "Ancien mot de passe incorrect, veuillez saisir le bon svp";
    } else {
        if (strlen($new_password) < 8 || !preg_match('/[#@!?\$%&]/', $new_password) || !preg_match('/[0-9]/', $new_password) || !preg_match('/[A-Z]/', $new_password)) {
            $error = "Nouveau mot de passe ne respecte pas les règles ci-dessus ";
        } else {
            if ($new_password !== $pwd_confirm) {
                $error = "Les nouveaux mots de passe ne sont pas identiques";
            } else {
                $newHash = password_hash($new_password, PASSWORD_DEFAULT);
                $currentHash = $newHash;
            }
        }
    }

    if (empty($error)) {
        try {
            $query = $pdo->prepare(
                "UPDATE users SET 
                password = :password 
                WHERE id = :id"
            );
            $query->execute([
                ':password' => $currentHash,
                ':id' => $id
            ]);
            header($header);
            exit;
        } catch (PDOException $e) {
            $error = "Erreur : Le changement de passe n'a pas pu aboutir, veuillez recommencer svp !";
        }
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
    <link rel="icon" type="image/png" href="../assets/images/mon_logo.png">
    <script src="../assets/js/password.js" defer></script>
    <title>MNS FC - Mot de passe</title>
</head>

<body>
    <?php require_once('../includes/header.php') ?>
    <main class="event-wrap">
        <div class="event">
            <h3>Modifier mon mot de passe</h3>
            <p>-- Veuillez modifier votre mot de passe en respectant les règles suivantes --</p>
            <ul>
                <li id="pwd-criteria-length">8 caractères minimum</li>
                <li id="pwd-criteria-special">1 caractère spécial minimum [#@!?$%&]</li>
                <li id="pwd-criteria-uppercase">1 majuscule minimum</li>
                <li id="pwd-criteria-numeric">1 numérique minimum</li>
            </ul>
            <form action="password.php" method="post"
                id="id-form" class="form" novalidate>
                <div class="event-item">
                    <label for="old_password">Ancien mot de passe *</label>
                    <div>
                        <input type="password" id="old-pwd" name="old_password">
                    </div>
                </div>
                <div class="event-item">
                    <label for="new_password">Nouveau mot de passe *</label>
                    <div>
                        <input type="password" id="new-pwd" name="new_password">
                    </div>
                </div>
                <div class="event-item">
                    <label for="pwd_confirm">Confirmation nouveau mot de passe *</label>
                    <div>
                        <input type="password" id="pwd-confirm" name="pwd_confirm">
                    </div>
                </div>
                <div class="event-item">
                    <button type="submit" id="sub-btn">Valider</button>
                </div>
            </form>
            <?php if ($error): ?>
                <p class="error"><?= $error ?></p>
            <?php endif; ?>
        </div>
    </main>
    <?php require_once('../includes/footer.php') ?>
</body>

</html>