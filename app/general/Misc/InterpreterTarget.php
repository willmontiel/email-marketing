<?php

namespace EmailMarketing\General\Misc;
/**
 * Interprets a json object and convert it in a sql consult
 * @author Will
 */
class InterpreterTarget 
{
	protected $topObject;
	protected $listObject;
	protected $account;
	protected $logger;
	protected $mail;
	protected $data;
	protected $result;
	protected $SQLForIdContacts = "";
	protected $joinForFilters = "";
	protected $conditions = "";
	protected $sql;

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
		$this->createSQLForIdContacts();
		$this->createSQLForFilters();
		$this->createSQLBaseForTotalContacts();
		$this->executeSQL();
	}
	
	public function searchContacts()
	{
		$this->data = $this->mail->target;
		$this->createSQLForIdContacts();
		$this->createSQLForFilters();
		$this->createSQLBaseForTarget();
//		$this->executeSQL();
	}
	
	private function createSQLForIdContacts()
	{
		$this->logger->log("Data: " . print_r($this->data, true));
		$this->modelData();
		
		if ($this->top && $this->list) {
			switch ($this->criteria) {
				case 'dbases':
					$this->SQLForIdContacts = "SELECT DISTINCT idContact FROM contact WHERE idDbase IN ({$this->ids})";
					break;

				case 'contactlists':
					$this->SQLForIdContacts = "SELECT DISTINCT idContact FROM coxcl WHERE idContactlist IN ({$this->ids})";
					break;

				case 'segments':
					$this->SQLForIdContacts = "SELECT DISTINCT idContact FROM sxc WHERE idSegment IN ({$this->ids})";
					break;
			}	
		}
	}
	
	private function modelData()
	{
		$this->top = false;
		$this->list = false;
		
		foreach ($this->data as $data) {
			if ($data['type'] == 'top-panel') {
				$this->topObject = $data;
				$this->criteria = $data['serialization']['criteria'];
				$this->top  = true;
			}
			else if ($data['type'] == 'list-panel'){
				$this->listObject = $data;
				if (isset($data['serialization']['items'])) {
					if (count($data['serialization']['items']) > 0) {
						$this->ids = implode(',' , $data['serialization']['items']);
						$this->list = true;
					}
				}
			}
		}
		
		
	}
	
	private function createSQLForFilters()
	{
		$condition = ($this->topObject['serialization']['conditions'] == 'all' ? 'AND' : 'OR');
		$first = true;
		$i = 1;
	
		$piece = "";
		
		foreach ($this->data as $data) {
			if ($data['type'] == 'filter-panel') {
				switch ($data['serialization']['type']) {
					case 'mail-sent':
						$this->joinForFilters .= " JOIN mxc AS mc{$i} ON (mc{$i}.idContact = c.idContact AND mc{$i}.idMail = {$data['serialization']['items']})";
						break;

					case 'mail-open':
						$this->joinForFilters .= " JOIN mxc AS mc{$i} ON (mc{$i}.idContact = c.idContact AND mc{$i}.idMail = {$data['serialization']['items']})";

						if ($first) {
							$piece .= " COALESCE(mc{$i}.opening, 0) != 0";
						}
						else {
							$piece .= " {$condition} COALESCE(mc{$i}.opening, 0) != 0";
						}

						$first = false;
						break;

					case 'click':
						$this->joinForFilters .= " JOIN mxcxl AS ml{$i} ON (ml{$i}.idContact = c.idContact AND ml{$i}.idMailLink = {$data['serialization']['items']})";
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
						  FROM ({$this->SQLForIdContacts}) AS c 
						  JOIN contact AS co ON (co.idContact = c.idContact) 
						  JOIN email AS e ON (e.idEmail = co.idEmail) 
						  {$this->joinForFilters} 
					  WHERE co.unsubscribed = 0 AND e.bounced = 0 AND e.blocked = 0 AND e.spam = 0 
						  {$this->conditions} ";
	}
	
	private function createSQLBaseForTarget()
	{
		$sql = "SELECT {$this->mail->idMail}, co.idContact, null, 'scheduled', 0, 0, 0, 0, 0, GROUP_CONCAT(l.idContactlist), 0, 0, 0, 0, 0, 0, 0, 0
						  FROM ({$this->SQLForIdContacts}) AS c 
						  JOIN contact AS co ON (co.idContact = c.idContact) 
						  JOIN coxcl AS l ON (co.idContact = l.idContact)
						  JOIN email AS e ON (e.idEmail = co.idEmail) 
						  {$this->joinForFilters} 
					  WHERE co.unsubscribed = 0 AND e.bounced = 0 AND e.blocked = 0 AND e.spam = 0 
						  {$this->conditions} ";
						  
		$this->sql = "INSERT IGNORE INTO mxc (idMail, idContact, idBouncedCode, status, opening, clicks, bounced, 
											  spam, unsubscribe, contactlists, share_fb, share_tw, share_gp, share_li,
											  open_fb, open_tw, open_gp, open_li) VALUES ({$sql})";
											  
		$this->logger->log("SQL: {$sql}");									  
	}
	
	private function executeSQL()
	{
		$this->logger->log("SQL: " . print_r($this->sql, true));
		
		if ($this->SQLForIdContacts != "") {
			$db = \Phalcon\DI::getDefault()->get('db');
			$result = $db->query($this->sql);
			$this->result = $result->fetchAll();
		}
		else {
			$this->result = array(
				0 => array('total' => 0)
			);
		}
	}
	
	public function getTotalContacts()
	{
		return array('totalContacts' => $this->result[0]['total']);
	}
}

