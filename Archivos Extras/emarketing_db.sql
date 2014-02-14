-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 13-02-2014 a las 15:18:53
-- Versión del servidor: 5.6.12-log
-- Versión de PHP: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `emarketing_db`
--
CREATE DATABASE IF NOT EXISTS `emarketing_db` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `emarketing_db`;

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_counters_db`(IN `idDbase` int(11))
BEGIN
	DECLARE cnt INT;
	DECLARE activecnt INT;
	DECLARE unsubscribedcnt INT;
	DECLARE bouncedcnt INT;
	DECLARE spamcnt INT;
	DECLARE vnotfound INT DEFAULT 0;

	DECLARE CONTINUE HANDLER FOR NOT FOUND SET vnotfound = 1;
	
	START TRANSACTION;

	SELECT idDbase FROM dbase WHERE dbase.idDbase = idDbase FOR UPDATE;

	IF (vnotfound = 0) THEN
		SELECT COUNT(*),	
			SUM( IF(c.status != 0, IF(c.unsubscribed = 0, IF(c.bounced = 0, IF(c.spam = 0,1,0), 0),0),0)),
			SUM( IF(c.unsubscribed != 0, IF(c.bounced = 0, IF(c.spam = 0,1,0), 0),0)),
			SUM( IF(c.bounced != 0, IF(c.spam = 0,1,0),	0)), 
			SUM( IF(c.spam != 0,1,0)) INTO cnt, activecnt, unsubscribedcnt, bouncedcnt, spamcnt 
		FROM contact c
		WHERE c.idDbase = idDbase;

		IF (vnotfound = 0) THEN
			UPDATE dbase d SET d.Ctotal = cnt, d.Cactive = activecnt, d.Cunsubscribed = unsubscribedcnt, d.Cbounced = bouncedcnt, d.Cspam = spamcnt WHERE d.idDbase = idDbase;
		END IF;
	END IF;

	COMMIT;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_counters_list`(IN `idContactlist` int(11))
BEGIN
	DECLARE cnt INT;
	DECLARE activecnt INT;
	DECLARE unsubscribedcnt INT;
	DECLARE bouncedcnt INT;
	DECLARE spamcnt INT;
	DECLARE vnotfound INT DEFAULT 0;

	DECLARE CONTINUE HANDLER FOR NOT FOUND SET vnotfound = 1;
	
	START TRANSACTION;

	SELECT idContactlist FROM contactlist WHERE contactlist.idContactlist = idContactlist FOR UPDATE;

	IF (vnotfound = 0) THEN
		SELECT COUNT(*),	
			SUM( IF(c.status != 0, IF(c.unsubscribed = 0, IF(c.bounced = 0, IF(c.spam = 0,1,0), 0),0),0)),
			SUM( IF(c.unsubscribed != 0, IF(c.bounced = 0, IF(c.spam = 0,1,0), 0),0)),
			SUM( IF(c.bounced != 0, IF(c.spam = 0,1,0),	0)), 
			SUM( IF(c.spam != 0,1,0)) INTO cnt, activecnt, unsubscribedcnt, bouncedcnt, spamcnt 
		FROM contact c JOIN coxcl x ON (c.idContact = x.idContact)
		WHERE x.idContactlist = idContactlist;

		IF (vnotfound = 0) THEN
			UPDATE contactlist c SET c.Ctotal = cnt, c.Cactive = activecnt, c.Cunsubscribed = unsubscribedcnt, c.Cbounced = bouncedcnt, c.Cspam = spamcnt WHERE c.idContactlist = idContactlist;
		END IF;
	END IF;

	COMMIT;
END$$

--
-- Funciones
--
CREATE DEFINER=`root`@`localhost` FUNCTION `find_or_create_email`(email VARCHAR(80), domain VARCHAR(80), idAccount INT(11)) RETURNS int(11)
    READS SQL DATA
BEGIN
	DECLARE idForEmail INT;
	DECLARE idForDomain INT;
	DECLARE timevar INT;
	DECLARE vnotfound INT DEFAULT 0;
	
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET vnotfound = 1;
	
	SET timevar = (SELECT TO_SECONDS( NOW() ));
	SELECT e.idEmail INTO idForEmail FROM email e WHERE e.email = email;
	IF (vnotfound = 0) THEN
		RETURN idForEmail;
	END IF;
	SET vnotfound = 0;

	SELECT d.idDomain INTO idForDomain FROM domain d WHERE d.name = domain;
	IF (vnotfound = 1) THEN
		INSERT IGNORE INTO domain (name) VALUES (domain);
		SET idForDomain = LAST_INSERT_ID();
	END IF;
	
	INSERT IGNORE INTO email (idAccount, idDomain, email, bounced, spam, blocked, createdon, updatedon) VALUES (idAccount, idForDomain, email, 0, 0, 0, timevar, timevar);
	RETURN LAST_INSERT_ID();

