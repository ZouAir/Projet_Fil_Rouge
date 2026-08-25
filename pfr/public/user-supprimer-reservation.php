<?php
////  Vérifications session + profil
////  Récupérer l'ID via $_GET['id']
////  DELETE FROM orders WHERE id = ?
////  Rediriger vers dash-user-reservations.php
session_start();

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

if ($_SESSION['profil'] !== 'abonne') {
    header('Location: index.php');
    exit;
}

$pdo = require_once('../includes/bdd.php');
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
    if ($id === null) {
        $_SESSION['error'] = "Erreur : Réservation introuvable";
        header('Location: dash-user-reservations.php');
        exit;
    } else {
        $query = $pdo->prepare("DELETE FROM orders WHERE id = ? AND users_id = ?");
        $query->execute([$id, $_SESSION['id']]);

        header('Location: dash-user-reservations.php');
        exit;
    }
}
