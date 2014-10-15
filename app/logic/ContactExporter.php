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
	private $cfSQL = null;
	private $customfields = null;
	private $cf = null;
	private $cfJoin = null;

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
		
		if ($this->data->fields == 'custom-fields') {
			$this->createCustomFieldsSQL();
		}
		
		if ($this->cfSQL != null) {
			$this->db->execute($this->cfSQL);
		}
		
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
	
	private function createCustomFieldsSQL()
	{
		$cfields = Customfield::find(array(
			'conditions' => 'idDbase = ?1' ,
			'bind' => array(1 => $this->data->model->idDbase)
		));
		
		$cfnames = array();
		$cfs = array();
		
		if (count($cfields) > 0) {
			foreach ($cfields as $cfield) {
				$type = ($cfield->type == 'Numerical' ? 'INT(100)' : 'VARCHAR(100)');
				$name = $this->cleanSpaces($cfield->name);
				$cfs[] = $name;
				$cfnames[] = "{$name} {$type}";
			}
			
			$this->logger->log(print_r($cfnames, true));
			
			if (count($cfnames) > 0) {
				$columns = implode(', ', $cfnames);
				$this->customfields = ", ";
				$this->customfields .= implode(', ', $cfs);
				
				$this->cfSQL = "ALTER TABLE {$this->tablename} ADD ({$columns})";
			}	
//			$this->logger->log($this->cfSQL);
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
				$this->conditions = " AND c.unsubscribed = 0 AND e.bounced = 0 AND e.spam = 0 AND c.status != 0";
				break;
			
			case 'unsuscribed':
				$this->conditions = " AND c.unsubscribed != 0 AND  ";
				break;
			
			case 'bounced':
				$this->conditions = " AND e.bounced != 0 ";
				break;
			
			case 'spam':
				$this->conditions = " AND e.spam != 0 ";
				break;
			
			default:
				$this->conditions = "";
				break;
		}
		
		if ($this->data->fields == 'custom-fields' && $this->customfields != null) {
			$this->cf = " , IF(fi.textValue = null, fi.numberValue, fi.textValue) ";
			$this->cfJoin = " LEFT JOIN fieldinstance AS fi ON (fi.idContact = c.idContact)";
		}
	}


	
	private function setDataInTmpTable()
	{
		$this->createSelectQuery();
		
		$select = "SELECT null, IF(e.spam != 0, 'Spam', IF(e.bounced != 0, 'Rebotado', IF(e.blocked != 0, 'Bloqueado', IF(c.unsubscribed != 0, 'Des-suscrito', IF(c.status != 0, 'Activo', 'Inactivo'))))), e.email, c.name, c.lastName, c.birthDate, " . time() ." {$this->cf}
				   FROM {$this->from}
					    {$this->join}
						JOIN email AS e ON (e.idEmail = c.idEmail)
						{$this->cfJoin}
				   WHERE {$this->where} {$this->conditions}";
		
//		$this->logger->log($select);		   
				   
		$insert = "INSERT INTO {$this->tablename} (idExport, status, email, name, lastName, birthDate, createdon {$this->customfields})
					     ({$select})";
		
		$this->logger->log($insert);		   
						  
		$db = Phalcon\DI::getDefault()->get('db');
		$result = $db->execute($insert);
		
		if (!$result) {
			throw new \Exception('Error while saving data in db');
		}
	}
	
	private function saveFileInServer()
	{
		$exportfile =  "SELECT email, name, lastName, birthDate, status {$this->customfields}
						FROM {$this->tablename}
						INTO OUTFILE  '{$this->tmpPath}{$this->data->model->name}.csv'
						CHARACTER SET utf8
						FIELDS TERMINATED BY ','
						ENCLOSED BY '\"'
						LINES TERMINATED BY '\n'";

		$this->logger->log($exportfile);				
						
		$db = Phalcon\DI::getDefault()->get('db');
		$result = $db->execute($exportfile);
		
		if (!$result) {
			throw new \Exception('Error while saving file in server');
		}
		
		$db->execute("DROP TEMPORARY TABLE $this->tablename");
		return true;
	}
	
	
	private function cleanSpaces($cadena){
//		$cadena = str_replace($cadena, " ", "");
//		$cadena = ereg_replace( "([ ]+)", "", $cadena );
		$cadena = preg_replace("([ ]+)", "", $cadena);
		return $cadena;
	}
	
	public function deleteFile()
	{
		$path = "{$this->tmpPath}{$this->data->model->name}.csv";
		
		if (!unlink($path)) {
			$this->logger->log("File could not delete from server!");
//			throw new Exception('File could not delete from server!');
		}
	}
}