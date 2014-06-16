<?php
class IdentifyTarget
{
	public $mail;
	public $identifiers;
	public $target;
	public $sql;
	
	public function __construct() 
	{
		$this->log = Phalcon\DI::getDefault()->get('logger');
		$this->db = Phalcon\DI::getDefault()->get('db');
	}
	
	public function setMail(Mail $mail)
	{
		$this->mail = $mail;
	}
	
	public function processData()
	{
		$this->validateMail();
		
		if (!empty($this->target->filter)){
			$this->processTarget(true);
		}
		else {
			$this->processTarget();
		}
	}
	
	
	public function getTotalContacts()
	{
		$query = $this->db->query($this->sql->count);
		$total = $query->fetchAll();
		
		$this->log->log("Total: " . print_r($total, true));
		$this->log->log("Total: " . print_r($total[0]['total'], true));
		return $total[0]['total'];
	}
	
	public function saveTarget()
	{
		$destination = $this->db->execute($this->sql->general);
		$statcontactlist = $this->db->execute( $this->sql->contactlist);
		$statdbase = $this->db->execute( $this->sql->dbase);
		
		if (!$destination || !$statcontactlist || !$statdbase) {
			throw new Exception('Error while consulting recipients');
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
		$generalSql = "INSERT IGNORE INTO mxc (idMail, idContact, idBouncedCode, status, opening, clicks, bounced, spam, unsubscribe, contactlists, share_fb, share_tw, share_gp, share_li, open_fb, open_tw, open_gp, open_li)";
		switch ($this->target->destination) {
			case 'dbases':
				$this->getSqlByDbase($filter);
				break;
			
			case 'contactlists':
				$this->getSqlByContactList($filter);
				break;
				
			case 'segments':
				$this->getSqlBySegment($filter);
				break;
		}
		
		$generalSql .= $this->sql->general;
		$this->sql->general = $generalSql;
		
		$this->log->log("SQL General: {$this->sql->general}");
		$this->log->log("SQL Contactlist: {$this->sql->contactlist}");
		$this->log->log("SQL Dbase: {$this->sql->dbase}");
		$this->log->log("SQL Count: {$this->sql->count}");
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
		
		$sql1 = "(SELECT {$this->mail->idMail}, c.idContact, null, 'scheduled', 0, 0, 0, 0, 0, GROUP_CONCAT(l.idContactlist), 0, 0, 0, 0, 0, 0, 0, 0
				  FROM contact AS c 
					 JOIN email AS e ON (c.idEmail = e.idEmail) 
					 JOIN coxcl AS l ON (c.idContact = l.idContact) {$filters->join}
					 WHERE c.idDbase IN ({$this->identifiers}) {$filters->and} AND e.bounced = 0 AND e.spam = 0 AND e.blocked = 0 AND c.unsubscribed = 0 
				   GROUP BY 1, 2)";
		/**
		 * Inserting data into statdbase for statistics.
		 */
		$values = ' ';
		$comma = true;
		foreach ($this->target->ids as $id) {
			if ($comma) {
				$values .= "({$id}, {$this->mail->idMail}, 0, 0, 0, 0, 0, {$this->mail->totalContacts}, " . time() . ")";
				$comma = false;
			}
			else{
				$values .= ", ({$id}, {$this->mail->idMail}, 0, 0, 0, 0, 0, {$this->mail->totalContacts}, " . time() . ")";
			}
		}
		$sql2 = "INSERT INTO statdbase VALUES {$values}";
		/**
		 * Inserting data into statcontactlist for statistics
		 */
		$sql3 = "INSERT IGNORE INTO statcontactlist 
					(SELECT c.idContactlist, {$this->mail->idMail}, 0, 0, 0, 0, 0, {$this->mail->totalContacts}, ' . time() . '
					 FROM coxcl AS c
					    JOIN mxc AS m ON (m.idContact = c.idContact )
					 WHERE m.idMail = {$this->mail->idMail})";
		
		$sql4 = "(SELECT COUNT(c.idContact) AS total
				  FROM contact AS c 
					 JOIN email AS e ON (c.idEmail = e.idEmail) 
					 {$filters->join}
					 WHERE c.idDbase IN ({$this->identifiers}) {$filters->and} AND e.bounced = 0 AND e.spam = 0 AND e.blocked = 0 AND c.unsubscribed = 0)";
		
		$this->sql = new stdClass();
		
		$this->sql->general = $sql1;
		$this->sql->contactlist = $sql2;
		$this->sql->dbase = $sql3;
		$this->sql->count = $sql4;
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
		
