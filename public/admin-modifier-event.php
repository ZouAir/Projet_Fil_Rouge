 <?php
    ////  Vérifications session + profil admin
    ////  Inclusion de bdd.php
    ////  Récupérer l'ID de l'event via $_GET['id']
    ////  Vérifier que l'ID existe en BDD (sinon rediriger)
    ////  Si GET : afficher formulaire pré-rempli avec les données actuelles
    ////  Si POST : UPDATE l'event en BDD + redirection vers admin-events.php
    ////  Récupérer les catégories pour le select (comme dans ajouter)
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

    $pdo = require_once('includes/bdd.php');
    $error = null;

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (!isset($_GET['id'])) {
            header('Location: admin-events.php');
            exit;
        }
        $id = $_GET['id'];
        $stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
        $stmt->execute([$id]);
        $currentEvent = $stmt->fetch(PDO::FETCH_ASSOC);

        $query = $pdo->prepare("SELECT * FROM categories");
        $query->execute();
        $categories = $query->fetchAll(PDO::FETCH_ASSOC);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $date = $_POST['date'];
        $hour = $_POST['hour'];
        $price = (int)$_POST['price'];
        $description = $_POST['description'];
        $capacity = (int)$_POST['capacity'];
        $status = $_POST['status'];
        $categories = $_POST['categories_id'];

        try {
            $query = $pdo->prepare(
                "UPDATE events SET 
                name = :name, 
                date = :date, 
                hour = :hour, 
                price = :price, 
                description = :description, 
                capacity = :capacity, 
                status = :status, 
                categories_id = :categories_id
                WHERE id = :id"
            );
            $query->execute([
                ':name' => $name,
                ':date' => $date,
                ':hour' => $hour,
                ':price' => $price,
                ':description' => $description,
                ':capacity' => $capacity,
                ':status' => $status,
                ':categories_id' => $categories,
                ':id' => $id
            ]);

            header('Location: admin-events.php');
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
         <h2>Modifier un évènement</h2>
         <form action="admin-modifier-event.php" method="post"
             id="id-form" class="form">
             <input type="hidden" name="id" value="<?= $currentEvent['id'] ?>">
             <div class="form-group">
                 <label for="name">Name</label>
                 <div>
                     <input type="text" id="name" name="name" value="<?= htmlspecialchars($currentEvent['name']) ?>">
                 </div>
             </div>
             <div class="form-group">
                 <label for="date">Date</label>
                 <div>
                     <input type="date" id="date" name="date" value="<?= htmlspecialchars($currentEvent['date']) ?>">
                 </div>
             </div>
             <div class="form-group">
                 <label for="hour">Heure</label>
                 <div>
                     <input type="time" id="hour" name="hour" value="<?= htmlspecialchars($currentEvent['hour']) ?>">
                 </div>
             </div>
             <div class="form-group">
                 <label for="price">Tarif</label>
                 <div>
                     <input type="number" id="price" name="price" value="<?= htmlspecialchars($currentEvent['price']) ?>">
                 </div>
             </div>
             <div class="form-group">
                 <label for="description">Description</label>
                 <div>
                     <input type="text" id="description" name="description" value="<?= htmlspecialchars($currentEvent['description']) ?>">
                 </div>
             </div>
             <div class="form-group">
                 <label for="capacity">capacité</label>
                 <div>
                     <input type="number" id="capacity" name="capacity" value="<?= htmlspecialchars($currentEvent['capacity']) ?>">
                 </div>
             </div>
             <div class="form-group">
                 <label for="status">Statut</label>
                 <div>
                     <select name="status" id="status">
                         <option value="">Choisir</option>
                         <option value="a_venir" <?= $currentEvent['status'] === 'a_venir' ? 'selected' : '' ?>>A venir</option>
                         <option value="confirme" <?= $currentEvent['status'] === 'confirme' ? 'selected' : '' ?>>Confirmé</option>
                         <option value="reporte" <?= $currentEvent['status'] === 'reporte' ? 'selected' : '' ?>>Reporté</option>
                         <option value="annule" <?= $currentEvent['status'] === 'annule' ? 'selected' : '' ?>>Annulé</option>
                     </select>
                 </div>
             </div>
             <div class="form-group">
                 <label for="categories">Catégorie</label>
                 <div>
                     <select name="categories_id" id="categories">
                         <option value="">Choisir</option>
                         <?php foreach ($categories as $category): ?>
                             <option value="<?= $category['id'] ?>"
                                 <?= ($currentEvent['categories_id'] === $category['id']) ? 'selected' : '' ?>>
                                 <?= $category['name'] ?>
                             </option>
                         <?php endforeach; ?>
                     </select>
                 </div>
             </div>
             <div class="form-group">
                 <button type="submit" id="sub-btn">Modifier l'événement</button>
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