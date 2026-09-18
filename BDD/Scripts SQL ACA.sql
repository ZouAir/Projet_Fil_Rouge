-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Hôte : db
-- Généré le : ven. 18 sep. 2026 à 13:09
-- Version du serveur : 8.0.45
-- Version de PHP : 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `ACA`
--

-- --------------------------------------------------------

--
-- Structure de la table `athlets`
--

CREATE TABLE `athlets` (
  `id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`) VALUES
(1, 'Match', 'Rencontre sportive championnat ou coupe ou amicale'),
(2, 'Tournoi', 'Journée sportive sous forme de coupe avec un podium.'),
(3, 'Loisir', 'Manifestation sociale culturelle ou fête'),
(4, 'Réunion', 'Réunion d\'information ou d\'organisation d\'une action ou assemblée générale');

-- --------------------------------------------------------

--
-- Structure de la table `contacts`
--

CREATE TABLE `contacts` (
  `id` int NOT NULL,
  `users_id` int DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(150) DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `events`
--

CREATE TABLE `events` (
  `id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '../assets/images/events/default.jpg',
  `date` date NOT NULL,
  `price` decimal(10,0) NOT NULL,
  `scene` enum('Tribune Nord','Tribune Sud','Tribune Est','Tribune Ouest','Club House') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Tribune Sud',
  `capacity` int NOT NULL,
  `status` enum('À venir','Confirmé','Reporté','Annulé') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `categories_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `events`
--

INSERT INTO `events` (`id`, `name`, `description`, `image`, `date`, `price`, `scene`, `capacity`, `status`, `categories_id`) VALUES
(1, 'Bingo', 'Soirée festive pour les adhérents et leurs familles', '../assets/images/events/bingo.jpg', '2027-01-29', 8, 'Club House', 5, 'À venir', 3),
(2, 'soirée st sylvestre', 'soirée de fin d\'année', '../assets/images/events/soiree2.jpg', '2026-12-31', 10, 'Club House', 200, 'À venir', 3),
(3, 'Tournoi U13->Seniors', 'La fête du foot annuelle, moment de sport et de convivialité entre adhérents et invités.', '../assets/images/events/default.jpg', '2026-08-31', 10, 'Tribune Sud', 150, 'Confirmé', 1),
(5, 'rencontre amicale', 'un match de préparation avant le début de saison', '../assets/images/events/u15.jpg', '2026-10-13', 0, 'Tribune Sud', 3, 'À venir', 1),
(6, 'finale régionale', 'le grand match de la saison, le dénouement d\'une campagne épique pour nos champions', '../assets/images/events/default.jpg', '2026-09-10', 5, 'Tribune Nord', 200, 'À venir', 1),
(7, 'match u13', 'premiere journée de championnat u13 d1', '../assets/images/events/default.jpg', '2026-11-11', 5, 'Tribune Sud', 200, 'À venir', 1),
(8, 'assemblée générale', 'assemblée générale ordinaire pour l\'exercice 2026', '../assets/images/events/assemblee.jpg', '2026-12-21', 0, 'Club House', 100, 'Confirmé', 4),
(10, 'amical', 'rencontre amicale de préparation pour la coupe district u13', '../assets/images/events/u13.jpg', '2026-10-31', 0, 'Tribune Sud', 200, 'Confirmé', 1);

-- --------------------------------------------------------

--
-- Structure de la table `notation`
--

CREATE TABLE `notation` (
  `mvp_player` int DEFAULT NULL,
  `event_notation` enum('A','AA','AAA') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `events_id` int NOT NULL,
  `users_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `date` date NOT NULL,
  `status` enum('En attente','Validée','Annulée') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `seats` enum('1','2') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `places_id` int DEFAULT NULL,
  `events_id` int NOT NULL,
  `users_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `orders`
--

INSERT INTO `orders` (`id`, `date`, `status`, `seats`, `places_id`, `events_id`, `users_id`) VALUES
(1, '2026-07-27', 'Validée', '2', NULL, 1, 1),
(3, '2026-10-13', 'En attente', '2', NULL, 2, 1),
(4, '2026-06-05', 'Validée', '2', NULL, 5, 1),
(8, '2026-08-25', 'En attente', '2', NULL, 3, 2),
(10, '2026-08-25', 'En attente', '2', NULL, 2, 2),
(11, '2026-08-25', 'Validée', '1', NULL, 3, 1),
(12, '2026-08-25', 'En attente', '2', NULL, 2, 9),
(14, '2026-08-25', 'En attente', '1', NULL, 2, 7),
(15, '2026-08-27', 'Validée', '2', NULL, 3, 7),
(16, '2026-08-27', 'Validée', '2', NULL, 6, 7),
(17, '2026-08-27', 'Validée', '1', NULL, 6, 7),
(18, '2026-08-27', 'Validée', '2', NULL, 6, 7),
(19, '2026-08-28', 'En attente', '2', NULL, 6, 8),
(20, '2026-08-28', 'En attente', '1', NULL, 3, 8),
(21, '2026-09-01', 'En attente', '1', NULL, 6, 9),
(24, '2026-09-01', 'En attente', '1', NULL, 6, 9),
(25, '2026-09-01', 'En attente', '2', NULL, 7, 9),
(26, '2026-09-01', 'En attente', '2', NULL, 8, 1),
(28, '2026-09-08', 'En attente', '2', NULL, 1, 8),
(29, '2026-09-08', 'En attente', '1', NULL, 1, 7),
(30, '2026-09-09', 'En attente', '2', NULL, 7, 7),
(31, '2026-09-09', 'En attente', '2', NULL, 8, 7),
(32, '2026-09-09', 'En attente', '2', NULL, 3, 7),
(33, '2026-09-17', 'En attente', '2', NULL, 10, 9),
(34, '2026-09-17', 'En attente', '2', NULL, 7, 1),
(36, '2026-09-17', 'En attente', '2', NULL, 7, 8),
(37, '2026-09-17', 'En attente', '2', NULL, 2, 8);

-- --------------------------------------------------------

--
-- Structure de la table `payments`
--

CREATE TABLE `payments` (
  `id` int NOT NULL,
  `date` date NOT NULL,
  `method` enum('cash','cheque','virement') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('en_cours','valide','annule') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `orders_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `places`
--

CREATE TABLE `places` (
  `id` int NOT NULL,
  `number` tinyint NOT NULL,
  `stands` enum('nord','sud','est','ouest') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `level` enum('haut','milieu','bas') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `birthday` date NOT NULL,
  `adress` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `postal` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('Fermé','Privé','Public') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Public',
  `profil` enum('administrateur','service','abonne') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'abonne',
  `is_actif` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `first_name`, `email`, `password`, `phone`, `birthday`, `adress`, `postal`, `city`, `status`, `profil`, `is_actif`) VALUES
(1, 'reghai', 'zouhair', 'reghaizouhair@gmail.com', '$2y$12$IpkoWjPlSUUv3jXM0Kox3.eg633QVlTczmb2FUHEGcCJC.iJa1VUW', '0621981370', '1981-09-10', '15 rue des oblats', '57685', 'augny', 'Public', 'service', 1),
(2, 'admin', 'admin', 'test@test.com', '$2y$12$69hZqE2wWUb/pk1TbFZr4OxRsks7PoMExUrfmSdtuBHBwKkwEmpry', '123456789', '1959-02-21', '61 la siesta', '57000', 'metz', 'Public', 'administrateur', 0),
(7, 'reghai', 'ali', 'reghaiali@gmail.com', '$2y$12$5YVufS4szogdWYYnnFP/ye1t35MDLCr15.24qYcO6Q8qsxs.pmPZO', '0769021113', '2011-06-05', '15 rue des oblats', '57685', 'augny', 'Public', 'abonne', 1),
(8, 'reghai', 'lilia', 'reghaililia@gmail.com', '$2y$12$4q9HgXkgZFp5YvY8dWgYqOgoDCXxkoTRK3zFoeCvmhEnnK1x1/VaW', '0684585345', '2014-06-28', '15 rue des oblats', '57685', 'augny', 'Privé', 'abonne', 1),
(9, 'reghai', 'zakaria', 'reghaizakaria@gmail.com', '$2y$12$4wHgZ4mb4.w1cGu0EEpyCew8g3x5Df5ewOuOauOOqtUTTxO9X9cZe', '0743535271', '2014-08-31', '15 rue des oblats', '57685', 'augny', 'Public', 'abonne', 1),
(14, 'chat', 'fliflou', 'fliflou@email.com', '$2y$12$zSe7q7lDzR57vSvVLijm9ux3zB/AmsPM1xEGCD2TAfgRtSyY/C7h.', '0663033187', '2000-01-01', 'adresse à modifier', '11111', 'ville', 'Public', 'abonne', 1),
(15, 'souafi', 'kais', 'kais@gmail.com', '$2y$12$2lLS/.6RR21jyfNzC6q8Qu.SsJx37yLTOxkd9cAJb1kK3OvvfzZ7m', '0643160456', '2021-11-29', 'adresse à modifier', '11111', 'ville', 'Public', 'abonne', 1),
(16, 'reghai', 'abdou', 'abdou@email.com', '$2y$12$RtxBun3Z8O5v9H./QwDz.uUMdpvre4NjILB5B7vKh6mZfcw6IuFlS', '0011223344', '2000-01-01', 'adresse à modifier', '11111', 'ville', 'Public', 'abonne', 1),
(18, 'reg', 'nawal', 'nawal@email.com', '$2y$12$fpACNi84rBQcXaa4S2c.mOZvAi5rMZVfQZchn3xKenuq/BorqjmDy', '0022446688', '2000-01-01', 'adresse à modifier', '11111', 'ville', 'Public', 'abonne', 1),
(19, 'reghai', 'erminia', 'petreltitine@hotmail.com', '$2y$12$iIgxPvyNdseTXYQyAlP1V.OflJ8/1hUCTfYvxOdFEEMYlW5h8RBMu', '0614602498', '2000-01-01', 'Adresse à modifier', '11111', 'Ville', 'Public', 'abonne', 1),
(26, 'essalih', 'jilali', 'essalihjilali@email.com', '$2y$12$lmwFuwvUB8wbZ6Ih1cRzBubNuiOEVvBN0pPcuoq8WLOVJYO.jiNxa', '0123456799', '2000-01-01', 'adresse à modifier', '00000', 'ville', 'Public', 'abonne', 1),
(27, 'essalih', 'samir', 'essalihsamir@email.com', '$2y$12$EhEA4Jwxyq5F98oPlVHtueT/0VaSEaRVn8UVNTMTwCKi3hPqKQtGq', '0670930013', '2000-01-01', 'adresse à modifier', '00000', 'ville', 'Public', 'abonne', 1),
(28, 'essalih', 'rahim', 'essalihrahim@email.com', '$2y$12$33pYWFUPvSss7Lkc0Yol0uvvDJU5.m.lF/lZnilPhdQKipo4/mXLW', '0661043365', '2000-01-01', 'adresse à modifier', '00000', 'ville', 'Public', 'abonne', 1),
(29, 'essalih', 'najib', 'essalihnajib@email.com', '$2y$12$FzwtnXBBIA.QDc/sLu7F7ebpYt1LuAzTLvWSPF0EMkmdf9oYxx9hO', '0673489118', '2000-01-01', 'adresse à modifier', '00000', 'ville', 'Public', 'abonne', 1),
(30, 'rrr', 'eee', 'testtest@test.com', '$2y$12$Q.g.P2irSnli7avDnkS9UOxsv65ujLvDjC3HzMIgq/K/o1qOAY7/a', '0621987777', '2000-01-01', 'adresse à modifier', '00000', 'ville', 'Public', 'abonne', 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `athlets`
--
ALTER TABLE `athlets`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `events_ibfk_1` (`categories_id`);

--
-- Index pour la table `notation`
--
ALTER TABLE `notation`
  ADD PRIMARY KEY (`events_id`,`users_id`),
  ADD UNIQUE KEY `events_id` (`events_id`,`users_id`),
  ADD KEY `events_id_2` (`events_id`,`users_id`),
  ADD KEY `users_id` (`users_id`),
  ADD KEY `notation_ibfk_3` (`mvp_player`);

--
-- Index pour la table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_ibfk_2` (`users_id`),
  ADD KEY `orders_ibfk_1` (`events_id`),
  ADD KEY `orders_ibfk_3` (`places_id`);

--
-- Index pour la table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_id` (`orders_id`),
  ADD UNIQUE KEY `orders_id_2` (`orders_id`),
  ADD UNIQUE KEY `orders_id_3` (`orders_id`);

--
-- Index pour la table `places`
--
ALTER TABLE `places`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `athlets`
--
ALTER TABLE `athlets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `events`
--
ALTER TABLE `events`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT pour la table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `places`
--
ALTER TABLE `places`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`categories_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Contraintes pour la table `notation`
--
ALTER TABLE `notation`
  ADD CONSTRAINT `notation_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `notation_ibfk_2` FOREIGN KEY (`events_id`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `notation_ibfk_3` FOREIGN KEY (`mvp_player`) REFERENCES `athlets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`events_id`) REFERENCES `events` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`places_id`) REFERENCES `places` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Contraintes pour la table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`orders_id`) REFERENCES `orders` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
