<?php
session_start();
$pdo = require_once('../includes/bdd.php');

if (!isset($_SESSION['id']) || !in_array($_SESSION['profil'], ['administrateur', 'service'])) {
    header('Location: login.php');
    exit;
}

$error = null;

if ($_SESSION['profil'] === 'administrateur' || $_SESSION['profil'] === 'service') {
    $header = 'Location: staff-reservations.php';
} else {
    $header = 'Location: index.php';
};

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : null;
    if ($id === null) {
        $_SESSION['error'] = "Erreur : Réservation introuvable";
        header($header);
        exit;
    } else {
        try {
            $query = $pdo->prepare("DELETE FROM orders WHERE id = ?");
            $query->execute([$id]);
            $_SESSION['success'] = "Opération réussie ! La réservation vient d'être supprimée";
        } catch (PDOException $e) {
            $_SESSION['error'] = "Erreur : Impossible de supprimer cette réservation, veuillez réessayer svp";
        }
        header($header);
        exit;
    }
}
