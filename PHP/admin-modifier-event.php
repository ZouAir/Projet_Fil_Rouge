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

    $pdo = require_once('bdd.php');
    $error = null;

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (!isset($_GET['id'])) {
            header('Location: admin-events.php');
            exit;
        }

        $status = $_GET['status'];
        $categories = $_GET['categories_id'];
        $stmt = $pdo->prepare("SELECT * FROM categories");
        $stmt->execute();
        $categories = $stmt->fecthAll(PDO::FETCH_ASSOC);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'];
        $date = $_POST['date'];
        $hour = $_POST['hour'];
        $price = (int)$_POST['price'];
        $description = $_POST['description'];
        $capacity = (int)$_POST['capacity'];
        $status = $_POST['status'];
        $categories = $_POST['categories_id'];

        try {
            $query = $pdo->prepare("INSERT INTO events ('name, date, hour, price, description, capacity, status, categories_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $query->execute([$name, $date, $hour, $price, $description, $capacity, $status, $categories]);

            header('Location:admin-events.php');
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
     <title>MNS Football Club</title>
 </head>

 <body>
     <header><!--ici le header --></header>
     <main>
         <h1>Tableau de bord "Administrateur"</h1>
         <h2>Modifier un évènement</h2>
         <form action="admin-modifier-event.php" method="post"
             id="id-form" class="form">
             <div class="form-group">
                 <label for="name">Name</label>
                 <div>
                     <input type="text" id="name" name="name" value="<?= htmlspecialchars($_GET['name']) ?>">
                 </div>
             </div>
             <div class="form-group">
                 <label for="date">Date</label>
                 <div>
                     <input type="date" id="date" name="date" value="<?= htmlspecialchars($_GET['date']) ?>">
                 </div>
             </div>
             <div class="form-group">
                 <label for="hour">Heure</label>
                 <div>
                     <input type="time" id="hour" name="hour" value="<?= htmlspecialchars($_GET['hour']) ?>">
                 </div>
             </div>
             <div class="form-group">
                 <label for="price">Tarif</label>
                 <div>
                     <input type="number" id="price" name="price" value="<?= htmlspecialchars($_GET['price']) ?>">
                 </div>
             </div>
             <div class="form-group">
                 <label for="description">Description</label>
                 <div>
                     <input type="text" id="description" name="description" value="<?= htmlspecialchars($_GET['description']) ?>">
                 </div>
             </div>
             <div class="form-group">
                 <label for="capacity">capacité</label>
                 <div>
                     <input type="number" id="capacity" name="capacity" value="<?= htmlspecialchars($_GET['capacity']) ?>">
                 </div>
             </div>
             <div class="form-group">
                 <label for="status">Statut</label>
                 <div>
                     <select name="status" id="status">
                         <option value="">Choisir</option>
                         <option value="a_venir" <?= $status === 'a_venir' ? 'selected' : '' ?>>A venir</option>
                         <option value="confirme" <?= $status === 'confirme' ? 'selected' : '' ?>>Confirmé</option>
                         <option value="reporte" <?= $status === 'reporte' ? 'selected' : '' ?>>Reporté</option>
                         <option value="annule" <?= $status === 'annule' ? 'selected' : '' ?>>Annulé</option>
                     </select>
                 </div>
             </div>
             <div class="form-group">
                 <label for="categories">Catégorie</label>
                 <div>
                     <select name="categories" id="categories">
                         <option value="">Choisir</option>
                         <?php foreach ($categories as $category): ?>
                             <option value="<?= $category['id'] ?>"
                                 <?= $category['id'] ? 'selected' : '' ?>>
                                 <?= $category['name'] ?>
                             </option>
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