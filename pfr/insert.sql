INSERT INTO users (name, first_name, email, password, phone, birthday, adress, postal, city, profil, is_actif) 
VALUES ('REGHAI', 'Zouzou', 'reghaizouhair@mail.com', 'password123', '0612345678', '1982-01-15', '1 rue des jardins', '57685', 'Augny', 'abonne', 1);

INSERT INTO events (name, date, price, description, capacity, status, categories_id) 
VALUES ('Finale Régionale', '2026-06-05', 15, 'Un grand match', 200, 'confirme', 1);

INSERT INTO orders (date, status, number_of_places, events_id, users_id) 
VALUES ('2026-07-27', 'validée', 1, 1, 1);