END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `account`
--

CREATE TABLE IF NOT EXISTS `account` (
  `idAccount` int(11) NOT NULL AUTO_INCREMENT,
  `idUrlDomain` int(11) NOT NULL,
  `companyName` varchar(50) CHARACTER SET utf8 NOT NULL,
  `accountingMode` enum('Contacto','Envio') CHARACTER SET utf8 NOT NULL,
  `fileSpace` int(11) unsigned NOT NULL DEFAULT '0',
  `messageLimit` int(11) unsigned NOT NULL DEFAULT '0',
  `contactLimit` int(11) unsigned DEFAULT '0',
  `subscriptionMode` enum('Prepago','Pospago') CHARACTER SET utf8 NOT NULL,
  `createdon` int(11) DEFAULT NULL,
  `updatedon` int(11) DEFAULT NULL,
  PRIMARY KEY (`idAccount`),
  KEY `idUrlDomain` (`idUrlDomain`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `action`
--

CREATE TABLE IF NOT EXISTS `action` (
  `idAction` int(11) NOT NULL AUTO_INCREMENT,
  `idResource` int(11) NOT NULL,
  `action` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`idAction`),
  KEY `idResource` (`idResource`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=94 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `adminmsg`
--

CREATE TABLE IF NOT EXISTS `adminmsg` (
  `idAdminMsg` int(11) NOT NULL AUTO_INCREMENT,
  `type` enum('Recoverpass') NOT NULL,
  `subject` varchar(80) NOT NULL,
  `from` varchar(30) NOT NULL,
  `msg` text NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`idAdminMsg`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `allowed`
--

CREATE TABLE IF NOT EXISTS `allowed` (
  `idAllowed` int(11) NOT NULL AUTO_INCREMENT,
  `idRole` int(11) NOT NULL,
  `idAction` int(11) NOT NULL,
  PRIMARY KEY (`idAllowed`),
  KEY `idRole` (`idAction`),
  KEY `idAllow` (`idAction`),
  KEY `idRole_2` (`idRole`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=132 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asset`
--

CREATE TABLE IF NOT EXISTS `asset` (
  `idAsset` int(11) NOT NULL AUTO_INCREMENT,
  `idAccount` int(11) NOT NULL,
  `fileName` varchar(100) CHARACTER SET utf8 NOT NULL,
  `fileSize` int(20) NOT NULL,
  `dimensions` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `type` varchar(20) CHARACTER SET utf8 NOT NULL,
  `createdon` int(11) DEFAULT NULL,
  PRIMARY KEY (`idAsset`),
  KEY `idAccount` (`idAccount`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=192 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `blockedemail`
--

CREATE TABLE IF NOT EXISTS `blockedemail` (
  `idBlockedemail` int(11) NOT NULL AUTO_INCREMENT,
  `idEmail` int(11) NOT NULL,
  `blockedReason` varchar(100) CHARACTER SET utf8 NOT NULL,
  `blockedDate` int(11) DEFAULT NULL,
  PRIMARY KEY (`idBlockedemail`),
  KEY `idEmail` (`idEmail`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=33 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bouncedcode`
--

CREATE TABLE IF NOT EXISTS `bouncedcode` (
  `idBouncedCode` int(11) NOT NULL,
  `type` enum('hard','soft') NOT NULL,
  `description` tinytext NOT NULL,
  PRIMARY KEY (`idBouncedCode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contact`
--

CREATE TABLE IF NOT EXISTS `contact` (
  `idContact` int(10) NOT NULL AUTO_INCREMENT,
  `idDbase` int(11) NOT NULL,
  `idEmail` int(11) NOT NULL,
  `name` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `lastName` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `bounced` int(10) unsigned NOT NULL,
  `unsubscribed` int(10) unsigned NOT NULL,
  `spam` int(10) unsigned NOT NULL,
  `ipActivated` int(11) unsigned NOT NULL DEFAULT '0',
  `ipSubscribed` int(11) unsigned NOT NULL DEFAULT '0',
  `updatedon` int(11) DEFAULT NULL,
  `subscribedon` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL,
  `createdon` int(11) DEFAULT NULL,
  PRIMARY KEY (`idContact`),
  KEY `fk_Contact_Dbase1_idx` (`idDbase`),
  KEY `fk_Contact_Email1_idx` (`idEmail`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12815 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contactlist`
--

CREATE TABLE IF NOT EXISTS `contactlist` (
  `idContactlist` int(11) NOT NULL AUTO_INCREMENT,
  `idDbase` int(11) NOT NULL,
  `name` varchar(60) NOT NULL,
  `description` varchar(100) DEFAULT 'Sin Descripcion',
  `Ctotal` int(11) unsigned DEFAULT '0',
  `Cunsubscribed` int(11) unsigned DEFAULT '0',
  `Cactive` int(11) unsigned DEFAULT '0',
  `Cspam` int(11) unsigned DEFAULT '0',
  `Cbounced` int(11) unsigned DEFAULT '0',
  `createdon` int(11) DEFAULT NULL,
  `updatedon` int(11) DEFAULT NULL,
  PRIMARY KEY (`idContactlist`),
  KEY `idDbase` (`idDbase`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=50 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coxcl`
--

CREATE TABLE IF NOT EXISTS `coxcl` (
  `idContactlist` int(11) NOT NULL,
  `idContact` int(10) NOT NULL,
  `createdon` int(11) DEFAULT NULL,
  PRIMARY KEY (`idContactlist`,`idContact`),
  KEY `fk_COxCL_contact1_idx` (`idContact`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `criteria`
--

CREATE TABLE IF NOT EXISTS `criteria` (
  `idCriteria` int(11) NOT NULL AUTO_INCREMENT,
  `idSegment` int(11) NOT NULL,
  `idCustomField` int(11) DEFAULT NULL,
  `relation` varchar(60) CHARACTER SET utf8 NOT NULL,
  `value` varchar(100) CHARACTER SET utf8 NOT NULL,
  `fieldName` varchar(40) CHARACTER SET utf8 COLLATE utf8_estonian_ci DEFAULT NULL,
  `type` enum('custom','domain','email','contact') CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`idCriteria`),
  KEY `idSegment` (`idSegment`,`idCustomField`),
  KEY `criteriaAcustomfield` (`idCustomField`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `customfield`
--

CREATE TABLE IF NOT EXISTS `customfield` (
  `idCustomField` int(11) NOT NULL AUTO_INCREMENT,
  `idDbase` int(11) NOT NULL,
  `name` varchar(60) CHARACTER SET utf8 NOT NULL,
  `type` enum('Text','Date','Numerical','TextArea','Select','MultiSelect') CHARACTER SET utf8 NOT NULL,
  `required` enum('Si','No') CHARACTER SET utf8 NOT NULL,
  `defaultValue` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `values` text CHARACTER SET utf8,
  `minValue` int(11) DEFAULT NULL,
  `maxValue` int(11) DEFAULT NULL,
  `maxLength` int(11) DEFAULT NULL,
  PRIMARY KEY (`idCustomField`),
  KEY `fk_CustomField_Dbases1_idx` (`idDbase`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=42 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dbase`
--

CREATE TABLE IF NOT EXISTS `dbase` (
  `idDbase` int(11) NOT NULL AUTO_INCREMENT,
  `idAccount` int(11) NOT NULL,
  `name` varchar(45) CHARACTER SET utf8 NOT NULL,
  `description` varchar(250) CHARACTER SET utf8 NOT NULL DEFAULT 'Sin descripciÃ³n',
  `Cdescription` varchar(250) CHARACTER SET utf8 NOT NULL DEFAULT 'Sin descripciÃ³n',
  `Ctotal` int(11) NOT NULL DEFAULT '0',
  `Cactive` int(11) NOT NULL DEFAULT '0',
  `Cunsubscribed` int(11) NOT NULL DEFAULT '0',
  `Cbounced` int(11) NOT NULL DEFAULT '0',
  `Cspam` int(11) NOT NULL DEFAULT '0',
  `createdon` int(11) DEFAULT NULL,
  `updatedon` int(11) DEFAULT NULL,
  PRIMARY KEY (`idDbase`),
  KEY `fk_Dbases_Account1_idx` (`idAccount`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `domain`
--

CREATE TABLE IF NOT EXISTS `domain` (
  `idDomain` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`idDomain`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=129 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `email`
--

CREATE TABLE IF NOT EXISTS `email` (
  `idEmail` int(11) NOT NULL AUTO_INCREMENT,
  `idAccount` int(11) NOT NULL,
  `idDomain` int(60) NOT NULL,
  `email` varchar(80) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `bounced` int(11) unsigned NOT NULL DEFAULT '0',
  `spam` int(11) unsigned NOT NULL DEFAULT '0',
  `blocked` int(11) unsigned DEFAULT '0',
  `createdon` int(40) DEFAULT NULL,
  `updatedon` int(50) DEFAULT NULL,
  PRIMARY KEY (`idEmail`),
  KEY `fk_Email_Account1_idx` (`idAccount`),
  KEY `idDomain` (`idDomain`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10288 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fieldinstance`
--

CREATE TABLE IF NOT EXISTS `fieldinstance` (
  `idCustomField` int(11) NOT NULL,
  `idContact` int(10) NOT NULL,
  `textValue` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `numberValue` int(10) DEFAULT NULL,
  PRIMARY KEY (`idCustomField`,`idContact`),
  KEY `fk_FieldInstance_Contact1_idx` (`idContact`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `flashmessage`
--

CREATE TABLE IF NOT EXISTS `flashmessage` (
  `idFlashMessage` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `accounts` tinytext NOT NULL,
  `type` tinytext CHARACTER SET utf16 NOT NULL,
  `start` int(11) NOT NULL,
  `end` int(11) NOT NULL,
  `createdon` int(11) NOT NULL,
  PRIMARY KEY (`idFlashMessage`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `importfile`
--

CREATE TABLE IF NOT EXISTS `importfile` (
  `idImportfile` int(11) NOT NULL AUTO_INCREMENT,
  `idAccount` int(11) NOT NULL,
  `internalName` varchar(100) CHARACTER SET utf8 NOT NULL,
  `originalName` varchar(100) CHARACTER SET utf8 NOT NULL,
  `createdon` int(11) unsigned NOT NULL,
  PRIMARY KEY (`idImportfile`),
  KEY `idAccount` (`idAccount`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=208 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `importproccess`
--

CREATE TABLE IF NOT EXISTS `importproccess` (
  `idImportproccess` int(11) NOT NULL AUTO_INCREMENT,
  `idAccount` int(11) NOT NULL,
  `inputFile` int(11) DEFAULT NULL,
  `successFile` int(11) DEFAULT NULL,
  `errorFile` int(11) DEFAULT NULL,
  `totalReg` int(10) DEFAULT NULL,
  `status` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `processLines` int(10) DEFAULT '0',
  `exist` int(10) DEFAULT '0',
  `invalid` int(10) DEFAULT '0',
  `bloqued` int(10) DEFAULT '0',
  `limitcontact` int(10) DEFAULT '0',
  `repeated` int(10) DEFAULT '0',
  PRIMARY KEY (`idImportproccess`),
  KEY `idAccount` (`idAccount`,`inputFile`,`successFile`,`errorFile`),
  KEY `inputFile` (`inputFile`),
  KEY `successFile` (`successFile`),
  KEY `errorFile` (`errorFile`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mail`
--

CREATE TABLE IF NOT EXISTS `mail` (
  `idMail` int(11) NOT NULL AUTO_INCREMENT,
  `idAccount` int(11) NOT NULL,
  `type` enum('Html','Editor') DEFAULT NULL,
  `status` enum('Scheduled','Draft','Cancelled','Paused','Sending','Sent') NOT NULL,
  `wizardOption` enum('schedule','target','source','setup') NOT NULL,
  `startedon` int(11) DEFAULT NULL,
  `totalContacts` int(11) DEFAULT '0',
  `scheduleDate` int(11) DEFAULT NULL,
  `finishedon` int(11) DEFAULT NULL,
  `createdon` int(11) DEFAULT NULL,
  `updatedon` int(11) DEFAULT NULL,
  `uniqueOpens` int(11) DEFAULT '0',
  `clicks` int(11) DEFAULT '0',
  `bounced` int(11) DEFAULT '0',
  `spam` int(11) DEFAULT '0',
  `unsubscribed` int(11) DEFAULT '0',
  `name` varchar(80) DEFAULT NULL,
  `subject` varchar(120) DEFAULT NULL,
  `fromName` varchar(50) DEFAULT NULL,
  `fromEmail` varchar(80) DEFAULT NULL,
  `replyTo` varchar(80) DEFAULT NULL,
  `target` varchar(100) DEFAULT NULL,
  `previewData` text,
  PRIMARY KEY (`idMail`),
  KEY `fk_mail_account1_idx` (`idAccount`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=161 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mailcontent`
--

CREATE TABLE IF NOT EXISTS `mailcontent` (
  `idMail` int(11) NOT NULL,
  `content` mediumtext NOT NULL,
  `plainText` mediumtext,
  `googleAnalytics` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`idMail`),
  KEY `fk_mailcontent_mail1_idx` (`idMail`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mailevent`
--

CREATE TABLE IF NOT EXISTS `mailevent` (
  `idMailEvent` int(11) NOT NULL AUTO_INCREMENT,
  `idMail` int(11) NOT NULL,
  `idContact` int(11) NOT NULL,
  `idBouncedCode` int(11) DEFAULT NULL,
  `description` enum('opening','opening for click','bounced','spam','unsubscribed') NOT NULL,
  `userAgent` varchar(80) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `date` int(11) DEFAULT NULL,
  PRIMARY KEY (`idMailEvent`),
  KEY `idMail` (`idMail`),
  KEY `idContact` (`idContact`),
  KEY `idMail_2` (`idMail`,`idContact`),
  KEY `idMail_3` (`idMail`,`idContact`),
  KEY `idBouncedCode` (`idBouncedCode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `maillink`
--

CREATE TABLE IF NOT EXISTS `maillink` (
  `idMailLink` int(11) NOT NULL AUTO_INCREMENT,
  `idAccount` int(11) NOT NULL,
  `link` tinytext NOT NULL,
  `createdon` int(11) NOT NULL,
  PRIMARY KEY (`idMailLink`),
  KEY `idAccount` (`idAccount`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mailreportfile`
--

CREATE TABLE IF NOT EXISTS `mailreportfile` (
  `idMailReportFile` int(11) NOT NULL AUTO_INCREMENT,
  `idMail` int(11) NOT NULL,
  `type` enum('opens','clicks','unsubscribed','bounced') NOT NULL,
  `name` varchar(100) NOT NULL,
  `createdon` int(11) NOT NULL,
  PRIMARY KEY (`idMailReportFile`),
  KEY `idMail` (`idMail`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mailschedule`
--

CREATE TABLE IF NOT EXISTS `mailschedule` (
  `idMailSchedule` int(11) NOT NULL AUTO_INCREMENT,
  `idMail` int(11) NOT NULL,
  `scheduleDate` int(11) NOT NULL,
  `confirmationStatus` enum('Yes','No') CHARACTER SET utf8 NOT NULL DEFAULT 'No',
  PRIMARY KEY (`idMailSchedule`),
  KEY `idMail` (`idMail`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mxc`
--

CREATE TABLE IF NOT EXISTS `mxc` (
  `idMail` int(11) NOT NULL,
  `idContact` int(10) NOT NULL,
  `status` enum('scheduled','sent','canceled') NOT NULL DEFAULT 'scheduled',
  `opening` int(11) NOT NULL DEFAULT '0',
  `clicks` int(11) NOT NULL DEFAULT '0',
  `bounced` int(11) NOT NULL DEFAULT '0',
  `spam` int(11) NOT NULL DEFAULT '0',
  `contactlists` tinytext,
  PRIMARY KEY (`idMail`,`idContact`),
  KEY `fk_mxc_contact1_idx` (`idContact`),
  KEY `fk_mxc_mail1_idx` (`idMail`),
  KEY `idContact` (`idContact`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mxcxl`
--

CREATE TABLE IF NOT EXISTS `mxcxl` (
  `idMail` int(11) NOT NULL,
  `idMailLink` int(11) NOT NULL,
  `idContact` int(11) NOT NULL,
  `click` int(11) NOT NULL,
  KEY `idMail` (`idMail`),
  KEY `idMailLink` (`idMailLink`),
  KEY `idContact` (`idContact`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mxl`
--

CREATE TABLE IF NOT EXISTS `mxl` (
  `idMail` int(11) NOT NULL,
  `idMailLink` int(11) NOT NULL,
  `totalClicks` int(11) DEFAULT '0',
  PRIMARY KEY (`idMail`),
  KEY `idMailLink` (`idMailLink`),
  KEY `idMail` (`idMail`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `resource`
--

CREATE TABLE IF NOT EXISTS `resource` (
  `idResource` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`idResource`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `role`
--

CREATE TABLE IF NOT EXISTS `role` (
  `idRole` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`idRole`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `segment`
--

CREATE TABLE IF NOT EXISTS `segment` (
  `idSegment` int(11) NOT NULL AUTO_INCREMENT,
  `idDbase` int(11) NOT NULL,
  `name` varchar(60) CHARACTER SET utf8 NOT NULL,
  `description` varchar(100) CHARACTER SET utf8 NOT NULL,
  `criterion` varchar(40) CHARACTER SET utf8 NOT NULL,
  `createdon` int(11) unsigned NOT NULL,
  PRIMARY KEY (`idSegment`),
  KEY `idDbase` (`idDbase`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sendhistory`
--

CREATE TABLE IF NOT EXISTS `sendhistory` (
  `idSendHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idMail` int(11) NOT NULL,
  `scheduleDate` int(11) NOT NULL,
  `startDate` int(11) NOT NULL,
  `finishDate` int(11) NOT NULL,
  `status` enum('standby','sent','stopped','canceled','error') CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`idSendHistory`),
  KEY `idMail` (`idMail`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `socialnetwork`
--

CREATE TABLE IF NOT EXISTS `socialnetwork` (
  `idSocialnetwork` int(11) NOT NULL AUTO_INCREMENT,
  `idUser` int(11) NOT NULL,
  `userid` varchar(200) NOT NULL,
  `token` varchar(200) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` enum('Twitter','Facebook') NOT NULL,
  `category` enum('Profile','Fan Page') NOT NULL,
  PRIMARY KEY (`idSocialnetwork`),
  KEY `fk_usuario_social` (`idUser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `statcontactlist`
--

CREATE TABLE IF NOT EXISTS `statcontactlist` (
  `idContactlist` int(11) NOT NULL,
  `idMail` int(11) NOT NULL,
  `uniqueOpens` int(11) DEFAULT '0',
  `clicks` int(11) DEFAULT '0',
  `bounced` int(11) DEFAULT '0',
  `spam` int(11) DEFAULT '0',
  `unsubscribed` int(11) DEFAULT '0',
  `sent` int(11) DEFAULT '0',
  `sentDate` int(11) NOT NULL,
  PRIMARY KEY (`idContactlist`,`idMail`),
  KEY `fk_statcontactlist_mail1_idx` (`idMail`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `statdbase`
--

CREATE TABLE IF NOT EXISTS `statdbase` (
  `idDbase` int(11) NOT NULL,
  `idMail` int(11) NOT NULL,
  `uniqueOpens` int(11) DEFAULT '0',
  `clicks` int(11) DEFAULT '0',
  `bounced` int(11) DEFAULT '0',
  `spam` int(11) DEFAULT '0',
  `unsubscribed` int(11) DEFAULT '0',
  `sent` int(11) DEFAULT NULL,
  `sentDate` int(11) NOT NULL,
  PRIMARY KEY (`idDbase`,`idMail`),
  KEY `fk_statdbase_dbase1_idx` (`idDbase`),
  KEY `fk_statdbase_mail1` (`idMail`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sxc`
--

CREATE TABLE IF NOT EXISTS `sxc` (
  `idSegment` int(11) NOT NULL,
  `idContact` int(11) NOT NULL,
  PRIMARY KEY (`idSegment`,`idContact`),
  KEY `idSegment` (`idSegment`) USING BTREE,
  KEY `idContact` (`idContact`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `template`
--

CREATE TABLE IF NOT EXISTS `template` (
  `idTemplate` int(11) NOT NULL AUTO_INCREMENT,
  `idAccount` int(11) DEFAULT NULL,
  `name` varchar(80) CHARACTER SET utf8 NOT NULL,
  `category` varchar(45) CHARACTER SET utf8 NOT NULL,
  `content` mediumtext CHARACTER SET utf8,
  `contentHtml` mediumtext CHARACTER SET utf8,
  PRIMARY KEY (`idTemplate`),
  KEY `idAccount` (`idAccount`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=68 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `templateimage`
--

CREATE TABLE IF NOT EXISTS `templateimage` (
  `idTemplateImage` int(11) NOT NULL AUTO_INCREMENT,
  `idTemplate` int(11) NOT NULL,
  `name` varchar(60) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`idTemplateImage`),
  KEY `idTemplate` (`idTemplate`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=63 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tmpimport`
--

CREATE TABLE IF NOT EXISTS `tmpimport` (
  `idArray` int(11) NOT NULL DEFAULT '0',
  `idEmail` int(11) DEFAULT NULL,
  `idContact` int(11) DEFAULT NULL,
  `email` varchar(80) DEFAULT NULL,
  `name` varchar(80) DEFAULT NULL,
  `lastName` varchar(80) DEFAULT NULL,
  `blocked` int(1) DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `dbase` int(1) DEFAULT NULL,
  `coxcl` int(1) DEFAULT NULL,
  `new` int(1) DEFAULT NULL,
  PRIMARY KEY (`idArray`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tmprecoverpass`
--

CREATE TABLE IF NOT EXISTS `tmprecoverpass` (
  `idTmpRecoverPass` varchar(60) NOT NULL,
  `idUser` int(11) NOT NULL,
  `url` varchar(100) NOT NULL,
  `date` int(11) NOT NULL,
  PRIMARY KEY (`idTmpRecoverPass`),
  KEY `idUser` (`idUser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tmpreport`
--

CREATE TABLE IF NOT EXISTS `tmpreport` (
  `idTmpReport` int(11) NOT NULL AUTO_INCREMENT,
  `idMail` int(11) NOT NULL,
  `reportType` enum('opens','clicks','unsubscribed','bounced') NOT NULL,
  `email` varchar(100) NOT NULL,
  `name` varchar(60) DEFAULT NULL,
  `lastName` varchar(60) DEFAULT NULL,
  `os` varchar(50) DEFAULT NULL,
  `link` varchar(120) DEFAULT NULL,
  `bouncedType` varchar(40) DEFAULT NULL,
  `category` varchar(60) DEFAULT NULL,
  `date` int(11) NOT NULL,
  PRIMARY KEY (`idTmpReport`),
  KEY `idMail` (`idMail`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `urldomain`
--

CREATE TABLE IF NOT EXISTS `urldomain` (
  `idUrlDomain` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 NOT NULL,
  `imageUrl` varchar(100) CHARACTER SET utf8 NOT NULL,
  `trackUrl` varchar(100) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`idUrlDomain`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `idUser` int(11) NOT NULL AUTO_INCREMENT,
  `idAccount` int(11) NOT NULL,
  `email` varchar(45) CHARACTER SET utf8 NOT NULL,
  `password` text CHARACTER SET utf8 NOT NULL,
  `username` varchar(50) CHARACTER SET utf8 NOT NULL,
  `userrole` varchar(30) CHARACTER SET utf8 NOT NULL,
  `firstName` varchar(45) CHARACTER SET utf8 NOT NULL,
  `lastName` varchar(45) CHARACTER SET utf8 NOT NULL,
  `createdon` int(11) DEFAULT NULL,
  `updatedon` int(11) DEFAULT NULL,
  PRIMARY KEY (`idUser`),
  KEY `fk_usuario_cuenta_idx` (`idAccount`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=33 ;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `account`
--
ALTER TABLE `account`
  ADD CONSTRAINT `account_ibfk_1` FOREIGN KEY (`idUrlDomain`) REFERENCES `urldomain` (`idUrlDomain`);

--
-- Filtros para la tabla `action`
--
ALTER TABLE `action`
  ADD CONSTRAINT `action_ibfk_2` FOREIGN KEY (`idResource`) REFERENCES `resource` (`idResource`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `allowed`
--
ALTER TABLE `allowed`
  ADD CONSTRAINT `allowed_ibfk_2` FOREIGN KEY (`idAction`) REFERENCES `action` (`idAction`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `allowed_ibfk_3` FOREIGN KEY (`idRole`) REFERENCES `role` (`idRole`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `asset`
--
ALTER TABLE `asset`
  ADD CONSTRAINT `asset_ibfk_1` FOREIGN KEY (`idAccount`) REFERENCES `account` (`idAccount`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `blockedemail`
--
ALTER TABLE `blockedemail`
  ADD CONSTRAINT `fk_idEmail_Email` FOREIGN KEY (`idEmail`) REFERENCES `email` (`idEmail`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `contact`
--
ALTER TABLE `contact`
  ADD CONSTRAINT `fk_Contact_Dbase1` FOREIGN KEY (`idDbase`) REFERENCES `dbase` (`idDbase`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Contact_Email1` FOREIGN KEY (`idEmail`) REFERENCES `email` (`idEmail`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `contactlist`
--
ALTER TABLE `contactlist`
  ADD CONSTRAINT `fk_Dbase_idDbase` FOREIGN KEY (`idDbase`) REFERENCES `dbase` (`idDbase`) ON DELETE CASCADE;

--
-- Filtros para la tabla `coxcl`
--
ALTER TABLE `coxcl`
  ADD CONSTRAINT `coxcl_ibfk_1` FOREIGN KEY (`idContact`) REFERENCES `contact` (`idContact`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_COxCL_contactlist1` FOREIGN KEY (`idContactlist`) REFERENCES `contactlist` (`idContactlist`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `criteria`
--
ALTER TABLE `criteria`
  ADD CONSTRAINT `criteria_ibfk_1` FOREIGN KEY (`idSegment`) REFERENCES `segment` (`idSegment`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `criteria_ibfk_2` FOREIGN KEY (`idCustomField`) REFERENCES `customfield` (`idCustomField`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `customfield`
--
ALTER TABLE `customfield`
  ADD CONSTRAINT `fk_CustomField_Dbases1` FOREIGN KEY (`idDbase`) REFERENCES `dbase` (`idDbase`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `dbase`
--
ALTER TABLE `dbase`
  ADD CONSTRAINT `fk_Dbases_Account1` FOREIGN KEY (`idAccount`) REFERENCES `account` (`idAccount`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `email`
--
ALTER TABLE `email`
  ADD CONSTRAINT `fk_Email_Account1` FOREIGN KEY (`idAccount`) REFERENCES `account` (`idAccount`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Email_Domain` FOREIGN KEY (`idDomain`) REFERENCES `domain` (`idDomain`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `fieldinstance`
--
ALTER TABLE `fieldinstance`
  ADD CONSTRAINT `fieldinstance_ibfk_1` FOREIGN KEY (`idContact`) REFERENCES `contact` (`idContact`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_FieldInstance_CustomField1` FOREIGN KEY (`idCustomField`) REFERENCES `customfield` (`idCustomField`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `importfile`
--
ALTER TABLE `importfile`
  ADD CONSTRAINT `FK_IDACCOUNT` FOREIGN KEY (`idAccount`) REFERENCES `account` (`idAccount`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `importproccess`
--
ALTER TABLE `importproccess`
  ADD CONSTRAINT `importproccess_ibfk_1` FOREIGN KEY (`idAccount`) REFERENCES `account` (`idAccount`),
  ADD CONSTRAINT `importproccess_ibfk_2` FOREIGN KEY (`inputFile`) REFERENCES `importfile` (`idImportfile`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `importproccess_ibfk_3` FOREIGN KEY (`successFile`) REFERENCES `importfile` (`idImportfile`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `importproccess_ibfk_4` FOREIGN KEY (`errorFile`) REFERENCES `importfile` (`idImportfile`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `mail`
--
ALTER TABLE `mail`
  ADD CONSTRAINT `fk_mail_account1` FOREIGN KEY (`idAccount`) REFERENCES `account` (`idAccount`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `mailcontent`
--
ALTER TABLE `mailcontent`
  ADD CONSTRAINT `fk_mailcontent_mail1` FOREIGN KEY (`idMail`) REFERENCES `mail` (`idMail`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `mailevent`
--
ALTER TABLE `mailevent`
  ADD CONSTRAINT `mailevent_ibfk_1` FOREIGN KEY (`idMail`) REFERENCES `mail` (`idMail`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mailevent_ibfk_2` FOREIGN KEY (`idContact`) REFERENCES `contact` (`idContact`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mailevent_ibfk_3` FOREIGN KEY (`idBouncedCode`) REFERENCES `bouncedcode` (`idBouncedCode`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `maillink`
--
ALTER TABLE `maillink`
  ADD CONSTRAINT `maillink_ibfk_1` FOREIGN KEY (`idAccount`) REFERENCES `account` (`idAccount`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `mailreportfile`
--
ALTER TABLE `mailreportfile`
  ADD CONSTRAINT `mailreportfile_ibfk_1` FOREIGN KEY (`idMail`) REFERENCES `mail` (`idMail`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `mailschedule`
--
ALTER TABLE `mailschedule`
  ADD CONSTRAINT `mailschedule_ibfk_1` FOREIGN KEY (`idMail`) REFERENCES `mail` (`idMail`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `mxc`
--
ALTER TABLE `mxc`
  ADD CONSTRAINT `fk_mxc_mail1` FOREIGN KEY (`idMail`) REFERENCES `mail` (`idMail`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mxc_ibfk_1` FOREIGN KEY (`idContact`) REFERENCES `contact` (`idContact`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `mxcxl`
--
ALTER TABLE `mxcxl`
  ADD CONSTRAINT `mxcxl_ibfk_1` FOREIGN KEY (`idMail`) REFERENCES `mail` (`idMail`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mxcxl_ibfk_2` FOREIGN KEY (`idMailLink`) REFERENCES `maillink` (`idMailLink`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mxcxl_ibfk_3` FOREIGN KEY (`idContact`) REFERENCES `contact` (`idContact`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `mxl`
--
ALTER TABLE `mxl`
  ADD CONSTRAINT `mxl_ibfk_1` FOREIGN KEY (`idMail`) REFERENCES `mail` (`idMail`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mxl_ibfk_2` FOREIGN KEY (`idMailLink`) REFERENCES `maillink` (`idMailLink`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `segment`
--
ALTER TABLE `segment`
  ADD CONSTRAINT `segment_ibfk_1` FOREIGN KEY (`idDbase`) REFERENCES `dbase` (`idDbase`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `socialnetwork`
--
ALTER TABLE `socialnetwork`
  ADD CONSTRAINT `fk_usuario_social` FOREIGN KEY (`idUser`) REFERENCES `user` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `statcontactlist`
--
ALTER TABLE `statcontactlist`
  ADD CONSTRAINT `fk_statcontactlist_contactlist1` FOREIGN KEY (`idContactlist`) REFERENCES `contactlist` (`idContactlist`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_statcontactlist_mail1` FOREIGN KEY (`idMail`) REFERENCES `mail` (`idMail`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `statdbase`
--
ALTER TABLE `statdbase`
  ADD CONSTRAINT `fk_statdbase_dbase1` FOREIGN KEY (`idDbase`) REFERENCES `dbase` (`idDbase`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_statdbase_mail1` FOREIGN KEY (`idMail`) REFERENCES `mail` (`idMail`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `sxc`
--
ALTER TABLE `sxc`
  ADD CONSTRAINT `sxc_ibfk_1` FOREIGN KEY (`idSegment`) REFERENCES `segment` (`idSegment`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sxc_ibfk_2` FOREIGN KEY (`idContact`) REFERENCES `contact` (`idContact`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `template`
--
ALTER TABLE `template`
  ADD CONSTRAINT `template_ibfk_1` FOREIGN KEY (`idAccount`) REFERENCES `account` (`idAccount`) ON DELETE CASCADE;

--
-- Filtros para la tabla `templateimage`
--
ALTER TABLE `templateimage`
  ADD CONSTRAINT `templateimage_ibfk_1` FOREIGN KEY (`idTemplate`) REFERENCES `template` (`idTemplate`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tmprecoverpass`
--
ALTER TABLE `tmprecoverpass`
  ADD CONSTRAINT `tmprecoverpass_ibfk_1` FOREIGN KEY (`idUser`) REFERENCES `user` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tmpreport`
--
ALTER TABLE `tmpreport`
  ADD CONSTRAINT `tmpreport_ibfk_1` FOREIGN KEY (`idMail`) REFERENCES `mail` (`idMail`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `fk_usuario_cuenta` FOREIGN KEY (`idAccount`) REFERENCES `account` (`idAccount`) ON DELETE CASCADE ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
