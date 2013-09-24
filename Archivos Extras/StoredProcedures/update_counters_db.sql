DROP PROCEDURE IF EXISTS `update_counters_db`;

DELIMITER //

CREATE DEFINER = `root`@`localhost` PROCEDURE `update_counters_db`(IN `idDbase` int(11))
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

END;
//