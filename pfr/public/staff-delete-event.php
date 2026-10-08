<?php
session_start();
$pdo = require_once('../includes/bdd.php');

if (!isset($_SESSION['id']) || !in_array($_SESSION['profile'], ['administrateur', 'service'])) {
    header('Location: login.php');
    exit;
}

$error = null;

if ($_SESSION['profile'] === 'administrateur' || $_SESSION['profile'] === 'service') {
    $header = 'Location: staff-events.php';
} else {
    $header = 'Location: index.php';
};

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : null;
    if ($id === null) {
        $_SESSION['error'] = "Erreur : Évènement introuvable";
        header($header);
        exit;
    } else {
        try {
            $query = $pdo->prepare("DELETE FROM events WHERE id = ?");
            $query->execute([$id]);
            $_SESSION['success'] = "Opération réussie ! L'évènement vient d'être supprimé";
        } catch (PDOException $e) {
            $_SESSION['error'] = "Erreur : Impossible de supprimer cet évènement, des réservations y sont associées";
        }
        header($header);
        exit;
    }
}
