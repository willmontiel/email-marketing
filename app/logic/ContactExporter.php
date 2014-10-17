<?php

class ContactExporter extends BaseWrapper
{
	private $data;
	private $appPath;
	private $logger;
	private $tmpPath;
	private $cfSQL = null;
	private $cf = false;
	private $contactsToSave = array();
	private $cfData;

	const CONTACTS_PER_UPDATE = 25000;

	public function __construct() 
	{
		$this->appPath = Phalcon\DI::getDefault()->get('appPath');
		$this->logger = Phalcon\DI::getDefault()->get('logger');
		
		$this->cfData = new stdClass();
		$this->cfData->customfieldsNames = '';
		$this->cfData->customfieldsColumns = '';
		$this->cfData->arrayCustomfieldsNames = array();
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
		try {
			$this->prepareDirectory();
			$this->createTmpTable();
			$this->validateCustomFields();
		}	
		catch (Exception $e) {
			throw new Exception("Exceptio while creating tmp table... {$e}");
		}
		
		try {
			$contactIterator = new TotalContactIterator();
			$contactIterator->setCustomFields($this->cf);
			$contactIterator->setData($this->data);
			$contactIterator->initialize();
			
			if ($this->cf == true) {
				$this->cfData = $contactIterator->getCustomFieldsData();
				if ($this->cfData != null) {
					$this->addCustomFieldsToTmpTable();
				}
			}
			
			foreach ($contactIterator as $contact) {
				$status = (empty($contact['status']) ? "null" : "'{$contact['status']}'");
				$email = (empty($contact['email']) ? "null" : "'{$contact['email']}'");
				$name = (empty($contact['name']) ? "null" : "'{$contact['name']}'");
				$lastName = (empty($contact['lastName']) ? "null" : "'{$contact['lastName']}'");
				$birthDate = (empty($contact['birthDate']) ? "null" : "'{$contact['birthDate']}'");
				
				$fields = "{$contact['idContact']}, {$status}, {$email}, {$name}, {$lastName}, {$birthDate}, {$contact['createdon']}";
				
				if (isset($this->cfData->arrayCustomfieldsNames) && count($this->cfData->arrayCustomfieldsNames) > 0) {
					foreach ($this->cfData->arrayCustomfieldsNames as $cfname) {
						$fields .= (empty($contact[$cfname]) ? ", null" : ", '{$contact[$cfname]}'");
					}
				}
//				$this->logger->log("Entra");
				$this->contactsToSave[] = $fields;
				unset($fields);
				
				if (count($this->contactsToSave) == self::CONTACTS_PER_UPDATE) {
					$this->setDataInTmpTable();
					unset($this->contactsToSave);
				}
			}
			
			if (count($this->contactsToSave) > 0) {
				$this->setDataInTmpTable();
                unset($this->contactsToSave);
			}

			$this->saveFileInServer();
		}
		catch (Exception $e) {
			$this->db->execute("DROP TEMPORARY TABLE $this->tablename");
			$this->logger->log("Exception: {$e}");
			throw new \Exception("{$e}");
		}
	}
	
	protected function prepareDirectory()
	{
		$filePath = $this->appPath->path . '/tmp/efiles/';
		if (!file_exists($filePath)) {
			mkdir($filePath, 0777, true);
		}
		$this->tmpPath = str_replace("\\", "/", $filePath);
	}

	protected function createTmpTable()
	{
		$this->db = Phalcon\DI::getDefault()->get('db');
		$this->tablename = "tmp{$this->data->id}{$this->data->criteria}";
		$newtable = "CREATE TEMPORARY TABLE $this->tablename LIKE tmpexport";
		$this->db->execute($newtable);
	}
	
	protected function addCustomFieldsToTmpTable()
	{
		if (!empty($this->cfData->customfieldsColumns)) {
			$this->cfSQL = "ALTER TABLE {$this->tablename} ADD ({$this->cfData->customfieldsColumns})";
			$this->db->execute($this->cfSQL);
		}
	}

	protected function validateCustomFields()
	{
		if ($this->data->fields == 'custom-fields') {
			$this->cf = true;
		}
	}

	protected function setDataInTmpTable()
	{
		$values = '';
		$init = true;
		foreach ($this->contactsToSave as $contact) {
			if ($init) {
				$values .= "({$contact})";
			}
			else {
				$values .= ",({$contact})";
			}
			$init = false;
		}
		
		$insert = "INSERT INTO {$this->tablename} (idContact, status, email, name, lastName, birthDate, createdon {$this->cfData->customfieldsNames})
				   VALUES {$values}";
		
//		$this->logger->log("insert: {$insert}");		   
						  
		$db = Phalcon\DI::getDefault()->get('db');
		$result = $db->execute($insert);
		
		if (!$result) {
			throw new \Exception('Error while saving data in db');
		}
	}
	
	protected function saveFileInServer()
	{
		$exportfile =  "SELECT email, name, lastName, birthDate, status {$this->cfData->customfieldsNames}
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
	
	
	protected function cleanSpaces($cadena){
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