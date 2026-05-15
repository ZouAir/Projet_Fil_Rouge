 <?php
    ////  Vérifications session + profil admin
    ////  Inclusion de bdd.php
    //  Récupérer l'ID de l'event via $_GET['id']
    //  Vérifier que l'ID existe en BDD (sinon rediriger)
    //  Si GET : afficher formulaire pré-rempli avec les données actuelles
    //  Si POST : UPDATE l'event en BDD + redirection vers admin-events.php
    //  Récupérer les catégories pour le select (comme dans ajouter)
    //  Gestion des erreurs avec $error

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
