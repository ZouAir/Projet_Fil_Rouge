<?php
// // Démarre la session 
// // Inclut bdd.php
// // Vérifie si le formulaire est soumis en POST
// // Récupère les variables depuis $_POST
// // Vérifie que les 2 mots de passe correspondent
// // Hashe le mot de passe
// // Insère l'utilisateur en BDD
// // Redirige vers login.php
session_start();
$pdo = require_once('includes/bdd.php');

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name']) ?? '';
    $firstName = htmlspecialchars($_POST['firstname']) ?? '';
    $email = htmlspecialchars($_POST['email']) ?? '';
    $phone = htmlspecialchars($_POST['phone']) ?? '';

    if ($_POST['password'] !== $_POST['password-confirm']) {
        $error = "Les mots de passe ne correspondent pas";
    } else {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        if (empty($error)) {
            try {
                $query = $pdo->prepare("INSERT INTO users (name, firstname, email, password, phone, birthday, adress, postal, city) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $query->execute([$name, $firstName, $email, $password, $phone, '2000-01-01', 'adresse à modifier', '57000', 'Metz']);
                header('Location: login.php');
                exit;
            } catch (PDOException $e) {
                die("Erreur : " . $e->getMessage());
            }
        }
    }
}

?>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/variables.css">
    <link rel="stylesheet" href="assets/css/login.css">
    <link href="https://cdn.boxicons.com/3.0.8/fonts/basic/boxicons.min.css" rel="stylesheet">
    <script src="assets/js/script.js" defer></script>
    <title>Login</title>
</head>

<!-- Ceci est un commentaire -->

<body>
    <?php include_once('includes/header-login.php') ?>
    <main>
        <div class="container">
            <div class="connection">
                <p>inscription</p>
            </div>
            <div class="field">
                <form method="POST" action="inscription.php" class="form">
                    <div class="rules">* champs obligatoires</div>
                    <input type="text" id="name" name="name" placeholder="Nom *">
                    <input type="text" id="firstname" name="firstname" placeholder="Prénom *">
                    <input type="email" id="email" name="email" placeholder="Email *">
                    <input type="text" id="phone" name="phone" placeholder="Téléphone *">
                    <input type="password" name="password" id="password" placeholder="Mot de passe">
                    <div class="rules">
                        <p>Le mot de passe doit respecter les règles suivantes</p>
                        <ul>
                            <li id="password-criteria-length">8 caractères minimum</li>
                            <li id="password-criteria-special">1 caractère spécial minimum [#@!?$%&]</li>
                            <li id="password-criteria-uppercase">1 majuscule minimum</li>
                            <li id="password-criteria-numeric">1 numérique minimum</li>
                        </ul>
                    </div>
                    <input type="password" name="password-confirm" id="password-confirm"
                        placeholder="Confirmation mot de passe">
                    <button type="submit" id="sub-btn">Valider</button>
                </form>
            </div>
            <?php if ($error): ?>
                <p class="error"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
        </div>
    </main>
    <?php include_once('includes/footer.php') ?>
</body>

</html>