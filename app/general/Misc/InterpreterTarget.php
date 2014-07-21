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
	protected $SQLForIdContacts;
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
		$this->createSQLBaseForTotalContacts();
		$this->createSQLForFilters();
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
		array_splice($this->data, 0, 2);
		
		$first = true;
		$i = 1;
		foreach ($this->data as $data) {
			switch ($data['serialization']['type']) {
				case 'click':
					$this->SQLForIdContacts = "SELECT idContact FROM contact WHERE idDbase IN ({$ids})";
					break;

				case 'open-sent':
				
					break;
				
				case 'open-view':
					$this->joinForFilters .= " JOIN Mxc AS mc{$i} ON (mc{$i}.idContact = c.idContact AND mc{$i}.idMail = {$data['serialization']['items']})";
					
					if ($first) {
						$this->conditions .= " mc{$i} != 0";
					}
					else {
						$this->conditions .= " {$condition} mc{$i} != 0";
					}
					break;
			}
			
			$first = false;
		}
	}

	private function createSQLBaseForTotalContacts()
	{
		$this->sql = "SELECT COUNT(c.idContact) AS total
						  FROM ({$this->SQLForIdContacts}) AS c
						  JOIN contact AS co ON (co.idContact = c.idContact)	 
							 {$this->joinForFilters}
							 WHERE 
							 {$this->Conditions} AND co.unsubscribe = 0";
	}
	
	private function executeSQL()
	{
		$this->logger->log("SQL: " . print_r($this->sql, true));
		
		$db = Phalcon\DI::getDefault()->get('db');
		$result = $db->query($this->sql);
		
		$this->result = $result->fetchAll();
	}
	
	public function getTotalContacts()
	{
		return $this->result->getFirst()->total;
	}
}

