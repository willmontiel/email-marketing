<?php

namespace EmailMarketing\General\Misc;
/**
 * Interprets a json object and convert it in a sql consult
 * @author Will
 */
class InterpreterTarget 
{
	protected $SQLFilterMail = "";
	protected $topObject;
	protected $listObject;
	protected $account;
	protected $logger;
	protected $mail;
	protected $data;
	protected $ids;
	protected $idsArray;
	protected $result;
	protected $SQLForContacts = "";
	protected $joinForFilters = "";
	protected $conditions = "";
	protected $conditionsWhenIsDbase = "";
	protected $sql;
	protected $statDbaseSQL = "";
	protected $statContactlistSQL = "";

	public function __construct()
	{
		$this->logger = \Phalcon\DI::getDefault()->get('logger');
	}
	
	public function setAccount(\Account $account)
	{
		$this->account = $account;
	}
	
	public function setData($data)
	{
		$this->data = $data;
	}
	
	public function setMail(\Mail $mail)
	{
		$this->mail = $mail;
	}
	
	public function searchTotalContacts()
	{
		$this->createSQLByCriteria();
		$this->createSQLForFilters();
		$this->createSQLBaseForTotalContacts();
	}
	
	public function searchContacts()
	{
		$this->data = json_decode($this->mail->target);
		$this->createSQLByCriteria();
		$this->createSQLForFilters();
		$this->createSQLBaseForTarget();
	}
	
