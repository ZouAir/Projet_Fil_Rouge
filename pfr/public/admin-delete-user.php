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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : null;
    if ($id === null) {
        $_SESSION['error'] = "Erreur : Abonné introuvable";
        header('Location: admin-users.php');
        exit;
    } else {
        try {
            $query = $pdo->prepare("DELETE FROM users WHERE id = ?");
            $query->execute([$id]);
            $_SESSION['success'] = "Opération réussie ! L'adhérent vient d'être supprimé";
        } catch (PDOException $e) {
            $_SESSION['error'] = "Erreur : Impossible de supprimer cet adhérent, veuillez réessayer svp";
        }
        header('Location: admin-users.php');
        exit;
    }
}
