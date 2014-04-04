<?php
class IdentifyTarget
{
	public $mail;
	public $identifiers;
	public $target;
	
	public function __construct() 
	{
		$this->log = Phalcon\DI::getDefault()->get('logger');
	}
	
	public function identifyTarget(Mail $mail)
	{
		
		$this->mail = $mail;
		$this->validateMail();
	
		if (!empty($this->target->filter)){
			$this->processTarget(true);
		}
		else {
			$this->processTarget();
		}
	}
	
	private function validateMail()
	{
		if($this->mail->target){
			$this->target = json_decode($this->mail->target);
//			$this->log->log('Target: ' . print_r($target, true));
			$this->identifiers = implode(',', $this->target->ids);
//			$this->log->log('Ids: ' . $ids);
		}
		else {
			throw new Exception('Error while checking target');
		}
	}
	
	private function processTarget($filter = false)
	{
		$generalSql = "INSERT IGNORE INTO mxc ";
		switch ($this->target->destination) {
			case 'dbases':
				$sql = $this->getSqlByDbase($filter);
				break;
			
			case 'contactlists':
				$sql = $this->getSqlByContactList($filter);
				break;
				
			case 'segments':
				$sql = $this->getSqlBySegment($filter);
				break;
		}
		
		$generalSql .= $sql->general;
		
		$this->log->log('SQL General: ' . $generalSql);
		$this->log->log('SQL Contactlist: ' . $sql->contactlist);
		$this->log->log('SQL Dbase: ' . $sql->dbase);
		
		$db = Phalcon\DI::getDefault()->get('db');

		$destination = $db->execute($generalSql);
		$statcontactlist = $db->execute($sql->contactlist);
		$statdbase = $db->execute($sql->dbase);
		
		if (!$destination || !$statcontactlist || !$statdbase) {
			throw new Exception('Error while consulting recipients');
		}
	}
	
	private function getSqlByDbase($filter = false)
	{
		if ($filter) {
			$filters = $this->getFilterSql();
		}
		else {
			$filters = new stdClass();
			$filters->join = '';
			$filters->and = '';
		}	
		
		$sql1 = "(SELECT " . $this->mail->idMail . ", c.idContact, null, 'scheduled', 0, 0, 0, 0, 0, GROUP_CONCAT(l.idContactlist), 0, 0, 0, 0, 0, 0, 0, 0
				  FROM contact AS c 
					 JOIN email AS e ON (c.idEmail = e.idEmail) 
					 JOIN coxcl AS l ON (c.idContact = l.idContact) " . $filters->join . "
					 WHERE c.idDbase IN (" . $this->identifiers . ") " . $filters->and . " AND e.bounced = 0 AND e.spam = 0 AND e.blocked = 0 AND c.unsubscribed = 0 
				   GROUP BY 1, 2)";
		/**
		 * Inserting data into statdbase for statistics.
		 */
		$values = ' ';
		$comma = true;
		foreach ($this->target->ids as $id) {
			if ($comma) {
				$values .= '(' . $id . ', ' . $this->mail->idMail . ', 0, 0, 0, 0, 0, ' . $this->mail->totalContacts . ', ' . time() . ')';
				$comma = false;
			}
			else{
				$values .= ', (' . $id . ', ' . $this->mail->idMail . ', 0, 0, 0, 0, 0, ' . $this->mail->totalContacts . ', ' . time() . ')';
			}
		}
		$sql2 = 'INSERT INTO statdbase VALUES ' . $values;
		/**
		 * Inserting data into statcontactlist for statistics
		 */
		$sql3 = 'INSERT IGNORE INTO statcontactlist 
					(SELECT c.idContactlist, ' . $this->mail->idMail . ', 0, 0, 0, 0, 0, ' . $this->mail->totalContacts . ', ' . time() . '
					 FROM coxcl AS c
					    JOIN mxc AS m ON (m.idContact = c.idContact )
					 WHERE m.idMail = ' . $this->mail->idMail . ')';
		
		$sql = new stdClass();
		
		$sql->general = $sql1;
		$sql->contactlist = $sql2;
		$sql->dbase = $sql3;
		
