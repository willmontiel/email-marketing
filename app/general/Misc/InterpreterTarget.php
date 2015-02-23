<?php

namespace EmailMarketing\General\Misc;
/**
 * Interprets a json object and convert it in a sql consult
 * @author Will
 */
class InterpreterTarget 
{
	protected $joinsForFilters = "";
	protected $conditionsForFilters = "";
	protected $SQLFilterMail = "";
	protected $topObject;
	protected $listObject;
	protected $account;
	protected $logger;
	protected $mail;
	protected $data = array();
	protected $ids;
	protected $idsArray;
	protected $SQLForContacts = "";
	protected $conditionsWhenIsDbase = "";
	protected $sql;
	protected $statDbaseSQL = "";
	protected $statContactlistSQL = "";
	protected $contactsSQL = "";
	protected $completeContactsSQL = "";
	protected $idsContactsSQL = "";

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
					$values = $this->implode();
					
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
					$this->SQLForContacts = "(SELECT co.idContact, co.idDbase, co.idEmail, co.name, co.lastName, DATE_FORMAT(co.birthDate, '%m-%d') AS birthDate, co.unsubscribed FROM contact co JOIN coxcl cl ON (co.idContact = cl.idContact) WHERE cl.idContactlist IN ({$this->ids}) GROUP BY 1, 2, 3, 4, 5, 6, 7)";
					
					/**
					* Inserting data into statcontactlist for statistics
					*/
				   $values = $this->implode();

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
					$this->SQLForContacts = "(SELECT co.idContact, co.idDbase, co.idEmail, co.name, co.lastName, DATE_FORMAT(co.birthDate, '%m-%d') AS birthDate, co.unsubscribed FROM contact co JOIN sxc s ON (co.idContact = s.idContact) WHERE s.idSegment IN ({$this->ids}) GROUP BY 1, 2, 3, 4, 5, 6, 7)";
					
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
	
	private function implode()
	{
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
		
		return $values;
	}

	public function modelData()
	{
		$this->logger->log("Data " . print_r($this->data, true));
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
						$this->names = implode(',' , $data->serialization->names);
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
		$from = array();
		$where = array();
		
		$i = 0;
		foreach ($this->data as $data) {
			if ($data->type == 'filter-panel') {
				$i++;
			}
		}
		
		$object = new \stdClass();
		$object->required = ($this->listObject->serialization->conditions == 'all' ? true : false);
		$object->more = ($i > 1 ? true : false);
		
		$key = 1;
		foreach ($this->data as $data) {
			if ($data->type == 'filter-panel') {
				$object->id = $data->serialization->items;
				$object->negative = ($data->serialization->negation == 'true' ? true : false);
				
				switch ($data->serialization->type) {
					case 'mail-sent':
						$object->type = "mail";
						$filter = new \EmailMarketing\General\Filter\FilterSent();
						break;

					case 'mail-open':
						$object->type = "mail";
						$filter = new \EmailMarketing\General\Filter\FilterOpening();
						break;

					case 'click':
						$object->type = "click";
						$object->idMail = $data->serialization->idMail;
						$filter = new \EmailMarketing\General\Filter\FilterClicks();
						break;
				}
				
				$filter->setObject($object);
				$filter->setKey($key);
				$filter->createSQL();

				$from[] = $filter->getFrom();
				$w = $filter->getWhere();

				if (!empty($w)) {
					$where[] = $w;
				}
			}
			$key++;
		}
		
		if (count($from) > 0) {
			$this->joinsForFilters = implode(" ", $from);
		}
		
		if (count($where) > 0) {
			$glue = ($object->required ? ' AND ' : ' OR ');
			$conditions = implode($glue, $where);
			$this->conditionsForFilters = "AND ({$conditions})";
		}
//		
//		$this->logger->log("From: {$this->joinsForFilters}");
//		$this->logger->log("Where: {$this->conditionsForFilters}");
	}

	private function createSQLBaseForTotalContacts()
	{
		$this->sql = "SELECT COUNT(c.idContact) AS total 
						  FROM {$this->SQLForContacts} AS c 
						  JOIN email AS e ON (e.idEmail = c.idEmail) 
						  {$this->joinsForFilters} 
					  WHERE {$this->conditionsWhenIsDbase} c.unsubscribed = 0 
						  AND e.bounced = 0 
						  AND e.blocked = 0 
						  AND e.spam = 0 
					  {$this->conditionsForFilters}";
	}
	
	private function createSQLBaseForTarget()
	{
		$this->completeContactsSQL = "SELECT c.idContact, c.idDbase, c.name, c.lastName, c.birthDate, (SELECT GROUP_CONCAT(idContactlist) FROM coxcl x WHERE x.idContact = c.idContact) AS listas
						  FROM {$this->SQLForContacts} AS c 
						  JOIN email AS e ON (e.idEmail = c.idEmail) 
						  {$this->joinsForFilters}
					  WHERE {$this->conditionsWhenIsDbase} c.unsubscribed = 0 AND e.bounced = 0 AND e.blocked = 0 AND e.spam = 0 {$this->conditionsForFilters}";
					  
		$this->idsContactsSQL = "SELECT c.idContact
						  FROM {$this->SQLForContacts} AS c 
						  JOIN email AS e ON (e.idEmail = c.idEmail) 
						  {$this->joinsForFilters} 
					  WHERE {$this->conditionsWhenIsDbase} c.unsubscribed = 0 AND e.bounced = 0 AND e.blocked = 0 AND e.spam = 0 {$this->conditionsForFilters}";
					  
					  
					  
					  
		$sql = "SELECT {$this->mail->idMail}, c.idContact, null, 'scheduled', 0, 0, 0, 0, 0, (SELECT GROUP_CONCAT(idContactlist) FROM coxcl x WHERE x.idContact = c.idContact) AS listas, 0, 0, 0, 0, 0, 0, 0, 0
						  FROM {$this->SQLForContacts} AS c 
						  JOIN email AS e ON (e.idEmail = c.idEmail) 
						  {$this->joinsForFilters} 
					  WHERE {$this->conditionsWhenIsDbase} c.unsubscribed = 0 AND e.bounced = 0 AND e.blocked = 0 AND e.spam = 0 {$this->conditionsForFilters}";
						  
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
	
	public function createModel()
	{
		$this->modelData();
	}
	
	public function getCriteria()
	{
		return $this->criteria;
	}
	
	public function getIds()
	{
		return $this->idsArray;
	}
	
	public function getSQLForSearchContacts()
	{
		return $this->completeContactsSQL;
	}

	public function getSQLForSearchIdContacts()
	{
		return $this->idsContactsSQL;
	}
	
	public function getNames()
	{
		return $this->names;
	}
}