		$sql1 = "(SELECT {$this->mail->idMail}, cl.idContact, null, 'scheduled', 0, 0, 0, 0, 0, GROUP_CONCAT(cl.idContactlist), 0, 0, 0, 0, 0, 0, 0, 0
				  FROM coxcl AS cl
					 JOIN contact AS c ON (cl.idContact = c.idContact)
				     JOIN email AS e ON (c.idEmail = e.idEmail) {$filters->join} 
				     WHERE cl.idContactlist IN ({$this->identifiers}) {$filters->and} AND e.bounced = 0 AND e.spam = 0 AND e.blocked = 0 AND c.unsubscribed = 0
				  GROUP BY 1, 2)";

		/**
		 * Inserting data into statcontactlist for statistics
		 */
		$values = ' ';
		$comma = true;
		foreach ($this->target->ids as $id) {
			if ($comma) {
				$values .= "({$id}, {$this->mail->idMail}, 0, 0, 0, 0, 0, {$this->mail->totalContacts}, " . time() . ")";
				$comma = false;
			}
			else{
				$values .= ", ({$id}, {$this->mail->idMail}, 0, 0, 0, 0, 0, {$this->mail->totalContacts}, " . time() . ")";
			}
		}

		$sql2 = "INSERT IGNORE INTO statcontactlist VALUES {$values}";

		$sql3 = "INSERT IGNORE INTO statdbase 
					(SELECT c.idDbase, {$this->mail->idMail}, 0, 0, 0, 0, 0, {$this->mail->totalContacts}, " . time() . "
						FROM contact AS c
						JOIN mxc AS m ON (m.idContact = c.idContact)
					WHERE m.idMail = {$this->mail->idMail})";
		
		$sql4 = "(SELECT COUNT(c.idContact) AS total
				  FROM coxcl AS cl
					 JOIN contact AS c ON (cl.idContact = c.idContact)
				     JOIN email AS e ON (c.idEmail = e.idEmail) {$filters->join} 
				     WHERE cl.idContactlist IN ({$this->identifiers}) {$filters->and} AND e.bounced = 0 AND e.spam = 0 AND e.blocked = 0 AND c.unsubscribed = 0)";
		
		$this->sql = new stdClass();
		
		$this->sql->general = $sql1;
		$this->sql->contactlist = $sql2;
		$this->sql->dbase = $sql3;
		$this->sql->count = $sql4;
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
		
		$sql1 = "(SELECT {$this->mail->idMail}, sc.idContact, null, 'scheduled', 0, 0, 0, 0, 0, GROUP_CONCAT(l.idContactlist), 0, 0, 0, 0, 0, 0, 0, 0
					FROM sxc AS sc
						JOIN contact AS c ON (sc.idContact = c.idContact)
						JOIN email AS e ON (c.idEmail = e.idEmail)
						JOIN coxcl AS l ON (c.idContact = l.idContact) {$filters->join} 
					WHERE sc.idSegment IN ({$this->identifiers}) {$filters->and} AND e.bounced = 0 AND e.spam = 0 AND e.blocked = 0 AND c.unsubscribed = 0
					GROUP BY 1, 2)";

		$sql2 = "INSERT IGNORE INTO statcontactlist 
					(SELECT c.idContactlist, {$this->mail->idMail}, 0, 0, 0, 0, 0, {$this->mail->totalContacts}, " . time() . "
						FROM coxcl AS c
						   JOIN mxc AS m ON (m.idContact = c.idContact )
						WHERE m.idMail = {$this->mail->idMail}) ";

		$sql3 = "INSERT IGNORE INTO statdbase 
					(SELECT c.idDbase, {$this->mail->idMail}, 0, 0, 0, 0, 0, {$this->mail->totalContacts}, " . time() . "
						FROM contact AS c
						JOIN mxc AS m ON (m.idContact = c.idContact)
					WHERE m.idMail = {$this->mail->idMail} LIMIT 0, 1)";
		
		$sql4 = "(SELECT COUNT(c.idContact) AS total
					FROM sxc AS sc
						JOIN contact AS c ON (sc.idContact = c.idContact)
						JOIN email AS e ON (c.idEmail = e.idEmail)
						{$filters->join} 
					WHERE sc.idSegment IN ({$this->identifiers}) {$filters->and} AND e.bounced = 0 AND e.spam = 0 AND e.blocked = 0 AND c.unsubscribed = 0)";
		
		$this->sql = new stdClass();
		
		$this->sql->general = $sql1;
		$this->sql->contactlist = $sql2;
		$this->sql->dbase = $sql3;
		$this->sql->count = $sql4;
	}
	
	private function getFilterSql()
	{
		if (is_array($this->target->filter->criteria)) {
			$ids = implode(',', $this->target->filter->criteria);
		}
		else {
			$ids = $this->target->filter->criteria;
		}
		
		switch ($this->target->filter->type) {
			case 'email':
				$join = '';
				$and = " AND e.email = '{$this->target->filter->criteria}' ";
				break;
			
			case 'open':
				$join = " JOIN mxc AS m ON (m.idContact = c.idContact) ";
				$and = " AND m.idMail IN ({$ids}) AND m.opening != 0 ";
				break;
			
			case 'click':
				$join = " JOIN mxcxl AS m ON (m.idContact = c.idContact) ";
				$and = " AND m.idMailLink IN ({$ids}) ";
				break;
			
			case 'mailExclude':
				$join = " LEFT OUTER JOIN mxc AS m ON (m.idContact = c.idContact AND m.idMail IN ({$ids})) ";
				$and = " AND (m.idContact IS NULL OR m.opening = 0)";
				break;
		}
		$filters = new stdClass();
		$filters->join = $join;
		$filters->and = $and;
		
		return $filters;
	}
}