		return $sql;
	}
	
	private function getSqlByContactList($filter = false)
	{
		if ($filter) {
		    $filters = $this->getFilterSql();
		}
		else {
			$filters = new stdClass();
			$filters->join = '';
			$filters->and = '';
		}
		
		$sql1 = "(SELECT " . $this->mail->idMail . ", cl.idContact, null, 'scheduled', 0, 0, 0, 0, 0, GROUP_CONCAT(cl.idContactlist), 0, 0, 0, 0, 0, 0, 0, 0
				  FROM coxcl AS cl
					 JOIN contact AS c ON (cl.idContact = c.idContact)
				     JOIN email AS e ON (c.idEmail = e.idEmail) " . $filters->join . "
				     WHERE cl.idContactlist IN (" . $this->identifiers . ") " . $filters->and . "AND e.bounced = 0 AND e.spam = 0 AND e.blocked = 0 AND c.unsubscribed = 0
				  GROUP BY 1, 2)";

		/**
		 * Inserting data into statcontactlist for statistics
		 */
		$values = ' ';
		$comma = true;
		foreach ($this->target->ids as $id) {
			if ($comma) {
				$values .= '(' . $id . ', ' . $this->mail->idMail . ', 0, 0, 0, 0, 0, ' . $this->mail->totalContacts . ', ' . time() . ')';
				$comma = false;
			}
			else{
				$values .= ', (' . $id . ', ' . $this->mail->idMail . ', 0, 0, 0, 0, 0, ' . $this->mail->totalContacts . ', ' . time() . ')';
			}
		}

		$sql2 = 'INSERT IGNORE INTO statcontactlist VALUES ' . $values;

		$sql3 = 'INSERT IGNORE INTO statdbase 
					(SELECT c.idDbase, ' . $this->mail->idMail . ', 0, 0, 0, 0, 0, ' . $this->mail->totalContacts . ', ' . time() . '
						FROM contact AS c
						JOIN mxc AS m ON (m.idContact = c.idContact)
					WHERE m.idMail = ' . $this->mail->idMail . ')';
		
		$sql = new stdClass();
		
		$sql->general = $sql1;
		$sql->contactlist = $sql2;
		$sql->dbase = $sql3;
		
		return $sql;
	}
	
	private function getSqlBySegment($filter = false)
	{
		if ($filter) {
			$filters = $this->getFilterSql();
		}
		else {
			$filters = new stdClass();
			$filters->join = '';
			$filters->and = '';
		}
		
		$sql1 = "(SELECT " . $this->mail->idMail . ", sc.idContact, null, 'scheduled', 0, 0, 0, 0, 0, GROUP_CONCAT(l.idContactlist), 0, 0, 0, 0, 0, 0, 0, 0
					FROM sxc AS sc
						JOIN contact AS c ON (sc.idContact = c.idContact)
						JOIN email AS e ON (c.idEmail = e.idEmail)
						JOIN coxcl AS l ON (c.idContact = l.idContact) " . $filters->join . "
					WHERE sc.idSegment IN (" . $this->identifiers . ") " . $filters->and . " AND e.bounced = 0 AND e.spam = 0 AND e.blocked = 0 AND c.unsubscribed = 0
					GROUP BY 1, 2)";

		$sql2 = 'INSERT IGNORE INTO statcontactlist 
					(SELECT c.idContactlist, ' . $this->mail->idMail . ', 0, 0, 0, 0, 0, ' . $this->mail->totalContacts . ', ' . time() . '
						FROM coxcl AS c
						   JOIN mxc AS m ON (m.idContact = c.idContact )
						WHERE m.idMail = ' . $this->mail->idMail . ')';

		$sql3 = 'INSERT IGNORE INTO statdbase 
					(SELECT c.idDbase, ' . $this->mail->idMail . ', 0, 0, 0, 0, 0, ' . $this->mail->totalContacts . ', ' . time() . '
						FROM contact AS c
						JOIN mxc AS m ON (m.idContact = c.idContact)
					WHERE m.idMail = ' . $this->mail->idMail . ' LIMIT 0, 1)';
		
		$sql = new stdClass();
		
		$sql->general = $sql1;
		$sql->contactlist = $sql2;
		$sql->dbase = $sql3;
		
		return $sql;
	}
	
	private function getFilterSql()
	{
		switch ($this->target->filter->type) {
			case 'email':
				$join = '';
				$and = " AND e.email = '" . $this->target->filter->criteria . "' ";
				break;
			
			case 'open':
				$ids = implode(',', $this->target->filter->criteria);
				$join = " JOIN mxc AS m ON (m.idContact = c.idContact) ";
				$and = " AND m.idMail IN (" . $ids . ") AND m.opening != 0 ";
				break;
			
			case 'click':
				$ids = implode(',', $this->target->filter->criteria);
				$join = " JOIN mxcxl AS m ON (m.idContact = c.idContact) ";
				$and = " AND m.idMailLink IN (" . $ids . ") ";
				break;
			
			case 'mailExclude':
				$ids = implode(',', $this->target->filter->criteria);
				$join = " JOIN mxc AS m ON (m.idContact = c.idContact) ";
				$and = " AND m.idMail NOT IN (" . $ids . ") ";
				break;
		}
		$filters = new stdClass();
		$filters->join = $join;
		$filters->and = $and;
		
		return $filters;
	}
}