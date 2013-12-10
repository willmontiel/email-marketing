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
		
		$sql = "INSERT IGNORE INTO mxc ";
		
		switch ($target->destination) {
			case 'dbases':
				$sql2 = "(SELECT " . $mail->idMail . ", c.idContact, 'scheduled'
                        	FROM contact AS c 
                        		JOIN email AS e ON (c.idEmail = e.idEmail) 
                        	WHERE c.idDbase IN (" . $ids . ") AND e.bounced <= 0 AND e.spam <= 0 AND e.blocked <= 0 AND c.spam <=0 AND c.bounced <= 0 AND c.unsubscribed <= 0)";
				break;
			
			case 'contactlists':
				$sql2 = "(SELECT " . $mail->idMail . ", cl.idContact, 'scheduled' 
							FROM coxcl AS cl
								JOIN contact AS c ON (cl.idContact = c.idContact)
								JOIN email AS e ON (c.idEmail = e.idEmail)
							WHERE cl.idContactlist IN (" . $ids . ") AND e.bounced <= 0 AND e.spam <= 0 AND e.blocked <= 0 AND c.spam <=0 AND c.bounced <= 0 AND c.unsubscribed <= 0)";
				break;
				
			case 'segments':
				$sql2 = "(SELECT " . $mail->idMail . ", sc.idContact, 'scheduled' 
							FROM sxc AS sc
								JOIN contact AS c ON (sc.idContact = c.idContact)
								JOIN email AS e ON (c.idEmail = e.idEmail)
							WHERE sc.idSegment IN (" . $ids . ") AND e.bounced <= 0 AND e.spam <= 0 AND e.blocked <= 0 AND c.spam <=0 AND c.bounced <= 0 AND c.unsubscribed <= 0)";
				break;
		}
		
		$sql .= $sql2;
//		$this->log->log('SQL: ' . $sql);
		
		$db = Phalcon\DI::getDefault()->get('db');
		
		$destination = $db->execute($sql);
		
		if (!$destination) {
			throw new InvalidArgumentException('Error while consulting recipients');
		}
	}
}