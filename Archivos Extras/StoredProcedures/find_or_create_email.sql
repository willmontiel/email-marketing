DROP FUNCTION IF EXISTS `find_or_create_email`;

DELIMITER //

CREATE DEFINER = `root`@`localhost` FUNCTION `find_or_create_email`(email VARCHAR(80), domain VARCHAR(80), idAccount INT(11))
 RETURNS int(11)
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

END;
//
