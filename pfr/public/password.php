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

if ($profil === 'administrateur') {
    $header = 'Location: dash-admin-events.php';
} elseif ($profil === 'service') {
    $header = 'Location: dash-service-events.php';
} else {
    $header = 'Location: dash-user-events.php';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old_password = $_POST['old_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    $query = $pdo->prepare("SELECT password FROM users WHERE id = ?");
    $query->execute([$id]);
    $currentHash = $query->fetchColumn();

    if (!password_verify($old_password, $currentHash)) {
        $error = "Ancien mot de passe incorrect, veuillez saisir le bon mot de passe svp";
    } else {
        if ($new_password !== $confirm_password) {
            $error = "Erreur : Les nouveaux mots de passe ne sont pas identiques";
        } else {
            $newHash = password_hash($new_password, PASSWORD_DEFAULT);
            $currentHash = $newHash;
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
    <title>MNS Football Club - évènement</title>
</head>

<body>
    <?php require_once('../includes/header.php') ?>
    <main class="event-wrap">
        <div class="event">
            <h3>Modifier mon mot de passe</h3>
            <p>-- Veuillez modifier votre mot de passe en respectant les règles suivantes --</p>
            <ul>
                <li>8 caractères minimum</li>
                <li>1 caractère spécial minimum [#@!?$%&]</li>
                <li>1 majuscule minimum</li>
                <li>1 numérique minimum</li>
            </ul>
            <form action="password.php" method="post"
                id="id-form" class="form">
                <div class="event-item">
                    <label for="old_password">Ancien mot de passe *</label>
                    <div>
                        <input type="password" id="old_password" name="old_password">
                    </div>
                </div>
                <div class="event-item">
                    <label for="new_password">Nouveau mot de passe *</label>
                    <div>
                        <input type="password" id="new_password" name="new_password">
                    </div>
                </div>
                <div class="event-item">
                    <label for="confirm_password">Confirmation nouveau mot de passe *</label>
                    <div>
                        <input type="password" id="confirm_password" name="confirm_password">
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