	private function createSQLByCriteria()
	{
		$this->modelData();
		
		if ($this->top && $this->list) {
			switch ($this->criteria) {
				case 'dbases':
					$this->SQLFilterMail = " JOIN contact AS c ON (c.idContact = mc.idContact) WHERE c.idDbase IN ({$this->ids}) AND m.status = 'Sent' GROUP BY 1,2,3,4";
					$this->SQLForContacts = "contact";
					$this->conditionsWhenIsDbase .= "c.idDbase = {$this->ids} AND ";
					
					
					/**
					* SQL for insert data into statdbase for statistics.
					*/
					$values = ' ';
					$comma = true;
					foreach ($this->idsArray as $id) {
						if ($comma) {
							$values .= "({$id}, {$this->mail->idMail}, 0, 0, 0, 0, 0, {$this->mail->totalContacts}, " .time() .")";
							$comma = false;
						}
						else{
							$values .= ", ({$id}, {$this->mail->idMail}, 0, 0, 0, 0, 0, {$this->mail->totalContacts}, " .time() .")";
						}
					}
					
					$this->statDbaseSQL = "INSERT INTO statdbase (idDbase, idMail, uniqueOpens, clicks, bounced, spam, unsubscribed, sent, sentDate) 
											   VALUES {$values}";
					/**
					* SQL for insert data into statcontactlist for statistics
					*/
				    $this->statContactlistSQL = "INSERT IGNORE INTO statcontactlist (idContactlist, idMail, uniqueOpens,clicks, bounced, spam, unsubscribed, sent, sentDate) 
													(SELECT c.idContactlist, {$this->mail->idMail}, 0, 0, 0, 0, 0, {$this->mail->totalContacts}, " . time() . "
													 FROM coxcl AS c
														JOIN mxc AS m ON (m.idContact = c.idContact )
													 WHERE m.idMail = {$this->mail->idMail})";
					
					
					break;

				case 'contactlists':
					$this->SQLFilterMail = " JOIN coxcl AS lc ON (lc.idContact = mc.idContact) WHERE lc.idContactlist IN ({$this->ids}) AND m.status = 'Sent' GROUP BY 1,2,3,4";
					$this->SQLForContacts = "(SELECT co.idContact, co.idEmail, co.unsubscribed FROM contact co JOIN coxcl cl ON (co.idContact = cl.idContact) WHERE cl.idContactlist IN ({$this->ids}) GROUP BY 1, 2, 3)";
					
					/**
					* Inserting data into statcontactlist for statistics
					*/
				   $values = ' ';
				   $comma = true;
				   foreach ($this->idsArray as $id) {
					   if ($comma) {
						   $values .= "({$id}, {$this->mail->idMail}, 0, 0, 0, 0, 0, {$this->mail->totalContacts}, " . time() . ")";
						   $comma = false;
					   }
					   else{
						   $values .= ", ({$id}, {$this->mail->idMail}, 0, 0, 0, 0, 0, {$this->mail->totalContacts}, " . time() . ")";
					   }
				   }

				   $this->statContactlistSQL = "INSERT IGNORE INTO statcontactlist (idContactlist, idMail, uniqueOpens,clicks, bounced, spam, unsubscribed, sent, sentDate) 
													VALUES {$values}";

				   $this->statDbaseSQL = "INSERT IGNORE INTO statdbase (idDbase, idMail, uniqueOpens, clicks, bounced, spam, unsubscribed, sent, sentDate) 
										  (SELECT c.idDbase, {$this->mail->idMail}, 0, 0, 0, 0, 0, {$this->mail->totalContacts}, " . time() . "
											 FROM contact AS c
												JOIN mxc AS m ON (m.idContact = c.idContact)
											 WHERE m.idMail = {$this->mail->idMail})";
					break;

				case 'segments':
					$this->SQLFilterMail = " JOIN sxc AS sc ON (sc.idContact = mc.idContact) WHERE sc.idSegment IN ({$this->ids}) AND m.status = 'Sent' GROUP BY 1,2,3,4";
					$this->SQLForContacts = "(SELECT co.idContact, co.idEmail, co.unsubscribed FROM contact co JOIN sxc s ON (co.idContact = s.idContact) WHERE s.idSegment IN ({$this->ids}) GROUP BY 1, 2, 3)";
					
					$this->statContactlistSQL = "INSERT IGNORE INTO statcontactlist (idContactlist, idMail, uniqueOpens,clicks, bounced, spam, unsubscribed, sent, sentDate) 
													(SELECT c.idContactlist, {$this->mail->idMail}, 0, 0, 0, 0, 0, {$this->mail->totalContacts}, " . time() ."
													 FROM coxcl AS c
														   JOIN mxc AS m ON (m.idContact = c.idContact )
													 WHERE m.idMail = {$this->mail->idMail})";

					$this->statDbaseSQL = "INSERT IGNORE INTO statdbase (idDbase, idMail, uniqueOpens, clicks, bounced, spam, unsubscribed, sent, sentDate) 
										   (SELECT c.idDbase, {$this->mail->idMail}, 0, 0, 0, 0, 0, {$this->mail->totalContacts}, " . time() . "
												FROM contact AS c
												JOIN mxc AS m ON (m.idContact = c.idContact)
											WHERE m.idMail = {$this->mail->idMail} LIMIT 0, 1)";
					break;
			}	
		}
	}
	
	private function modelData()
	{
		$this->top = false;
		$this->list = false;
		
		foreach ($this->data as $data) {
			if ($data->type == 'top-panel') {
				$this->topObject = $data;
				$this->criteria = $data->serialization->criteria;
				$this->top  = true;
			}
			else if ($data->type == 'list-panel'){
				$this->listObject = $data;
				if (isset($data->serialization->items)) {
					if (count($data->serialization->items) > 0) {
						$this->idsArray = $data->serialization->items;
						$this->ids = implode(',' , $data->serialization->items);
						$this->list = true;
					}
				}
			}
		}
	}
	
	public function searchMailFilter()
	{
		$this->createSQLByCriteria();
		
		$this->sql = "SELECT m.idMail AS id, m.name AS name, m.subject AS subject, m.startedon AS date
					  FROM mail AS m
						  JOIN mxc AS mc ON (mc.idMail = m.idMail) {$this->SQLFilterMail}";
	}
	
	public function searchMailsWithClicksFilter() 
	{
		$this->createSQLByCriteria();
		
		$this->sql = "SELECT m.idMail AS id, m.name AS name, m.subject AS subject, m.startedon AS date
						 FROM mail AS m
						 JOIN mxl AS l ON (l.idMail = m.idMail)
						 JOIN mxc AS mc ON (mc.idMail = m.idMail) {$this->SQLFilterMail}";
	}
	
