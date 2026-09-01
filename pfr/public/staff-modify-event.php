<?php
session_start();
$pdo = require_once('../includes/bdd.php');
$error = null;

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

if (!in_array($_SESSION['profil'], ['administrateur', 'service'])) {
    header('Location: login.php');
    exit;
} else {
    if ($_SERVER['REQUEST_METHOD'] === "GET") {
        $id = $_GET['id'] ? (int)$_GET['id'] : null;
    } else {
        $id = $_POST['id'] ? (int)$_POST['id'] : null;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_GET['id'])) {
        header('Location: dash-staff-events.php');
        exit;
    }
    $query = $pdo->prepare("SELECT * FROM events WHERE id = ?");
    $query->execute([$id]);
    $currentEvent = $query->fetch(PDO::FETCH_ASSOC);

    $query = $pdo->prepare("SELECT * FROM categories");
    $query->execute();
    $categories = $query->fetchAll(PDO::FETCH_ASSOC);
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

        header('Location: dash-staff-events.php');
        exit;
    } catch (PDOException $e) {
        $error = "Erreur : La modification de l'évènement n'a pas pu aboutir";
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
            <h3>Modifier un évènement</h3>
            <p>Veuillez modifier les informations de l'évènement</p>
            <form action="admin-modify-event.php" method="post"
                id="id-form" class="form">
                <input type="hidden" name="id" value="<?= $currentEvent['id'] ?>">
                <div class="event-item">
                    <label for="name">Name</label>
                    <div>
                        <input type="text" id="name" name="name" value="<?= htmlspecialchars($currentEvent['name']) ?>">
                    </div>
                </div>
                <div class="event-item">
                    <label for="date">Date</label>
                    <div>
                        <input type="date" id="date" name="date" value="<?= htmlspecialchars($currentEvent['date']) ?>">
                    </div>
                </div>
                <div class="event-item">
                    <label for="price">Tarif</label>
                    <div>
                        <input type="number" id="price" name="price" value="<?= htmlspecialchars($currentEvent['price']) ?>">
                    </div>
                </div>
                <div class="event-item">
                    <label for="description">Description</label>
                    <div>
                        <input type="text" id="description" name="description" value="<?= htmlspecialchars($currentEvent['description']) ?>">
                    </div>
                </div>
                <div class="event-item">
                    <label for="capacity">Capacité</label>
                    <div>
                        <input type="number" id="capacity" name="capacity" value="<?= htmlspecialchars($currentEvent['capacity']) ?>">
                    </div>
                </div>
                <div class="event-item">
                    <label for="scene">Scène</label>
                    <div>
                        <select name="scene" id="scene">
                            <option value="">Choisir</option>
                            <option value="Tribune Nord" <?= htmlspecialchars($currentEvent['scene']) === 'Tribune Nord' ? 'selected' : '' ?>>Tribune Nord</option>
                            <option value="Tribune Sud" <?= htmlspecialchars($currentEvent['scene']) === 'Tribune Sud' ? 'selected' : '' ?>>Tribune Sud</option>
                            <option value="Tribune Est" <?= htmlspecialchars($currentEvent['scene']) === 'Tribune Est' ? 'selected' : '' ?>>Tribune Est</option>
                            <option value="Tribune Ouest" <?= htmlspecialchars($currentEvent['scene']) === 'Tribune Ouest' ? 'selected' : '' ?>>Tribune Ouest</option>
                            <option value="Club House" <?= htmlspecialchars($currentEvent['scene']) === 'Club House' ? 'selected' : '' ?>>Club House</option>
                        </select>
                    </div>
                </div>
                <div class="event-item">
                    <label for="image">Image</label>
                    <div>
                        <input type="text" id="image" name="image" value="<?= htmlspecialchars($currentEvent['image']) ?>">
                    </div>
                </div>
                <div class="event-item">
                    <label for="status">Statut</label>
                    <div>
                        <select name="status" id="status">
                            <option value="">Choisir</option>
                            <option value="À venir" <?= htmlspecialchars($currentEvent['status']) == 'À venir' ? 'selected' : '' ?>>À venir</option>
                            <option value="Confirmé" <?= htmlspecialchars($currentEvent['status']) == 'Confirmé' ? 'selected' : '' ?>>Confirmé</option>
                            <option value="Reporté" <?= htmlspecialchars($currentEvent['status']) == 'Reporté' ? 'selected' : '' ?>>Reporté</option>
                            <option value="Annulé" <?= htmlspecialchars($currentEvent['status']) == 'Annulé' ? 'selected' : '' ?>>Annulé</option>
                        </select>
                    </div>
                </div>
                <div class="event-item">
                    <label for="categories">Catégorie</label>
                    <div>
                        <select name="categories_id" id="categories">
                            <option value="">Choisir</option>
                            <?php foreach ($categories as $category) { ?>
                                <option value="<?= $category['id'] ?>"
                                    <?= ($currentEvent['categories_id'] == $category['id']) ? 'selected' : '' ?>>
                                    <?= $category['name'] ?>
                                </option>
                            <?php }; ?>
                        </select>
                    </div>
                </div>
                <div class="event-item">
                    <button type="submit" id="sub-btn">Valider</button>
                </div>
            </form>
            <?php if ($error): ?>
                <p style="color:red"><?= $error ?></p>
            <?php endif; ?>
        </div>
    </main>
    <?php require_once('../includes/footer.php') ?>
</body>

</html>