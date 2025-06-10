-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : lun. 09 juin 2025 à 15:47
-- Version du serveur : 5.7.24
-- Version de PHP : 8.1.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `users`
--

-- --------------------------------------------------------

--
-- Structure de la table `size`
--

CREATE TABLE `size` (
  `id` int(11) NOT NULL,
  `size` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `size`
--

INSERT INTO `size` (`id`, `size`) VALUES
(1, 38),
(2, 39),
(3, 40),
(4, 41),
(5, 42),
(6, 43),
(7, 44);

-- --------------------------------------------------------

--
-- Structure de la table `stock`
--

CREATE TABLE `stock` (
  `id` int(2) NOT NULL,
  `nom` varchar(150) NOT NULL,
  `prix` float NOT NULL,
  `couleur` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `stock`
--

INSERT INTO `stock` (`id`, `nom`, `prix`, `couleur`, `image`) VALUES
(15, 'New Balance 550', 25.14, 'Blanc / Gris', 'assets/images/NB550.webp'),
(17, 'Nike Dunk Low Celtics', 15.16, NULL, 'assets/images/NKCELTICS.webp'),
(22, 'airj jordan', 45, 'Bleu', 'assets/images/sneaker_67dc2dbe6f896.png');

-- --------------------------------------------------------

--
-- Structure de la table `stock_size`
--

CREATE TABLE `stock_size` (
  `id` int(11) NOT NULL,
  `stock_id` int(11) DEFAULT NULL,
  `size_id` int(11) DEFAULT NULL,
  `amount` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `stock_size`
--

INSERT INTO `stock_size` (`id`, `stock_id`, `size_id`, `amount`) VALUES
(1, 15, 2, 6),
(3, 17, 2, 0),
(10, 22, 1, 48);

-- --------------------------------------------------------

--
-- Structure de la table `user_site`
--

CREATE TABLE `user_site` (
  `user_id` int(11) NOT NULL,
  `prenom` varchar(30) NOT NULL,
  `nom` varchar(30) NOT NULL,
  `email` varchar(256) NOT NULL,
  `password` varchar(128) NOT NULL,
  `Grade` varchar(20) NOT NULL DEFAULT 'client'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `user_site`
--

INSERT INTO `user_site` (`user_id`, `prenom`, `nom`, `email`, `password`, `Grade`) VALUES
(1, 'Badr mimouni', '', 'badr_mim@outlook.fr', 'devine', ''),
(37, 'admin', 'admin', 'admin@sneak.com', '$2y$10$.iTGSnwqNTigaGJ0qjjHgeyMtGAr51sBOlvTnQpsRXb6UVUxtF4AK', 'admin'),
(66, 'Mimouni', 'Badr-Eddine', 'badr@mail.com', 'badr', 'admin'),
(67, 'Mimouni', 'Badr-Eddine', 'badr1@mail.com', 'badr', 'admin'),
(68, 'test', 'test', 'test@mail.com', 'test', 'client'),
(69, 's', 'oden', 's@m.com', 'badr', 'client'),
(70, 'b', 'test', 'b@m.com', '$2y$10$NbdxPkTxwOPTh7Bk8anwnOirPbJrwl4p26lvQ2cIXqolF/ozh3GYK', 'client'),
(71, 'badr', 'admin', 'admin@admin.fr', '$2y$10$yGotgejnjxZ8qbTsgAbbHeFdqzMnxxucEhwPHfDnTLD4sHoheefjy', 'admin'),
(72, 'badrrr', 'b', 'admbad@mdr.fr', '$2y$10$SRZE1TeTibF/T9PFG334Heuj5q.Tl8gS7Z0YJHtPyrzmWNN1Rtfby', 'admin'),
(73, 'ara', 'testa', 'ar@m.com', '$2y$10$Jg9usStN6JR67XkSO82vqOK9/LxRnOuM01JYGcy5y5O.xywyd8W/6', 'admin'),
(74, 'badr', 'mim', 'test34@mail.com', '$2y$10$nXUUj1uXDd4EgP5gfImClurfE.LF/274YwWDhpdPC5kwBFbo9BRfG', 'admin'),
(76, 'badr', 'zzz', 'b@f.fr', '$2y$10$6Mls9b7Inq7U6ld8Xx0aUOXGybTqEyIpx0WT8GIXwM8/SSxBdTNsy', 'admin'),
(77, 'ba', 'dr', 'b@l.fr', '$2y$10$fWOSZDvKBwfu6l5jA.A4ZuTOc2qyGZk3HphPC5T06d8gHqBQp.6te', 'client');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `size`
--
ALTER TABLE `size`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `stock_size`
--
ALTER TABLE `stock_size`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_id` (`stock_id`),
  ADD KEY `size_id` (`size_id`);

--
-- Index pour la table `user_site`
--
ALTER TABLE `user_site`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `size`
--
ALTER TABLE `size`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `stock`
--
ALTER TABLE `stock`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT pour la table `stock_size`
--
ALTER TABLE `stock_size`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `user_site`
--
ALTER TABLE `user_site`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `stock_size`
--
ALTER TABLE `stock_size`
  ADD CONSTRAINT `stock_size_ibfk_1` FOREIGN KEY (`stock_id`) REFERENCES `stock` (`id`),
  ADD CONSTRAINT `stock_size_ibfk_2` FOREIGN KEY (`size_id`) REFERENCES `size` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
