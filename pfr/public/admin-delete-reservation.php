<?php
session_start();
$pdo = require_once('../includes/bdd.php');

if (!isset($_SESSION['id']) || !in_array($_SESSION['profil'], ['administrateur', 'service'])) {
    header('Location: login.php');
    exit;
}

$error = null;

if ($_SESSION['profil'] === 'administrateur') {
    $header = 'Location: dash-admin-reservations.php';
} elseif ($_SESSION['profil'] === 'service') {
    $header = 'Location: dash-service-reservations.php';
} else {
    $header = 'Location: index.php';
};

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
    if ($id === null) {
        $_SESSION['error'] = "Erreur : Réservation introuvable";
        header($header);
        exit;
    } else {
        $query = $pdo->prepare("DELETE FROM orders WHERE id = ?");
        $query->execute([$id]);
        header($header);
        exit;
    }
}
