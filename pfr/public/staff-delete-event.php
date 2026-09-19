<?php
session_start();
$pdo = require_once('../includes/bdd.php');

if (!isset($_SESSION['id']) || !in_array($_SESSION['profil'], ['administrateur', 'service'])) {
    header('Location: login.php');
    exit;
}

$error = null;

if ($_SESSION['profil'] === 'administrateur' || $_SESSION['profil'] === 'service') {
    $header = 'Location: dash-staff-events.php';
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
        try {
            $query = $pdo->prepare("DELETE FROM events WHERE id = ?");
            $query->execute([$id]);
        } catch (PDOException $e) {
            $_SESSION['error'] = "Erreur : Impossible de supprimer cet évènement, veuillez réessayer svp";
        }
        header($header);
        exit;
    }
}
