 admin-events.php
 ////////////////
 Session start
 Vérifier connexion → sinon login.php
 Vérifier que $_SESSION['profil'] === 'administrateur' → sinon index.php
 Inclure bdd.php
 Récupérer tous les events depuis la BDD
 Afficher dans un tableau HTML avec boutons Modifier/Supprimer

 admin-ajouter-event.php - Créer un event (Create) :
 ///////////////////////////////////////////////////
 Même vérifications session + profil
 Formulaire : name, date, hour, price, description, capacity, status, categories_id
 Traitement POST → INSERT INTO events

 admin-modifier-event.php - Modifier (Update) :
 //////////////////////////////////////////////
 Récupérer $_GET['id']
 Afficher formulaire pré-rempli
 Traitement POST → UPDATE events

 admin-supprimer-event.php - Supprimer (Delete) :
 ////////////////////////////////////////////////
 Récupérer $_GET['id']
 DELETE FROM events WHERE id = ?
 Rediriger vers admin-events.php