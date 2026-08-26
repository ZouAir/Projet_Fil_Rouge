<?php
session_start();
// Vérifications session + profil
// Récupérer l'ID via $_GET['id']
// DELETE FROM events WHERE id = ?
// Rediriger vers dash-admin-evenements.php

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

if ($_SESSION['profil'] == 'abonne') {
    header('Location: index.php');
    exit;
}

$pdo = require_once('../includes/bdd.php');
$error = null;

if ($_SESSION['profil'] === 'administrateur') {
    $header = 'Location: dash-admin-evenements.php';
} elseif ($_SESSION['profil'] === 'service') {
    $header = 'Location: dash-service-evenements.php';
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
