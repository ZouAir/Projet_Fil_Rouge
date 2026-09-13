<?php
session_start();
$pdo = require_once('../includes/bdd.php');
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim(mb_strtolower($_POST['name'] ?? ''));
    $first_name = trim(mb_strtolower($_POST['first_name'] ?? ''));
    $email = trim(mb_strtolower($_POST['email'] ?? ''));
    $phone = trim($_POST['phone'] ?? '');

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Veuillez saisir une adresse e-mail valide.";
    }

    if ($_POST['password'] !== $_POST['password-confirm']) {
        $error = "Les mots de passe ne correspondent pas";
    } else {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        if (empty($error)) {
            try {
                $query = $pdo->prepare("INSERT INTO users (name, first_name, email, password, phone, birthday, adress, postal, city) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $query->execute([$name, $first_name, $email, $password, $phone, '2000-01-01', 'adresse à modifier', '00000', 'ville']);
                $_SESSION['success'] = "Compte créé ! Vous pouvez vous connecter.";
                header('Location: login.php');
                exit;
            } catch (PDOException $e) {
                $error = "Erreur lors de l'inscription";
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
    <link href="../assets/css/header.css" rel="stylesheet">
    <link href="../assets/css/footer.css" rel="stylesheet">
    <link href="../assets/css/variables.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
    <link href="../assets/css/login.css" rel="stylesheet">
    <!-- <link href="../assets/css/dashboard.css" rel="stylesheet"> -->
    <link href="https://cdn.boxicons.com/3.0.8/fonts/basic/boxicons.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../assets/images/mon_logo.png">
    <script src="../assets/js/inscription.js" defer></script>
    <title>MNS FC - Inscription</title>
</head>

<!-- Ceci est un commentaire -->

<body>
    <?php include_once('../includes/header.php') ?>
    <main>
        <div class="container">
            <div class="login-left">
                <img src="../assets/images/stade.jpg" alt="">
            </div>
            <div class="login-right">
                <div class="login-title">
                    <p>inscription</p>
                </div>
                <div class="login">
                    <form action="inscription.php" method="POST" novalidate>
                        <div class="info">* champs obligatoires</div>
                        <input type="text" id="name" name="name" placeholder="Nom *" autocomplete="username">
                        <input type="text" id="firstname" name="first_name" placeholder="Prénom *">
                        <input type="email" id="email" name="email" placeholder="Email *">
                        <input type="text" id="phone" name="phone" placeholder="Téléphone *">
                        <input type="password" name="password" id="pwd" placeholder="Mot de passe *" autocomplete="new-password">
                        <div class="rules">
                            <div>Le mot de passe doit respecter les règles suivantes :</div>
                            <ul>
                                <li id="pwd-criteria-length">8 caractères minimum</li>
                                <li id="pwd-criteria-special">1 caractère spécial minimum [#@!?$%&]</li>
                                <li id="pwd-criteria-uppercase">1 majuscule minimum</li>
                                <li id="pwd-criteria-numeric">1 numérique minimum</li>
                            </ul>
                        </div>
                        <input type="password" name="password-confirm" id="pwd-confirm"
                            placeholder="Confirmation mot de passe *">
                        <?php if ($error): ?>
                            <p class="error"><?= htmlspecialchars($error) ?></p>
                        <?php endif; ?>
                        <button type="submit" id="sub-btn">Valider</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <?php include_once('../includes/footer.php') ?>
</body>

</html>