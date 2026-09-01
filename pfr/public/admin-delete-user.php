<?php
session_start();
$pdo = require_once('../includes/bdd.php');
$error = null;

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

if ($_SESSION['profil'] !== 'administrateur') {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
    if ($id === null) {
        $_SESSION['error'] = "Erreur : Abonné introuvable";
        header('Location: admin-users.php');
        exit;
    } else {
        $query = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $query->execute([$id]);
        header('Location: admin-users.php');
        exit;
    }
}
