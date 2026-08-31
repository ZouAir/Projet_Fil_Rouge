<?php
session_start();
$pdo = require_once('../includes/bdd.php');

if (!isset($_SESSION['id']) || !in_array($_SESSION['profil'], ['administrateur', 'service'])) {
    header('Location: login.php');
    exit;
}

$error = null;

if ($_SESSION['profil'] === 'administrateur') {
    $header = 'Location: dash-admin-events.php';
} elseif ($_SESSION['profil'] === 'service') {
    $header = 'Location: dash-service-events.php';
} else {
    $header = 'Location: index.php';
};

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
    if ($id === null) {
        $_SESSION['error'] = "Erreur : Évènement introuvable";
        header($header);
        exit;
    } else {
        $query = $pdo->prepare("DELETE FROM events WHERE id = ?");
        $query->execute([$id]);
        header($header);
        exit;
    }
}
