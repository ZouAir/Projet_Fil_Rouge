<?php
////  Vérifications session + profil admin
////  Récupérer l'ID via $_GET['id']
////  DELETE FROM events WHERE id = ?
////  Rediriger vers admin-events.php

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
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
    if ($id === null) {
        $_SESSION['error'] = "Erreur : Évènement introuvable";
        header('Location: admin-events.php');
        exit;
    } else {
        $query = $pdo->prepare("DELETE FROM events WHERE id = ?");
        $query->execute([$id]);

        header('Location: admin-events.php');
        exit;
    }
}
