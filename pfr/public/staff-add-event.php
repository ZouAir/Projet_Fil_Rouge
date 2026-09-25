<?php
session_start();
$pdo = require_once('../includes/bdd.php');

if (!isset($_SESSION['id']) || !in_array($_SESSION['profil'], ['administrateur', 'service'])) {
    header('Location: login.php');
    exit;
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $name = trim(mb_strtolower($_POST['name']));
        $date = $_POST['date'];
        $price = (int)trim($_POST['price']);
        $description = trim(mb_strtolower($_POST['description']));
        $team = $_POST['team'];
        $capacity = (int)trim($_POST['capacity']);
        $scene = $_POST['scene'];
        $image = trim(mb_strtolower($_POST['image']));
        $status = $_POST['status'];
        $categories = $_POST['categories'];

        $query = $pdo->prepare("INSERT INTO events (name, date, price, description, team, capacity, scene, image, status, categories_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $query->execute([$name, $date, $price, $description, $team, $capacity, $scene, $image, $status, $categories]);
        header('Location: staff-events.php');
        exit;
    } catch (PDOException $e) {
        $error = "Erreur : Veuillez renseigner toutes les informations de l'évènement";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $query = $pdo->prepare("SELECT * FROM categories");
    $query->execute();
    $categories = $query->fetchAll(PDO::FETCH_ASSOC);
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
    <link href="https://cdn.boxicons.com/3.0.8/fonts/basic/boxicons.min.css" rel="stylesheet">
    <link href="../assets/images/mon_logo.png" rel="icon" type="image/png">
    <script src="../assets/js/password.js" defer></script>
    <title>MNS Football Club - Évènement</title>
</head>

<body>
    <?php require_once('../includes/header.php') ?>
    <main class="event-wrap">
        <div class="event">
            <?php if ($error): ?>
                <p class="error"><?= $error ?></p>
            <?php endif; ?>
            <h3>Créer un évènement</h3>
            <p> * = champs obligatoires</p>
            <form action="staff-add-event.php" method="post"
                id="id-form" class="form">
                <div class="event-item">
                    <label for="name">Name *</label>
                    <div>
                        <input type="text" id="name" name="name" required>
                    </div>
                </div>
                <div class="event-item">
                    <label for="date">Date *</label>
                    <div>
                        <input type="date" id="date" name="date" required>
                    </div>
                </div>
                <div class="event-item">
                    <label for="price">Tarif *</label>
                    <div>
                        <input type="number" id="price" name="price" required>
                    </div>
                </div>
                <div class="event-item">
                    <label for="description">Description *</label>
                    <div>
                        <input type="text" id="description" name="description" required>
                    </div>
                </div>
                <div class="event-item">
                    <label for="capacity">capacité *</label>
                    <div>
                        <input type="number" id="capacity" name="capacity" required>
                    </div>
                </div>
                <div class="event-item">
                    <label for="image">Image</label>
                    <div>
                        <input type="text" id="image" name="image">
                    </div>
                </div>
                <div class="event-item">
                    <label for="team">Équipe</label>
                    <div>
                        <select name="team" id="team">
                            <option value="">Choisir</option>
                            <option value="U7">U7</option>
                            <option value="U9">U9</option>
                            <option value="U11">U11</option>
                            <option value="U13">U13</option>
                            <option value="U15">U15</option>
                            <option value="U17">U17</option>
                            <option value="U19">U19</option>
                            <option value="Seniors">Seniors</option>
                            <option value="Vétérans">Vétérans</option>
                            <option value="Féminines">Féminines</option>
                        </select>
                    </div>
                </div>
                <div class="event-item">
                    <label for="scene">Scène</label>
                    <div>
                        <select name="scene" id="scene">
                            <option value="">Choisir</option>
                            <option value="Tribune Nord">Tribune Nord</option>
                            <option value="Tribune Sud">Tribune Sud</option>
                            <option value="Tribune Est">Tribune Est</option>
                            <option value="Tribune Ouest">Tribune Ouest</option>
                            <option value="Club House">Club House</option>
                        </select>
                    </div>
                </div>
                <div class="event-item">
                    <label for="status">Statut</label>
                    <div>
                        <select name="status" id="status">
                            <option value="">Choisir</option>
                            <option value="À venir">À venir</option>
                            <option value="Confirmé">Confirmé</option>
                            <option value="Reporté">Reporté</option>
                            <option value="Annulé">Annulé</option>
                        </select>
                    </div>
                </div>
                <div class="event-item">
                    <label for="categories">Catégorie</label>
                    <div>
                        <select name="categories" id="categories">
                            <option value="">Choisir</option>
                            <?php foreach ($categories as $category) { ?>
                                <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="event-item">
                    <button type="submit" id="sub-btn">Créer l'événement</button>
                </div>
            </form>

        </div>
    </main>
    <?php require_once('../includes/footer.php') ?>
</body>

</html>