<?php
session_start();

$pdo = require('includes/bdd.php');

$error = "";

//Si déjà connecté
if (isset($_SESSION['email'])) {
    header('location: index.php');
    exit;
}

//Traitement POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $query = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $query->execute([$email]);
    $count = $query->fetch(PDO::FETCH_ASSOC);

    if (!$count) {
        $error = "Identifiant ou mot de passe incorrect";
    } else {
        $_SESSION['user_id'] = $count['id'];
        $_SESSION['email'] = $count['email'];
        $_SESSION['profil'] = $count['profil'];
        $passwordHash = $count['password'];

        if (password_verify($password, $passwordHash)) {
            // Redirection selon profil
            if ($count['profil'] == 'administrateur') {
                header("Location: dashboard-admin.php");
                //vérifier le header vers le bon fichier
            } elseif ($count['profil'] == 'service_reservation') {
                header("Location: dashboard-service.php");
                //vérifier le header vers le bon fichier
            } else {
                header("Location: dashboard-user.php");
                //vérifier le header vers le bon fichier
            }
            exit;
        } else {
            $error = "Identifiant ou mot de passe incorrects.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/variables.css">
    <link rel="stylesheet" href="assets/css/login.css">
    <link href="https://cdn.boxicons.com/3.0.8/fonts/basic/boxicons.min.css" rel="stylesheet">
    <script src="assets/js/script.js" defer></script>
    <title>Login</title>
</head>

<body>
    <header>
        <?php include_once('includes/header-login.php') ?>
    </header>
    <main>
        <div class="container">
            <div class="connection">
                <p>connexion</p>
            </div>
            <div class="field">
                <form method="POST" action="login.php" class="form">
                    <input type="email" name="email" id="email" placeholder="Email">
                    <input type="password" id="password" name="password" placeholder="Mot de passe">
                    <button type="submit">se connecter</button>
                </form>
                <?php if ($error): ?>
                    <p class="error"><?= htmlspecialchars($error) ?></p>
                <?php endif; ?>
                <div class="field-two">
                    <a id="password-forgot" href="#" target="_blank">Mot de passe oublié ?</a>
                    <!-- lien vers password forgort -->
                </div>
                <div class="field-three">
                    <a id="subscribe" href="inscription.php" target="_blank">inscription</a>
                </div>
            </div>
        </div>
    </main>
    <footer>
        <?php include_once('includes/footer.php') ?>
    </footer>
</body>

</html>