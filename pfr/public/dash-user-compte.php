<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../assets/css/header.css" rel="stylesheet">
    <link href="../assets/css/footer.css" rel="stylesheet">
    <link href="../assets/css/variables.css" rel="stylesheet">
    <!-- <link href="../assets/css/style.css" rel="stylesheet"> -->
    <!-- <link href="../assets/css/login.css" rel="stylesheet"> -->
    <link href="../assets/css/dashboard.css" rel="stylesheet">
    <link href="https://cdn.boxicons.com/3.0.8/fonts/basic/boxicons.min.css" rel="stylesheet">
    <title>MNS Football Club - Dashboard</title>
</head>

<body>
    <?php include_once('../includes/header.php') ?>
    <main>
        <div class="container">
            <div class="dashboard">
                <div class="dash-sidebar">
                    <div>Navigation</div>
                    <ul>
                        <li><a href="index.php">Accueil</a></li>
                        <li><a href="dash-user-evenements.php">Évènements</a></li>
                        <li><a href="dash-user-reservations.php">Réservations</a></li>
                        <li><a href="#">Mes amis</a></li>
                        <li><a href="#">Mon mvp</a></li>
                    </ul>
                    <div>Compte</div>
                    <a href="dash-user-compte.php">Mon compte</a>
                    <a href="logout.php">Déconnexion</a>
                </div>
                <div class="dash-content">
                    <div class="dash-head">
                        <div>
                            <span>ZR</span>
                            <span>Zouhair REGHAI</span>
                        </div>
                    </div>
                    <div>
                        <form action="compte.php" method="POST">
                            <div class="form-item">
                                <input type="text" id="" name="" placeholder="Nom" required>
                            </div>
                            <div class="form-item">
                                <input type="text" id="" name="" placeholder="Prénom" required>
                            </div>
                            <div class="form-item">
                                <input type="email" id="" name="" placeholder="Email" required>
                            </div>
                            <div class="form-item">
                                <input type="password" id="" name="" placeholder="Mot de passe" required>
                            </div>
                            <div class="form-item">
                                <input type="tel" id="" name="" placeholder="Téléphone" required>
                            </div>
                            <div class="form-item">
                                <input type="date" id="" name="" placeholder="Date de naissance" required>
                            </div>
                            <div class="form-item">
                                <input type="text" id="" name="" placeholder="Adresse" required>
                            </div>
                            <div class="form-item city">
                                <input type="text" id="" name="" placeholder="CP" required>
                                <input type="text" id="" name="" placeholder="Ville" required>
                            </div>
                            <div class="form-item">
                                <select name="statut" id="statut" aria-label="Statut du compte">
                                    <option value="">Mon status</option>
                                    <option value="ferme">Fermé</option>
                                    <option value="prive">Privé</option>
                                    <option value="public">Public</option>
                                </select>
                            </div>
                            <div class="form-item">
                                <button class="submit" type="button">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                    <div class="cta">
                        <a href="#" class="count-change">Changer mon mdp</a>
                    </div>
                    <div class="cta">
                        <a href="#" class="count-delete">Supprimer mon compte</a>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php include_once('../includes/footer.php') ?>
</body>

</html>