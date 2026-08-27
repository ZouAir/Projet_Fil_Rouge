<?php
session_start();
// Vérifications session + profil
// Récupérer l'ID via $_GET['id']
// DELETE FROM users WHERE id = ?
// Rediriger vers dash-admin-abonnes.php

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

if ($_SESSION['profil'] !== 'administrateur') {
    header('Location: index.php');
    exit;
}

$pdo = require_once('../includes/bdd.php');
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
    if ($id === null) {
        $_SESSION['error'] = "Erreur : Abonné introuvable";
        header('Location: dash-admin-abonnes.php');
        exit;
    } else {
        $query = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $query->execute([$id]);
        header('Location: dash-admin-abonnes.php');
        exit;
    }
}
