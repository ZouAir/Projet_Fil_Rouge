<?php
// // Session start
// // Vérifier connexion → sinon login.php
// // Inclure bdd.php
// // Récupérer $id de l'event depuis $_GET
// // Vérifier que $id existe et est valide
// // Récupérer les infos de l'event depuis la BDD
//  Vérifier que l'event existe
//  Si POST → insérer la réservation dans orders
//  Si POST → rediriger vers index.php
// HTML en bas :
//  Afficher le nom et la date de l'event
//  Formulaire avec select "Nombre de places" (1 ou 2)
//  Bouton "Confirmer la réservation"
session_start();
$pdo = require_once('bdd.php');
//Si pas connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
} else {
    //Si connecté
    $id = $_GET['id'] ? $_GET['id'] : '';
    $query = $pdo->prepare("SELECT * FROM events WHERE id = ?");
    $query->execute();
    $events = $query->fetchAll(PDO::FETCH_ASSOC);
    $name = $events['name'];
    $date = $events['date'];
}
$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
}
