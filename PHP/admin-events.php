<?php
//// Session start
////  Vérifier connexion → sinon login.php
////  Vérifier que $_SESSION['profil'] === 'administrateur' → sinon index.php
////  Inclure bdd.php
////  Récupérer tous les events depuis la BDD
//  Afficher dans un tableau HTML avec boutons Modifier/Supprimer
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SESSION['profil'] !== 'administrateur') {
    header('Location: index.php');
    exit;
}

$pdo = require_once('bdd.php');
$query = $pdo->prepare("SELECT * FROM events WHERE id = ? ORDER BY date ASC");
$query->execute();
$events = $query->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>MNS Football Club</title>
</head>

<body>
    <header><!--ici le header --></header>
    <span>Bonjour <?= $_SESSION['email'] ?> !</span>
    <h1>Mon tableau de bord évènements</h1>


    foreach ($result as $row) {
    //print_r($row);
    echo "ID: " . $row['id'] . "<br>";
    echo "Date de début: " . $row['begin_date'] . "<br>";
    echo "<a href='edit.php?id=" . $row[' id'] . "'>Modifier</a> | " ;
        echo "<a href='delete.php?id=" . $row['id'] . "'>Supprimer</a> | " ;
        }

        <table>
        <caption>
            <p>Les prochains évènements</p>
        </caption>
        <thead>
            <tr>
                <th scope="col"></th>
            </tr>
        </thead>
        </table>