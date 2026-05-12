<?php
// Démarre la session
// Inclut bdd.php
// Vérifie si le formulaire est soumis en POST
// Récupère les variables depuis $_POST
// Vérifie que les 2 mots de passe correspondent
// Hashe le mot de passe
// Insère l'utilisateur en BDD
// Redirige vers login.php
session_start();



?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.boxicons.com/3.0.8/fonts/basic/boxicons.min.css" rel="stylesheet">
    <script src="js/script.js" defer></script>
    <title>MNS Football Club</title>
</head>
<!-- Ceci est un commentaire -->

<body>
    <main>
        <section class="container">
            <h1>Inscription</h1>
            <p>Veuillez renseigner les informations suivantes afin de créer votre profil abonné du MNS Football Club.
            </p>
            <form action="inscription.php" method="post" id="id-form" class="form">
                <div> * champs obligatoires</div>
                <div class="form-group">
                    <label for="lastname">Name *</label>
                    <div>
                        <input type="text" id="lastname" name="lastname" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="firstname">Firstname <sup>*</sup></label>
                    <div>
                        <input type="text" id="firstname" name="firstname" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="email">Email *</label>
                    <div>
                        <input type="email" id="email" name="email" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="pwd">Mot de passe *</label>
                    <div class="input-with-button">
                        <input type="password" id="pwd" name="pwd" required>
                        <button type="button" id="pwd-show">
                            <i class="bx bx-eye" aria-label="Afficher le mot de passe"></i>
                            <i class="bx bx-eye-slash" aria-label="Masquer le mot de passe"></i>
                        </button>
                        <div>
                            <p>Le mot de passe doit respecter les règles suivantes</p>
                            <ul>
                                <li id="pwd-criteria-length">8 caractères minimum</li>
                                <li id="pwd-criteria-special">1 caractère spécial mini [#@!?$%&]</li>
                                <li id="pwd-criteria-uppercase">1 majuscule mini</li>
                                <li id="pwd-criteria-numeric">1 numérique mini</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="pwd-confirm">Confirmation*</label>
                    <div class="input-with-button">
                        <input type="password" id="pwd-confirm" name="pwd-confirm" required>
                        <button type="button" id="pwd-confirm-show">
                            <i class="bx bx-eye" aria-label="Afficher le mot de passe"></i>
                            <i class="bx bx-eye-slash" aria-label="Masquer le mot de passe"></i>
                        </button>
                    </div>
                </div>
                <div class="form-group">
                    <label for="phone">Telephone *</label>
                    <div>
                        <input type="text" id="phone" name="phone" required>
                    </div>
                </div>
                <div class="form-group">
                    <input type="submit" id="sub-btn" value="Valider">
                </div>
            </form>
        </section>
    </main>
</body>