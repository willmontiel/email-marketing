<?php

class TargetWrapper extends BaseWrapper
{
	protected $data;
	protected $dbase;
	protected $filter = array();
	protected $model;
	protected $sql;
	protected $SQLfilter;
	protected $totalContacts;

	public function __construct() 
	{
		$this->logger = Phalcon\DI::getDefault()->get('logger');
	}
	
	public function setDbase(Dbase $dbase)
	{
		$this->dbase = $dbase;
	}
	
	public function setAccount(Account $account)
	{
		$this->account = $account;
	}
	
	public function setData($data)
	{
		$this->data = $data;
	}
	
	private function createSQLFilter()
	{
		if (isset($this->data[1])) {
			$ids = implode(',' , $this->data[1]['serialization']['items']);
		
			$this->SQLfilter = new stdClass();

			switch ($this->data[0]['serialization']['criteria']) {
				case 'dbases':
					$this->SQLfilter->mail = " JOIN Contact AS c ON (c.idContact = mc.idContact) WHERE c.idDbase IN ({$ids}) AND m.status = 'Sent' GROUP BY 1,2,3 ";
					$this->SQLfilter->click = " JOIN Dbase AS d ON (d.idDbase = c.idDbase) WHERE d.idDbase IN ({$ids}) GROUP BY 1,2";
					$this->SQLfilter->totalContacts = " WHERE idDbase IN ({$ids})";
					break;

				case 'contactlists':
					$this->SQLfilter->mail = " JOIN Coxcl AS lc ON (lc.idContact = mc.idContact) WHERE lc.idContactlist IN ({$ids}) AND m.status = 'Sent' GROUP BY 1,2,3";
					$this->SQLfilter->click = " JOIN Coxcl AS cl ON (cl.idContact = c.idContact) WHERE cl.idContactlist IN ({$ids}) GROUP BY 1,2";
					$this->SQLfilter->totalContacts = " JOIN Coxcl AS cl ON (cl.idContact = c.idContact) WHERE idContactlist IN ({$ids})";
					break;

				case 'segments':
					$this->SQLfilter->mail = " JOIN Sxc AS sc ON (sc.idContact = mc.idContact) WHERE sc.idSegment IN ({$ids}) AND m.status = 'Sent' GROUP BY 1,2,3";
					$this->SQLfilter->click = " JOIN Sxc AS s ON (s.idContact = c.idContact) WHERE s.idSegment IN ({$ids}) GROUP BY 1,2 ";
					$this->SQLfilter->totalContacts = " JOIN Sxc AS s ON (s.idContact = c.idContact) WHERE idSegment IN ({$ids})";
					break;
			}	
		}
	}
	
	public function searchMailFilter()
	{
		$this->createSQLFilter();
		
		$this->sql = "SELECT m.idMail AS id, m.name AS name, m.previewData AS preview
					  FROM Mail AS m
						  JOIN Mxc AS mc ON (mc.idMail = m.idMail) {$this->SQLfilter->mail}";
		
		$this->setFilterResult();
	}
	
	public function searchClicksFilter()
	{
		$this->createSQLFilter();
		
		$this->sql = "SELECT ml.idMailLink AS id, ml.link AS name
						  FROM Maillink AS ml
						  JOIN Mxcxl AS mc ON (mc.idMailLink = ml.idMaillink)
						  JOIN Contact AS c ON (c.idContact = mc.idContact) {$this->SQLfilter->click}";
				
		$this->setFilterResult();
	}
	
	private function setFilterResult()
	{
		$this->logger->log("SQL: " . print_r($this->sql, true));
		
		$modelsManager = Phalcon\DI::getDefault()->get('modelsManager');
		$result = $modelsManager->executeQuery($this->sql);
		
		if (count($result) > 0) {
			foreach ($result as $r) {
				$object = new stdClass();
				$object->id = $r->id;
				$object->text = $r->name;
				$object->preview = $r->preview;
				
				$this->filter[] = $object;
			}
		}
	}
	
	private function setTotalContacts()
	{
		$this->logger->log("SQL: " . print_r($this->sql, true));
		
		$total = 0;
		
		if (isset($this->data[1])) {
			$modelsManager = Phalcon\DI::getDefault()->get('modelsManager');
			$result = $modelsManager->executeQuery($this->sql);
			$total = $result->getFirst()->totalContacts;
		}
		
		$this->totalContacts = array('totalContacts' => $total);
	}
	
	public function searchTotalContacts()
	{
		$this->createSQLFilter();
		
		$this->sql = "SELECT COUNT(DISTINCT c.idContact) AS totalContacts 
						 FROM Contact AS c 
						 JOIN Email AS e ON (e.idEmail = c.idEmail) {$this->SQLfilter->totalContacts} 
							 AND c.unsubscribed = 0 AND e.bounced = 0 AND e.spam = 0 AND e.blocked = 0";
		
		$this->setTotalContacts();
	}
	
	public function getTotalContacts()
	{
		$this->logger->log("Total: " . print_r($this->totalContacts, true));		
		return $this->totalContacts;
	}
	
	public function getFilter()
	{
//		$this->logger->log("Filter: " . print_r($this->filter, true));
		return $this->filter;
	}
}
