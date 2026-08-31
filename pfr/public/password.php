<?php
session_start();
$pdo = require_once('../includes/bdd.php');
$error = null;
// Vérifications session
// Inclusion de bdd.php
// Récupérer l'ID de l'event via $_GET['id']
// Vérifier que l'ID existe en BDD (sinon rediriger)
// Si GET : afficher formulaire pré-rempli avec les données actuelles
// Si POST : UPDATE l'event en BDD + redirection vers admin-events.php
// Récupérer les catégories pour le select (comme dans ajouter)
// Gestion des erreurs avec $error


if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === "GET") {
    $id = $_GET['id'] ? (int)$_GET['id'] : null;
} else {
    $id = $_POST['id'] ? (int)$_POST['id'] : null;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_GET['id'])) {
        header('Location: index.php');
        exit;
    }
    $query = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $query->execute([$id]);
    $currentUser = $query->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim(mb_strtolower($_POST['name']));
    $date = $_POST['date'];
    $price = (int)trim($_POST['price']);
    $description = trim(mb_strtolower($_POST['description']));
    $capacity = (int)trim($_POST['capacity']);
    $scene = $_POST['scene'];
    $image = trim(mb_strtolower($_POST['image']));
    $status = $_POST['status'];
    $categories = $_POST['categories_id'];

    try {
        $query = $pdo->prepare(
            "UPDATE events SET 
                name = :name, 
                date = :date,  
                price = :price, 
                description = :description, 
                capacity = :capacity, 
                scene = :scene,
                image = :image,
                status = :status, 
                categories_id = :categories_id
                WHERE id = :id"
        );
        $query->execute([
            ':name' => $name,
            ':date' => $date,
            ':price' => $price,
            ':description' => $description,
            ':capacity' => $capacity,
            ':scene' => $scene,
            ':image' => $image,
            ':status' => $status,
            ':categories_id' => $categories,
            ':id' => $id
        ]);

        header('Location: dash-admin-events.php');
        exit;
    } catch (PDOException $e) {
        $error = "Erreur : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../assets/css/header.css" rel="stylesheet">
    <link href="../assets/css/footer.css" rel="stylesheet">
    <link href="../assets/css/variables.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
    <!-- <link href="../assets/css/login.css" rel="stylesheet"> -->
    <!-- <link href="../assets/css/dashboard.css" rel="stylesheet"> -->
    <link href="https://cdn.boxicons.com/3.0.8/fonts/basic/boxicons.min.css" rel="stylesheet">
    <title>MNS Football Club - évènement</title>
</head>

<body>
    <?php require_once('../includes/header.php') ?>
    <main class="event-wrap">
        <div class="event">
            <h3>Modifier mon mot de passe</h3>
            <p>-- Veuillez modifier votre mot de passe en respectant les règles suivantes --</p>
            <ul>
                <li>8 caractères minimum</li>
                <li>1 caractère spécial minimum [#@!?$%&]</li>
                <li>1 majuscule minimum</li>
                <li>1 numérique minimum</li>
            </ul>
            <form action="password.php" method="post"
                id="id-form" class="form">
                <input type="hidden" name="id" value="<?= $currentUser['id'] ?>">
                <div class="event-item">
                    <label for="password">Ancien mot de passe *</label>
                    <div>
                        <input type="password" id="password" name="password">
                    </div>
                </div>
                <div class="event-item">
                    <label for="password">Nouveau mot de passe *</label>
                    <div>
                        <input type="password" id="password" name="password">
                    </div>
                </div>
                <div class="event-item">
                    <label for="password">Confirmation nouveau mot de passe *</label>
                    <div>
                        <input type="password" id="pwd" name="price">
                    </div>
                </div>
                <div class="event-item">
                    <button type="submit" id="sub-btn">Valider</button>
                </div>
            </form>
            <?php if ($error): ?>
                <p class="error"><?= $error ?></p>
            <?php endif; ?>
        </div>
    </main>
    <?php require_once('../includes/footer.php') ?>
</body>

</html>