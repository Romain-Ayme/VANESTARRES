-- --------------------------------------------------------
-- Hôte :                        mysql-romain-ayme.alwaysdata.net
-- Version du serveur:           10.5.8-MariaDB - MariaDB Server
-- SE du serveur:                Linux
-- HeidiSQL Version:             10.2.0.5599
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Listage de la structure de la base pour romain-ayme_vanestarre
CREATE DATABASE IF NOT EXISTS `romain-ayme_vanestarre` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `romain-ayme_vanestarre`;

-- Listage de la structure de la table romain-ayme_vanestarre. messages
CREATE TABLE IF NOT EXISTS `messages` (
  `ID_MESSAGE` int(11) NOT NULL AUTO_INCREMENT,
  `ID_USER` int(11) NOT NULL,
  `DATE_MESS` datetime NOT NULL DEFAULT curtime(),
  `MESSAGE` varchar(150) COLLATE latin1_general_ci NOT NULL,
  `IMG` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `NB_AVANT_DON` int(11) DEFAULT NULL,
  `DONNE` enum('Y','N') COLLATE latin1_general_ci NOT NULL DEFAULT 'N',
  `DON_USER` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID_MESSAGE`),
  KEY `ID_USER` (`ID_USER`),
  CONSTRAINT `messages.ID_USER` FOREIGN KEY (`ID_USER`) REFERENCES `users` (`ID_USER`)
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci ROW_FORMAT=COMPACT COMMENT='Liste des messages';

-- Listage des données de la table romain-ayme_vanestarre.messages : ~10 rows (environ)
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
INSERT INTO `messages` (`ID_MESSAGE`, `ID_USER`, `DATE_MESS`, `MESSAGE`, `IMG`, `NB_AVANT_DON`, `DONNE`, `DON_USER`) VALUES
	(58, 47, '2021-01-28 23:27:19', 'Coucou les amis, bienvenue à tous ßcoucou ßfirst', '../../Public/msg/58/image.jpeg', 5, 'N', 0),
	(59, 47, '2021-01-28 23:29:26', 'regardez moi ce soleil !! ßsoleil ßwow', '../../Public/msg/59/image.png', 5, 'N', 0),
	(60, 47, '2021-01-28 23:29:56', 'j&#39;adooore ce site !!!!', NULL, 2, 'N', 0),
	(61, 47, '2021-01-28 23:30:29', 'le covid c&#39;est pas ouf ßcovid ßnul', NULL, 5, 'N', 0),
	(62, 47, '2021-01-28 23:32:41', 'tiens tiens, il y a beaucoup de vanestarre sur 1/2', NULL, 2, 'N', 0),
	(63, 47, '2021-01-28 23:33:06', 'internet en ce moment 2/2', NULL, 2, 'N', 0),
	(64, 47, '2021-01-28 23:33:38', 'Bon par contre la limite de 50 caractères pas ouf', '../../Public/msg/64/image.jpeg', 2, 'N', 0),
	(66, 47, '2021-01-28 23:36:37', 'j&#39;espere que les createurs du site aurons ß20', NULL, 4, 'N', 0),
	(67, 47, '2021-01-28 23:38:09', '= moi devant les maths ßmath ßnul ßchat', '../../Public/msg/67/image.png', 3, 'N', 0),
	(68, 47, '2021-01-28 23:40:12', 'bonne nuit ! ßlune ßwow ßdodo', '../../Public/msg/68/image.png', 2, 'N', 0);
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;

-- Listage de la structure de la table romain-ayme_vanestarre. messages_tags
CREATE TABLE IF NOT EXISTS `messages_tags` (
  `ID_TAG` int(11) NOT NULL,
  `ID_MESSAGE` int(11) NOT NULL,
  KEY `ID_TAG` (`ID_TAG`),
  KEY `ID_MESSAGE` (`ID_MESSAGE`),
  CONSTRAINT `messages_tags.ID_MESSAGE` FOREIGN KEY (`ID_MESSAGE`) REFERENCES `messages` (`ID_MESSAGE`),
  CONSTRAINT `messages_tags.ID_TAG` FOREIGN KEY (`ID_TAG`) REFERENCES `tags` (`ID_TAG`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Liens entre Message et tag';

-- Listage des données de la table romain-ayme_vanestarre.messages_tags : ~12 rows (environ)
/*!40000 ALTER TABLE `messages_tags` DISABLE KEYS */;
INSERT INTO `messages_tags` (`ID_TAG`, `ID_MESSAGE`) VALUES
	(20, 58),
	(21, 58),
	(22, 59),
	(23, 59),
	(24, 61),
	(25, 61),
	(27, 66),
	(28, 67),
	(25, 67),
	(29, 67),
	(30, 68),
	(23, 68),
	(31, 68);
/*!40000 ALTER TABLE `messages_tags` ENABLE KEYS */;

-- Listage de la structure de la table romain-ayme_vanestarre. notes
CREATE TABLE IF NOT EXISTS `notes` (
  `ID_NOTE` int(11) NOT NULL AUTO_INCREMENT,
  `ID_MESSAGE` int(11) NOT NULL,
  `ID_USER` int(11) NOT NULL,
  `NOTE` enum('L','C','T','S') NOT NULL,
  PRIMARY KEY (`ID_NOTE`),
  KEY `ID_MESSAGE` (`ID_MESSAGE`),
  KEY `ID_USER` (`ID_USER`),
  CONSTRAINT `notes.ID_MESSAGE` FOREIGN KEY (`ID_MESSAGE`) REFERENCES `messages` (`ID_MESSAGE`),
  CONSTRAINT `notes.ID_USER` FOREIGN KEY (`ID_USER`) REFERENCES `users` (`ID_USER`)
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=utf8mb4 COMMENT='note par message et par utilisateur';

-- Listage des données de la table romain-ayme_vanestarre.notes : ~12 rows (environ)
/*!40000 ALTER TABLE `notes` DISABLE KEYS */;
INSERT INTO `notes` (`ID_NOTE`, `ID_MESSAGE`, `ID_USER`, `NOTE`) VALUES
	(62, 67, 52, 'C'),
	(63, 66, 52, 'L'),
	(64, 60, 52, 'T'),
	(65, 59, 52, 'S'),
	(66, 58, 52, 'L'),
	(67, 68, 51, 'L'),
	(68, 67, 51, 'L'),
	(69, 64, 51, 'C'),
	(70, 66, 51, 'T'),
	(71, 63, 51, 'L'),
	(72, 60, 51, 'C'),
	(73, 58, 51, 'S');
/*!40000 ALTER TABLE `notes` ENABLE KEYS */;

-- Listage de la structure de la table romain-ayme_vanestarre. parametres
CREATE TABLE IF NOT EXISTS `parametres` (
  `ID_PARAM` int(11) NOT NULL AUTO_INCREMENT,
  `N_MSG` int(11) NOT NULL DEFAULT 0,
  `N_MIN` int(11) NOT NULL DEFAULT 0,
  `N_MAX` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID_PARAM`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COMMENT='contient les paramètres pour le nombre de message à afficher par page + le nombre max et min qui va definir entre quelles valeurs le nombre n avant don va etre pour un message';

-- Listage des données de la table romain-ayme_vanestarre.parametres : ~0 rows (environ)
/*!40000 ALTER TABLE `parametres` DISABLE KEYS */;
INSERT INTO `parametres` (`ID_PARAM`, `N_MSG`, `N_MIN`, `N_MAX`) VALUES
	(1, 2, 2, 5);
/*!40000 ALTER TABLE `parametres` ENABLE KEYS */;

-- Listage de la structure de la table romain-ayme_vanestarre. tags
CREATE TABLE IF NOT EXISTS `tags` (
  `ID_TAG` int(11) NOT NULL AUTO_INCREMENT,
  `NOM_TAG` varchar(50) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID_TAG`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COMMENT='tout les types de tag';

-- Listage des données de la table romain-ayme_vanestarre.tags : ~11 rows (environ)
/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
INSERT INTO `tags` (`ID_TAG`, `NOM_TAG`) VALUES
	(20, 'coucou'),
	(21, 'first'),
	(22, 'soleil'),
	(23, 'wow'),
	(24, 'covid'),
	(25, 'nul'),
	(27, '20'),
	(28, 'math'),
	(29, 'chat'),
	(30, 'lune'),
	(31, 'dodo');
/*!40000 ALTER TABLE `tags` ENABLE KEYS */;

-- Listage de la structure de la table romain-ayme_vanestarre. users
CREATE TABLE IF NOT EXISTS `users` (
  `ID_USER` int(100) NOT NULL AUTO_INCREMENT,
  `EMAIL` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `PSWD` blob NOT NULL DEFAULT '0',
  `PSEUDO` varchar(10) COLLATE latin1_general_ci NOT NULL,
  `DATE_INS` datetime DEFAULT curtime(),
  `ROLE_USER` enum('SUPER','MEMBRE') COLLATE latin1_general_ci NOT NULL DEFAULT 'MEMBRE',
  `DELETED` enum('Y','N') COLLATE latin1_general_ci NOT NULL DEFAULT 'N',
  PRIMARY KEY (`ID_USER`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci ROW_FORMAT=COMPACT COMMENT='Table de tout les utilisateurs du site VANESTARRE';

-- Listage des données de la table romain-ayme_vanestarre.users : ~6 rows (environ)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`ID_USER`, `EMAIL`, `PSWD`, `PSEUDO`, `DATE_INS`, `ROLE_USER`, `DELETED`) VALUES
	(47, 'vane@star.re', _binary 0x2432792431302471744741514C7531664747674C4F61467773736D394F356A6A44347254664B7639774E463933616F4F68737235774963634F585947, 'Vanéstarre', '2021-01-28 23:24:09', 'SUPER', 'N'),
	(48, 'romain-ayme@alwaysdata.net', _binary 0x243279243130247447585A3945435A76306B43547A7A4F37316D727365574D526557796E4B5378524B544C6B79554A4F31416C7834586C583274386D, 'romain_aym', '2021-01-28 23:44:58', 'MEMBRE', 'N'),
	(49, 'parmenio.abz@gmail.com', _binary 0x3562616132343066323935326135366436393830, 'Lilian', '2021-01-28 23:47:59', 'MEMBRE', 'N'),
	(50, 'rauren@hotmail.fr', _binary 0x243279243130244E473058422E6F4D4433587234307134344E4665527565474538796D7066532E6931727659684A6850624E6C726246574A46797869, 'Rauren', '2021-01-28 23:48:52', 'MEMBRE', 'N'),
	(51, 'marlin.casalporte@hotmail.fr', _binary 0x24327924313024376B42734457585735314248734E576174366E5979757876586B313162586F64793951396E5A536338744163694246426C512E4943, 'marlin', '2021-01-28 23:50:38', 'MEMBRE', 'N'),
	(52, 'Jean-Hugues@gmail.com', _binary 0x2432792431302477576138584D4731574F4351742E4C456B5A554B5065385A74684A654E67697A4C617A58567949783471517A307556623765444F53, 'Jean-Hugue', '2021-01-28 23:51:31', 'MEMBRE', 'N');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
