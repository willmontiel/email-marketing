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
					$this->SQLfilter->mail = " JOIN Contact AS c ON (c.idContact = mc.idContact) WHERE c.idDbase IN ({$ids}) AND m.status = 'Sent' GROUP BY 1,2,3,4";
					break;

				case 'contactlists':
					$this->SQLfilter->mail = " JOIN Coxcl AS lc ON (lc.idContact = mc.idContact) WHERE lc.idContactlist IN ({$ids}) AND m.status = 'Sent' GROUP BY 1,2,3,4";
					break;

				case 'segments':
					$this->SQLfilter->mail = " JOIN Sxc AS sc ON (sc.idContact = mc.idContact) WHERE sc.idSegment IN ({$ids}) AND m.status = 'Sent' GROUP BY 1,2,3,4";
					break;
			}	
		}
	}
	
	public function searchMailFilter()
	{
		$this->createSQLFilter();
		
		$this->sql = "SELECT m.idMail AS id, m.name AS name, m.subject AS subject, m.startedon AS date
					  FROM Mail AS m
						  JOIN Mxc AS mc ON (mc.idMail = m.idMail) {$this->SQLfilter->mail}";
		
		$this->setFilterResult();
	}
	
	public function searchMailsWithClicksFilter() 
	{
		$this->createSQLFilter();
		
		$this->sql = "SELECT m.idMail AS id, m.name AS name, m.subject AS subject, m.startedon AS date
						 FROM Mail AS m
						 JOIN Mxl AS l ON (l.idMail = m.idMail)
						 JOIN Mxc AS mc ON (mc.idMail = m.idMail) {$this->SQLfilter->mail}";
						 
		$this->setFilterResult();
	}
	
	public function searchClicksFilter()
	{
		$this->sql = "SELECT l.idMailLink AS id, l.link AS name
						  FROM Mxl AS ml
						  JOIN Maillink AS l ON (l.idMailLink = ml.idMailLink)
					  WHERE ml.idMail = {$this->data['idMail']}";
				
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
				$object->subject = $r->subject;
				$object->date = date('d/m/Y', $r->date);
				
				$this->filter[] = $object;
			}
		}
	}
	
	public function getFilter()
	{
		return $this->filter;
	}
}
