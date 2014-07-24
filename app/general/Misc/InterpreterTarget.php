<?php

namespace EmailMarketing\General\Misc;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of InterpreterTarget
 *
 * @author Will
 */
class InterpreterTarget 
{
	protected $account;
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
	
	public function searchTotalContacts()
	{
		$this->createSQLForIdContacts();
		$this->createSQLForFilters();
		$this->createSQLBaseForTotalContacts();
		$this->executeSQL();
	}
	
	private function createSQLForIdContacts()
	{
		if (isset($this->data[1])) {
			$ids = implode(',' , $this->data[1]['serialization']['items']);

			switch ($this->data[0]['serialization']['criteria']) {
				case 'dbases':
					$this->SQLForIdContacts = "SELECT DISTINCT idContact FROM contact WHERE idDbase IN ({$ids})";
					break;

				case 'contactlists':
					$this->SQLForIdContacts = "SELECT DISTINCT idContact FROM coxcl WHERE idContactlist IN ({$ids})";
					break;

				case 'segments':
					$this->SQLForIdContacts = "SELECT DISTINCT idContact FROM sxc WHERE idSegment IN ({$ids})";
					break;
			}	
		}
	}
	
	private function createSQLForFilters()
	{
		$condition = ($this->data[1]['serialization']['conditions'] == 'all' ? 'AND' : 'OR');
//		$condition = $this->data[1]['serialization']['conditions'];
		array_splice($this->data, 0, 2);

		$first = true;
		$i = 1;
	
		$piece = "";
		
		foreach ($this->data as $data) {
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
			
			$i++;
		}
		
		$this->conditions = ($piece == "" ? "" : " AND ({$piece}) ");
		
		$this->logger->log("Joins: {$this->joinForFilters}");
		$this->logger->log("Conditions: {$this->conditions}");
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