	public function searchClicksFilter()
	{
		if (!empty($this->data['idMail'])) {
			$this->sql = "SELECT l.idMailLink AS id, l.link AS name
						  FROM mxl AS ml
						  JOIN maillink AS l ON (l.idMailLink = ml.idMailLink)
					  WHERE ml.idMail = {$this->data['idMail']}";
					  
			$this->top = true;
			$this->list = true;
		}
	}
	
	private function createSQLForFilters()
	{
		$condition = ($this->listObject->serialization->conditions == 'all' ? 'AND' : 'OR');
		$first = true;
		$i = 1;
	
		$piece = "";
		
		foreach ($this->data as $data) {
			if ($data->type == 'filter-panel') {
				switch ($data->serialization->type) {
					case 'mail-sent':
						$this->joinForFilters .= " LEFT JOIN mxc AS mc{$i} ON (mc{$i}.idContact = c.idContact AND mc{$i}.idMail = {$data->serialization->items})";
						
						if ($first) {
							$piece .= " mc{$i}.idContact IS NOT NULL ";
						}
						else {
							$piece .= " {$condition} mc{$i}.idContact IS NOT NULL ";
						}

						$first = false;
						break;

					case 'mail-open':
						$this->joinForFilters .= " LEFT JOIN mxc AS mc{$i} ON (mc{$i}.idContact = c.idContact AND mc{$i}.idMail = {$data->serialization->items} AND mc{$i}.opening != 0)";

						if ($first) {
							$piece .= " mc{$i}.idContact IS NOT NULL ";
						}
						else {
							$piece .= " {$condition} mc{$i}.idContact IS NOT NULL ";
						}

						$first = false;
						break;

					case 'click':
						$this->joinForFilters .= " JOIN mxcxl AS ml{$i} ON (ml{$i}.idContact = c.idContact AND ml{$i}.idMailLink = {$data->serialization->items})";
						break;
				}
			}
			$i++;
		}
		
		$this->conditions = ($piece == "" ? "" : " AND ({$piece}) ");
	}

	private function createSQLBaseForTotalContacts()
	{
		$this->sql = "SELECT COUNT(c.idContact) AS total 
						  FROM {$this->SQLForContacts} AS c 
						  JOIN email AS e ON (e.idEmail = c.idEmail) 
						  {$this->joinForFilters} 
					  WHERE {$this->conditionsWhenIsDbase} c.unsubscribed = 0 AND e.bounced = 0 AND e.blocked = 0 AND e.spam = 0 {$this->conditions}";
	}
	
	private function createSQLBaseForTarget()
	{
		$sql = "SELECT {$this->mail->idMail}, c.idContact, null, 'scheduled', 0, 0, 0, 0, 0, (SELECT GROUP_CONCAT(idContactlist) FROM coxcl x WHERE x.idContact = c.idContact) AS listas, 0, 0, 0, 0, 0, 0, 0, 0
						  FROM {$this->SQLForContacts} AS c 
						  JOIN email AS e ON (e.idEmail = c.idEmail) 
						  {$this->joinForFilters} 
					  WHERE {$this->conditionsWhenIsDbase} c.unsubscribed = 0 AND e.bounced = 0 AND e.blocked = 0 AND e.spam = 0 {$this->conditions}";
						  
		$this->sql = "INSERT IGNORE INTO mxc (idMail, idContact, idBouncedCode, status, opening, clicks, bounced, 
											  spam, unsubscribe, contactlists, share_fb, share_tw, share_gp, share_li,
											  open_fb, open_tw, open_gp, open_li) {$sql}";			  
	}
	
	
	public function getSQL()
	{
		if (!$this->top or !$this->list) {
			return false;
		}
		return $this->sql;
	}
	
	public function getStatDbaseSQL()
	{
		return $this->statDbaseSQL;
	}
	
	public function getStatContactlistSQL()
	{
		return $this->statContactlistSQL;
	}
}

