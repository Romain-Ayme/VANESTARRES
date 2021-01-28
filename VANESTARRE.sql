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
  `IMG` varchar(50) COLLATE latin1_general_ci DEFAULT NULL COMMENT 'On stocke juste le path',
  `NB_AVANT_DON` int(11) DEFAULT NULL,
  `DONNE` enum('Y','N') COLLATE latin1_general_ci NOT NULL DEFAULT 'N',
  `DON_USER` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID_MESSAGE`),
  KEY `ID_USER` (`ID_USER`),
  CONSTRAINT `messages.ID_USER` FOREIGN KEY (`ID_USER`) REFERENCES `users` (`ID_USER`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci ROW_FORMAT=COMPACT COMMENT='Liste des messages';

-- Les données exportées n'étaient pas sélectionnées.

-- Listage de la structure de la table romain-ayme_vanestarre. messages_tags
CREATE TABLE IF NOT EXISTS `messages_tags` (
  `ID_TAG` int(11) NOT NULL,
  `ID_MESSAGE` int(11) NOT NULL,
  KEY `ID_TAG` (`ID_TAG`),
  KEY `ID_MESSAGE` (`ID_MESSAGE`),
  CONSTRAINT `messages_tags.ID_MESSAGE` FOREIGN KEY (`ID_MESSAGE`) REFERENCES `messages` (`ID_MESSAGE`),
  CONSTRAINT `messages_tags.ID_TAG` FOREIGN KEY (`ID_TAG`) REFERENCES `tags` (`ID_TAG`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Liens entre Message et tag';

-- Les données exportées n'étaient pas sélectionnées.

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
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4 COMMENT='note par message et par utilisateur';

-- Les données exportées n'étaient pas sélectionnées.

-- Listage de la structure de la table romain-ayme_vanestarre. parametres
CREATE TABLE IF NOT EXISTS `parametres` (
  `ID_PARAM` int(11) NOT NULL AUTO_INCREMENT,
  `N_MSG` int(11) NOT NULL DEFAULT 0,
  `N_MIN` int(11) NOT NULL DEFAULT 0,
  `N_MAX` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID_PARAM`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COMMENT='contient les paramètres pour le nombre de message à afficher par page + le nombre max et min qui va definir entre quelles valeurs le nombre n avant don va etre pour un message';

-- Les données exportées n'étaient pas sélectionnées.

-- Listage de la structure de la table romain-ayme_vanestarre. tags
CREATE TABLE IF NOT EXISTS `tags` (
  `ID_TAG` int(11) NOT NULL AUTO_INCREMENT,
  `NOM_TAG` varchar(50) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID_TAG`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COMMENT='tout les types de tag';

-- Les données exportées n'étaient pas sélectionnées.

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
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci ROW_FORMAT=COMPACT COMMENT='Table de tout les utilisateurs du site VANESTARRE';

-- Les données exportées n'étaient pas sélectionnées.

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
