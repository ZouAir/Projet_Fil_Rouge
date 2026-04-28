<?php
session_start();

$pdo = require('bdd.php');

$error = "";

//Si déjà connecté
if (isset($_SESSION['pseudo'])) {
    header('location: index.php');
    exit;
}

//Traitement POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $pseudo = $_POST['pseudo'] ?? '';
    $password = $_POST['password'] ?? '';

    $query = $pdo->prepare("SELECT * FROM utilisateurs WHERE pseudo = ?");
    $query->execute([$pseudo]);
    $club = $query->fetch(PDO::FETCH_ASSOC);

    if (!$club) {
        $error = "Identifiant ou mot de passe incorrect";
    } else {
        $_SESSION['user_id'] = $club['id'];
        $_SESSION['pseudo'] = $club['pseudo'];
        $passwordHash = $club['mot_de_passe'];

        if (password_verify($password, $passwordHash)) {
            header('Location: index.php');
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
    <title>Login</title>
</head>

<body>
    <h1>Connexion</h1>

    <?php if ($error !== ''): ?>
        <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label>Pseudo :</label><br>
        <input type="text" name="pseudo"><br><br>

        <label>Mot de passe :</label><br>
        <input type="password" name="password"><br><br>

        <button type="submit">Se connecter</button>
    </form>
</body>

</html>