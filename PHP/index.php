<?php

session_start();
$pdo = require_once('bdd.php');

//Si pas connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
} else {
    //Si déjà connecté
    $query = $pdo->prepare("SELECT * FROM films WHERE id_user = ?");
    $query->execute([$_SESSION['user_id']]);
    $films = $query->fetchALL(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Ma Cinémathèque</title>
</head>

<body>
    <h1>Bienvenue <?= $_SESSION['pseudo'] ?> !</h1>

    <?php
    foreach ($films as $row) {
        echo "Id: " . $row['id'] . "<br>";
        echo "Titre: " . $row['titre'] . "<br>";
        echo "Année: " . $row['annee'] . "<br>";
        echo "Genre: " . $row['genre'] . "<br>";
        echo "Note: " . $row['note'] . "<br>";
    }
    ?>

    <!-- Lien vers ajouter.php -->
    <div>
        <a href="ajouter.php">Ajouter un film</a>
    </div>
    <!-- Lien vers logout.php -->
    <div>
        <a href="logout.php">Se déconnecter</a>
    </div>

</body>

</html>