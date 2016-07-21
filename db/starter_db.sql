# ************************************************************
# Sequel Pro SQL dump
# Version 4004
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: localhost (MySQL 5.5.38)
# Database: finance_system
# Generation Time: 2016-07-21 09:16:56 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table account
# ------------------------------------------------------------

DROP TABLE IF EXISTS `account`;

CREATE TABLE `account` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` text NOT NULL,
  `password` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `account` WRITE;
/*!40000 ALTER TABLE `account` DISABLE KEYS */;

INSERT INTO `account` (`id`, `username`, `password`)
VALUES
	(1,'test@inbox.com','12345');

/*!40000 ALTER TABLE `account` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table category
# ------------------------------------------------------------

DROP TABLE IF EXISTS `category`;

CREATE TABLE `category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `type` tinytext,
  `reporting_type` tinytext,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;

INSERT INTO `category` (`id`, `name`, `type`, `reporting_type`)
VALUES
	(1,'Разное','out','monthly'),
	(2,'Продукты','out','weekly'),
	(3,'Транспорт','out','monthly'),
	(4,'Развлечения','out','weekly'),
	(5,'Хозтовары','out','weekly'),
	(6,'Кафе','out','weekly'),
	(7,'Авто/мото','out','monthly'),
	(8,'Благотворительность','out','monthly'),
	(9,'Аренда связь','out','monthly'),
	(10,'Подарки','out','monthly'),
	(11,'Одежда, обувь','out','monthly'),
	(12,'Зарплата','in','monthly'),
	(13,'Дополнительный доход','in','monthly'),
	(14,'Авиабилеты и визы','out','yearly'),
	(15,'Техника и электроника','out','yearly'),
	(16,'Здоровье','out','monthly'),
	(17,'Образование','out','yearly');

/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table currency
# ------------------------------------------------------------

DROP TABLE IF EXISTS `currency`;

CREATE TABLE `currency` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `buying_rate` decimal(8,4) unsigned NOT NULL,
  `selling_rate` decimal(8,4) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `currency` WRITE;
/*!40000 ALTER TABLE `currency` DISABLE KEYS */;

INSERT INTO `currency` (`id`, `name`, `buying_rate`, `selling_rate`)
VALUES
	(1,'USD',1.0000,1.0000),
	(2,'UAH',25.5000,25.5000),
	(3,'EUR',0.0000,0.0000),
	(4,'RUR',0.0000,0.0000),
	(5,'LKR',0.0000,0.0000),
	(6,'THB',35.3600,35.3600),
	(7,'NPR',97.0000,97.0000),
	(8,'INR',65.0000,65.0000);

/*!40000 ALTER TABLE `currency` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table flow
# ------------------------------------------------------------

DROP TABLE IF EXISTS `flow`;

CREATE TABLE `flow` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `comment` text NOT NULL,
  `sum` decimal(7,2) unsigned NOT NULL,
  `currency_sum` decimal(7,2) unsigned NOT NULL,
  `currency_id` int(11) unsigned NOT NULL,
  `category_id` int(11) unsigned NOT NULL,
  `wallet_id` int(11) unsigned NOT NULL,
  `account_id` int(11) unsigned NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `period_id` int(11) NOT NULL,
  `ignore_in_reports` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `flow` WRITE;
/*!40000 ALTER TABLE `flow` DISABLE KEYS */;

INSERT INTO `flow` (`id`, `date`, `comment`, `sum`, `currency_sum`, `currency_id`, `category_id`, `wallet_id`, `account_id`, `type`, `period_id`, `ignore_in_reports`)
VALUES
	(1,'2014-01-01','IMAX: Хоббит 2',25.02,200.00,2,4,1,1,0,1,0);

/*!40000 ALTER TABLE `flow` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table period
# ------------------------------------------------------------

DROP TABLE IF EXISTS `period`;

CREATE TABLE `period` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` tinytext NOT NULL,
  `description` tinytext NOT NULL,
  `period_start` date DEFAULT NULL,
  `period_end` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table sessions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sessions`;

CREATE TABLE `sessions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table wallet
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wallet`;

CREATE TABLE `wallet` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `balance_usd` decimal(7,2) unsigned NOT NULL,
  `balance_currency` decimal(7,2) unsigned NOT NULL,
  `currency_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `wallet` WRITE;
/*!40000 ALTER TABLE `wallet` DISABLE KEYS */;

INSERT INTO `wallet` (`id`, `name`, `balance_usd`, `balance_currency`, `currency_id`)
VALUES
	(1,'Наличные',0.00,0.00,6);

/*!40000 ALTER TABLE `wallet` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
