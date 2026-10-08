<?php
session_start();
$pdo = require_once('../includes/bdd.php');
$error = null;

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

if ($_SESSION['profile'] !== 'administrateur') {
    header('Location: index.php');
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
        header('Location: admin-users.php');
        exit;
    }
    $query = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $query->execute([$id]);
    $currentUser = $query->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim(mb_strtolower($_POST['name']));
    $first_name = trim(mb_strtolower($_POST['first_name']));
    $email = trim(mb_strtolower($_POST['email']));
    $phone = trim($_POST['phone']);
    $birthday = $_POST['birthday'];
    $address = trim(mb_strtolower($_POST['address']));
    $postal = trim($_POST['postal']);
    $city = trim(mb_strtolower($_POST['city']));
    $profile = $_POST['profile'];
    $is_active = $_POST['is_active'];

    try {
        $query = $pdo->prepare(
            "UPDATE users SET 
                name = :name, 
                first_name = :first_name,  
                email = :email, 
                phone = :phone, 
                birthday = :birthday, 
                address = :address,
                postal = :postal,
                city = :city, 
                profile = :profile,
                is_active = :is_active
                WHERE id = :id"
        );
        $query->execute([
            ':name' => $name,
            ':first_name' => $first_name,
            ':email' => $email,
            ':phone' => $phone,
            ':birthday' => $birthday,
            ':address' => $address,
            ':postal' => $postal,
            ':city' => $city,
            ':profile' => $profile,
            ':is_active' => $is_active,
            ':id' => $id
        ]);
        header('Location: admin-users.php');
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
    <link href="https://cdn.boxicons.com/3.0.8/fonts/basic/boxicons.min.css" rel="stylesheet">
    <link href="../assets/images/mon_logo.png" rel="icon" type="image/png">
    <script src="../assets/js/header.js" defer></script>
    <title>MNS FC - Abonnés</title>
</head>

<body>
    <?php require_once('../includes/header.php') ?>
    <main class="event-wrap">
        <div class="event">
            <h3>Modifier un adhérent</h3>
            <p>Veuillez modifier les informations de l'adhérent</p>
            <form action="admin-modify-user.php" method="post"
                id="id-form" class="form">
                <input type="hidden" name="id" value="<?= $currentUser['id'] ?>">
                <div class="event-item">
                    <label for="name">Nom</label>
                    <div>
                        <input type="text" id="name" name="name" value="<?= strtoupper(htmlspecialchars($currentUser['name'])) ?>">
                    </div>
                </div>
                <div class="event-item">
                    <label for="first_name">Prénom</label>
                    <div>
                        <input type="text" id="first_name" name="first_name" value="<?= ucfirst(htmlspecialchars($currentUser['first_name'])) ?>">
                    </div>
                </div>
                <div class="event-item">
                    <label for="email">Email</label>
                    <div>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($currentUser['email']) ?>">
                    </div>
                </div>
                <div class="event-item">
                    <label for="phone">Téléphone</label>
                    <div>
                        <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($currentUser['phone']) ?>">
                    </div>
                </div>
                <div class="event-item">
                    <label for="birthday">Anniversaire</label>
                    <div>
                        <input type="date" id="birthday" name="birthday" value="<?= $currentUser['birthday'] ?>">
                    </div>
                </div>
                <div class="event-item">
                    <label for="address">Adresse</label>
                    <div>
                        <input type="text" id="address" name="address" value="<?= htmlspecialchars($currentUser['address']) ?>">
                    </div>
                </div>
                <div class="event-item">
                    <label for="postal">Code Postal</label>
                    <div>
                        <input type="text" id="postal" name="postal" value="<?= htmlspecialchars($currentUser['postal']) ?>">
                    </div>
                </div>
                <div class="event-item">
                    <label for="city">Ville</label>
                    <div>
                        <input type="text" id="city" name="city" value="<?= htmlspecialchars($currentUser['city']) ?>">
                    </div>
                </div>
                <div class="event-item">
                    <label for="profile">Profil</label>
                    <div>
                        <select name="profile" id="profile">
                            <option value="">Choisir</option>
                            <option value="administrateur" <?= ($currentUser['profile']) === 'administrateur' ? 'selected' : '' ?>>Administrateur</option>
                            <option value="service" <?= htmlspecialchars($currentUser['profile']) === 'service' ? 'selected' : '' ?>>Service</option>
                            <option value="abonne" <?= htmlspecialchars($currentUser['profile']) === 'abonne' ? 'selected' : '' ?>>Abonné</option>
                        </select>
                    </div>
                </div>
                <div class="event-item">
                    <label for="is_active">Actif</label>
                    <div>
                        <select name="is_active" id="is_active">
                            <option value="">Choisir</option>
                            <option value="1" <?= $currentUser['is_active'] == '1' ? 'selected' : '' ?>>Actif</option>
                            <option value="0" <?= $currentUser['is_active'] == '0' ? 'selected' : '' ?>>Inactif</option>
                        </select>
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