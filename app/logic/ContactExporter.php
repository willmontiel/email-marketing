<?php

class ContactExporter extends BaseWrapper
{
	private $data;
	private $appPath;
	private $logger;
	private $tmpPath;
	private $from;
	private $join;
	private $where;
	private $conditions;
	
	public function __construct() 
	{
		$this->appPath = Phalcon\DI::getDefault()->get('appPath');
		$this->logger = Phalcon\DI::getDefault()->get('logger');
	}

	public function setData($data)
	{
		if (!is_object($data) || empty($data)) {
			throw new InvalidArgumentException("export data is not valid...");
		}
		$this->data = $data;
	}
	
	public function startExporting()
	{
		$this->db = Phalcon\DI::getDefault()->get('db');
		
		$this->tablename = "tmp{$this->data->id}{$this->data->criteria}";
		$newtable = "CREATE TEMPORARY TABLE $this->tablename LIKE tmpexport";
		
		$this->db->execute($newtable);
		
		$filePath = $this->appPath->path . '/tmp/efiles/';
		if (!file_exists($filePath)) {
			mkdir($filePath, 0777, true);
		}
		
		$this->tmpPath = str_replace("\\", "/", $filePath);
		
		try {
			$this->setDataInTmpTable();
			$this->saveFileInServer();
		}
		catch (Exception $e) {
			$this->db->execute("DROP TEMPORARY TABLE $this->tablename");
			
			$this->logger->log("Exception: {$e}");
			throw new \Exception("{$e}");
		}
	}
	
	private function createSelectQuery()
	{
		switch ($this->data->criteria) {
			case 'contactlist':
				$this->from = " contactlist AS cl ";
				$this->join = " JOIN coxcl AS co ON (co.idContactlist = cl.idContactlist) 
								JOIN contact AS c ON (c.idContact = co.idContact)";
				$this->where = " cl.idContactlist = {$this->data->id} ";
				break;
			
			case 'dbase':
				$this->from = " contact AS c ";
				$this->join = "";
				$this->where = " c.idDbase = {$this->data->id} ";
				break;
			
			case 'segment':
				$this->from = " segment AS s ";
				$this->join = " JOIN sxc AS sc ON (sc.idSegment = s.idSegment) 
							    JOIN contact AS c ON (c.idContact = sc.idContact)";
				$this->where = "s.idSegment = {$this->data->id} ";
				break;
		}
		
		switch ($this->data->contacts) {
			case 'active':
				$this->conditions = " AND c.unsubscribed = 0 AND e.bounced = 0 AND e.spam = 0 ";
				break;
			
			case 'unsuscribed':
				$this->conditions = " AND c.unsubscribed = 0 AND  ";
				break;
			
			case 'bounced':
				$this->conditions = " AND e.bounced = 0 ";
				break;
			
			case 'spam':
				$this->conditions = " AND e.spam = 0 ";
				break;
			
			default:
				$this->conditions = "";
				break;
		}
	}


	
	private function setDataInTmpTable()
	{
		$this->createSelectQuery();
		
		$select = "SELECT null, '{$this->data->contacts}', e.email, c.name, c.lastName, c.birthDate
				   FROM {$this->from}
					    {$this->join}
						JOIN email AS e ON (e.idEmail = c.idEmail)
				   WHERE {$this->where} {$this->conditions}";
		
		$this->logger->log($select);		   
				   
		$insert = "INSERT INTO {$this->tablename} (idExport, status, email, name, lastName, birthDate, createdon)
					      VALUES ({$select})";
		
		$this->logger->log($insert);		   
						  
		$db = Phalcon\DI::getDefault()->get('db');
		$result = $db->execute($insert);
		
		if (!$result) {
			throw new \Exception('Error while saving data in db');
		}
	}
	
	private function saveFileInServer()
	{
		$exportfile =  "SELECT FROM_UNIXTIME(date, '%d-%m-%Y %H:%i:%s'), email, link 
						FROM {$this->tablename}
						INTO OUTFILE  '{$this->tmpPath}{$this->data->model->name}.csv'
						FIELDS TERMINATED BY ','
						ENCLOSED BY '\"'
						LINES TERMINATED BY '\n'";
						
		$db = Phalcon\DI::getDefault()->get('db');
		$result = $db->execute($exportfile);
		
		if (!$result) {
			throw new \Exception('Error while saving file in server');
		}
		
		$db->execute("DROP TEMPORARY TABLE $this->tablename");
		return true;
	}
}