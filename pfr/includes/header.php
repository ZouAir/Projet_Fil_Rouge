<header>
    <div class="top">
        <img src="../assets/images/logo.png" alt="logo">
        <div class="title">
            <h1>MNS Football Club</h1>
        </div>
    </div>
    <div class="bottom">
        <nav>
            <ul>
                <li><a href="event.php">Évènements</a></li>
                <li><a href="#">Partenaires</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </nav>
        <div class="btn">
            <div class="burger">
                <button id="burger-btn" aria-label="Ouvrir le menu" aria-expanded="false">
                    <i class="bx bx-menu"></i>
                </button>
            </div>
            <?php
            if (!isset($_SESSION['email'])) {
            ?>
                <div class="cta">
                    <a href="login.php" id="login-btn">Connexion</a>
                </div>
            <?php
            } else {
            ?>
                <div class="cta">
                    <a href="logout.php" id="logout-btn">Déconnexion</a>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</header>