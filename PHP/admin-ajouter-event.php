<?php
////  Vérifications session + profil admin
////  Inclusion de bdd.php
//  Structure if/else sur REQUEST_METHOD
//  Si POST : INSERT INTO events + redirection
//  Si GET : afficher formulaire + récupérer catégories

//// Vérifications de session
//// Inclusion de bdd.php
// Structure if/else sur REQUEST_METHOD
// À l'intérieur du else : la requête SELECT pour récupérer les catégories
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SESSION['profil'] !== 'administrateur') {
    header('Location: index.php');
    exit;
}

$pdo = require_once('includes/bdd.php');
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $name = $_POST['name'];
        $date = $_POST['date'];
        $hour = $_POST['hour'];
        $price = (int)$_POST['price'];
        $description = $_POST['description'];
        $capacity = (int)$_POST['capacity'];
        $status = $_POST['status'];
        $categories = $_POST['categories'];

        $query = $pdo->prepare("INSERT INTO events (name, date, hour, price, description, capacity, status, categories_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $query->execute([$name, $date, $hour, $price, $description, $capacity, $status, $categories]);

        header('Location: admin-events.php');
        exit;
    } catch (PDOException $e) {
        $error = "Erreur : " . $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->prepare("SELECT * FROM categories");
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/variables.css">
    <link rel="stylesheet" href="assets/css/login.css">
    <link href="https://cdn.boxicons.com/3.0.8/fonts/basic/boxicons.min.css" rel="stylesheet">
    <script src="assets/js/script.js" defer></script>
    <title>Login</title>
</head>

<body>
    <header><!--ici le header --></header>
    <main>
        <h1>Tableau de bord "Administrateur"</h1>
        <h2>Ajouter un nouvel évènement</h2>
        <p> * = champs obligatoires</p>
        <form action="admin-ajouter-event.php" method="post"
            id="id-form" class="form">
            <div class="form-group">
                <label for="name">Name *</label>
                <div>
                    <input type="text" id="name" name="name" required>
                </div>
            </div>
            <div class="form-group">
                <label for="date">Date * <sup>*</sup></label>
                <div>
                    <input type="date" id="date" name="date" required>
                </div>
            </div>
            <div class="form-group">
                <label for="hour">Heure *</label>
                <div>
                    <input type="time" id="hour" name="hour" required>
                </div>
            </div>
            <div class="form-group">
                <label for="price">Tarif *</label>
                <div>
                    <input type="number" id="price" name="price" required>
                </div>
            </div>
            <div class="form-group">
                <label for="description">Description *</label>
                <div>
                    <input type="text" id="description" name="description" required>
                </div>
            </div>
            <div class="form-group">
                <label for="capacity">capacité *</label>
                <div>
                    <input type="number" id="capacity" name="capacity" required>
                </div>
            </div>
            <div class="form-group">
                <label for="status">Statut</label>
                <div>
                    <select name="status" id="status">
                        <option value="">Choisir</option>
                        <option value="a_venir">A venir</option>
                        <option value="confirme">Confirmé</option>
                        <option value="reporte">Reporté</option>
                        <option value="annule">Annulé</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="categories">Catégorie</label>
                <div>
                    <select name="categories" id="categories">
                        <option value="">Choisir</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                            <!-- On stock dans value $category['id'] et pas ['name'] => C'est une clé étrangère (int) dans la table qu'on insert pas une string. -->
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" id="sub-btn">Créer l'événement</button>
            </div>
        </form>
        <?php if ($error): ?>
            <p style="color:red"><?= $error ?></p>
        <?php endif; ?>
    </main>
    <footer>
        <?php //require_once(footer.php)
        ?>
    </footer>
</body>

</html>