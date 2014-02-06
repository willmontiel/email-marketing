<?php
class IdentifyTarget
{
	public function __construct() 
	{
		$this->log = Phalcon\DI::getDefault()->get('logger');
	}
	
	public function identifyTarget($mail)
	{
		if($mail->target){
			$target = json_decode($mail->target);
//			$this->log->log('Target: ' . print_r($target, true));
			$ids = implode(',', $target->ids);
//			$this->log->log('Ids: ' . $ids);
		}
		else {
			throw new \InvalidArgumentException('Error while checking target');
		}
		
		if (!empty($target->filter)){
			$this->log->log('Hay filtro');
		}
		else {
			$this->log->log('No hay filtro');
		}
		
//		$this->log->log('Iniciando Consulta e inserción target');
		$sql = "INSERT IGNORE INTO mxc ";
		switch ($target->destination) {
			case 'dbases':
				$sql2 = "(SELECT " . $mail->idMail . ", c.idContact, 'scheduled', 0, 0, 0, 0, GROUP_CONCAT(l.idContactlist)
                        	FROM contact AS c 
                        		JOIN email AS e ON (c.idEmail = e.idEmail) 
								JOIN coxcl AS l ON (c.idContact = l.idContact)
                        	WHERE c.idDbase IN (" . $ids . ") AND e.bounced <= 0 AND e.spam <= 0 AND e.blocked <= 0 AND c.spam <=0 AND c.bounced <= 0 AND c.unsubscribed <= 0
							GROUP BY 1, 2)";
				
				/**
				 * Inserting data into statdbase for statistics.
				 */
				$values = ' ';
				$comma = true;
				foreach ($target->ids as $id) {
					if ($comma) {
						$values .= '(' . $id . ', ' . $mail->idMail . ', 0, 0, 0, 0, 0, ' . $mail->totalContacts . ', ' . time() . ')';
						$comma = false;
					}
					else{
						$values .= ', (' . $id . ', ' . $mail->idMail . ', 0, 0, 0, 0, 0, ' . $mail->totalContacts . ', ' . time() . ')';
					}
				}
				
				$sql4 = 'INSERT INTO statdbase VALUES ' . $values;
				
				/**
				 * Inserting data into statcontactlist for statistics
				 */
				$sql3 = 'INSERT IGNORE INTO statcontactlist 
							(SELECT c.idContactlist, ' . $mail->idMail . ', 0, 0, 0, 0, 0, ' . $mail->totalContacts . ', ' . time() . '
								FROM coxcl AS c
								   JOIN mxc AS m ON (m.idContact = c.idContact )
								WHERE m.idMail = ' . $mail->idMail . ')';
				break;
			
			case 'contactlists':
				$sql2 = "(SELECT " . $mail->idMail . ", cl.idContact, 'scheduled', 0, 0, 0, 0, GROUP_CONCAT(cl.idContactlist) 
							FROM coxcl AS cl
								JOIN contact AS c ON (cl.idContact = c.idContact)
								JOIN email AS e ON (c.idEmail = e.idEmail)
							WHERE cl.idContactlist IN (" . $ids . ") AND e.bounced <= 0 AND e.spam <= 0 AND e.blocked <= 0 AND c.spam <=0 AND c.bounced <= 0 AND c.unsubscribed <= 0
							GROUP BY 1, 2)";
				
				/**
				 * Inserting data into statcontactlist for statistics
				 */
				$values = ' ';
				$comma = true;
				foreach ($target->ids as $id) {
					if ($comma) {
						$values .= '(' . $id . ', ' . $mail->idMail . ', 0, 0, 0, 0, 0, ' . $mail->totalContacts . ', ' . time() . ')';
						$comma = false;
					}
					else{
						$values .= ', (' . $id . ', ' . $mail->idMail . ', 0, 0, 0, 0, 0, ' . $mail->totalContacts . ', ' . time() . ')';
					}
				}
				
				$sql3 = 'INSERT INTO statcontactlist VALUES ' . $values;
				
				$sql4 = 'INSERT IGNORE INTO statdbase 
							(SELECT c.idDbase, ' . $mail->idMail . ', 0, 0, 0, 0, 0, ' . $mail->totalContacts . ', ' . time() . '
								FROM contact AS c
								JOIN mxc AS m ON (m.idContact = c.idContact)
							WHERE m.idMail = ' . $mail->idMail . ')';
				break;
				
			case 'segments':
				$sql2 = "(SELECT " . $mail->idMail . ", sc.idContact, 'scheduled', 0, 0, 0, 0, GROUP_CONCAT(l.idContactlist) 
							FROM sxc AS sc
								JOIN contact AS c ON (sc.idContact = c.idContact)
								JOIN email AS e ON (c.idEmail = e.idEmail)
								JOIN coxcl AS l ON (c.idContact = l.idContact)
							WHERE sc.idSegment IN (" . $ids . ") AND e.bounced <= 0 AND e.spam <= 0 AND e.blocked <= 0 AND c.spam <=0 AND c.bounced <= 0 AND c.unsubscribed <= 0 
							GROUP BY 1, 2)";
				
				$sql3 = 'INSERT IGNORE INTO statcontactlist 
							(SELECT c.idContactlist, ' . $mail->idMail . ', 0, 0, 0, 0, 0, ' . $mail->totalContacts . ', ' . time() . '
								FROM coxcl AS c
								   JOIN mxc AS m ON (m.idContact = c.idContact )
								WHERE m.idMail = ' . $mail->idMail . ')';
				
				$sql4 = 'INSERT IGNORE INTO statdbase 
							(SELECT c.idDbase, ' . $mail->idMail . ', 0, 0, 0, 0, 0, ' . $mail->totalContacts . ', ' . time() . '
								FROM contact AS c
								JOIN mxc AS m ON (m.idContact = c.idContact)
							WHERE m.idMail = ' . $mail->idMail . ' LIMIT 0, 1)';
				break;
		}
		
		$sql .= $sql2;
		
//		$this->log->log('SQL Mxc: ' . $sql);
//		$this->log->log('SQL Contact: ' . $sql3);
//		$this->log->log('SQL Dbase: ' . $sql4);
		
		$db = Phalcon\DI::getDefault()->get('db');
		
		$destination = $db->execute($sql);
		$statcontactlist = $db->execute($sql3);
		$statdbase = $db->execute($sql4);
		
		
		if (!$destination || !$statcontactlist || !$statdbase) {
			throw new InvalidArgumentException('Error while consulting recipients');
		}
	}
}