DROP PROCEDURE IF EXISTS `update_counters_list`;

DELIMITER //

CREATE DEFINER = `root`@`localhost` PROCEDURE `update_counters_list`(IN `idContactlist` int(11))
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
END;
//