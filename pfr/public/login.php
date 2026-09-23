<?php
session_start();
$pdo = require_once('../includes/bdd.php');

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
        $passwordHash = $count['password'];
        if (password_verify($password, $passwordHash)) {
            $_SESSION['id'] = $count['id'];
            $_SESSION['name'] = $count['name'];
            $_SESSION['first_name'] = $count['first_name'];
            $_SESSION['email'] = $count['email'];
            $_SESSION['profil'] = $count['profil'];

            // Redirection selon profil
            if ($count['profil'] === 'administrateur' || $count['profil'] === 'service') {
                header("Location: staff-events.php");
            } elseif ($count['profil'] === 'abonne') {
                header("Location: user-events.php");
            } else {
                header("Location: index.php");
            }
            exit;
        } else {
            $error = "Identifiant ou mot de passe incorrect.";
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
    <link href="https://cdn.boxicons.com/3.0.8/fonts/basic/boxicons.min.css" rel="stylesheet">
    <link href="../assets/images/mon_logo.png" rel="icon" type="image/png">
    <script src="../assets/js/login.js" defer></script>
    <title>MNS FC - Login</title>
</head>

<body>
    <?php include_once('../includes/header.php') ?>
    <main>
        <div class="container">
            <div class="login-left">
                <img src="../assets/images/stade.jpg" alt="">
            </div>
            <div class="login-right">
                <div>
                    <?php
                    if (!isset($_SESSION['success'])) {
                        $modal = '';
                    } else {
                        $modal = $_SESSION['success'];
                    }
                    unset($_SESSION['success']);
                    ?>
                    <script>
                        const modal = '<?= htmlspecialchars($modal) ?>';
                    </script>
                </div>
                <div class="login-title">
                    <p>connexion</p>
                </div>
                <div class="login">
                    <form action="login.php" method="POST" novalidate>
                        <input type="email" name="email" id="email" placeholder="Email">
                        <input type="password" name="password" id="pwd" placeholder="Mot de passe">
                        <button type="submit">se connecter</button>
                    </form>
                    <?php if ($error): ?>
                        <div class="error"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    <div class="login-pwd">
                        <a id="pwd-forgot" href="password.php" target="_blank">Mot de passe oublié ?</a>
                    </div>
                    <div class="login-link">
                        <a id="subscribe" href="inscription.php" target="_blank">inscription</a>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php include_once('../includes/footer.php') ?>
</body>

</html>