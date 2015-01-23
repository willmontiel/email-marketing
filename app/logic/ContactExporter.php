<?php

class ContactExporter extends BaseWrapper
{
	private $data;
	private $appPath;
	private $logger;
	private $tmpPath;
	private $model;
	private $customfields = false;
	
	private $cfSQL = null;
	private $contactsToSave = array();
	private $customfieldsData;
	private $exportfile;
	
	private $i = 0;

	const CONTACTS_PER_UPDATE = 20000;

	public function __construct() 
	{
		$this->appPath = Phalcon\DI::getDefault()->get('appPath');
		$this->logger = Phalcon\DI::getDefault()->get('logger');
		
		$this->customfieldsData = new stdClass();
		$this->customfieldsData->customfieldsNames = '';
		$this->customfieldsData->customfieldsColumns = '';
		$this->customfieldsData->arrayCustomfieldsNames = array();
	}

	public function setData($data)
	{
		if (!is_object($data) || empty($data)) {
			throw new InvalidArgumentException("export data is not valid...");
		}
		$this->data = $data;
	}
	
	private function findExportFile()
	{
		$exportfile = Exportfile::findFirstByIdExportfile($this->data->idExportfile);
		
		if (!$exportfile) {
			throw new InvalidArgumentException("exportfile do not exists...");
		}
		
		$this->exportfile = $exportfile;
	}
	
	private function findCriteria()
	{
		switch ($this->data->criteria) {
			case 'contactlist':
				$model = Contactlist::findFirst(array(
					'conditions' => 'idContactlist = ?1',
					'bind' => array(1 => $this->data->idCriteria)
				));

				$dbase = Dbase::findFirst(array(
					'conditions' => 'idDbase = ?1 AND idAccount = ?2',
					'bind' => array(1 => $model->idDbase,
									2 => $this->exportfile->idAccount)
				));

				if (!$model || !$dbase) {
					throw new InvalidArgumentException("Criteria do not exists");
				}
				break;

			case 'dbase':
				$model = Dbase::findFirst(array(
					'conditions' => 'idDbase = ?1 AND idAccount = ?2',
					'bind' => array(1 => $this->data->idCriteria,
									2 => $this->exportfile->idAccount)
				));

				if (!$model) {
					throw new InvalidArgumentException("Criteria do not exists");
				}
				break;

			case 'segment':
				$model = Segment::findFirst(array(
					'conditions' => 'idSegment = ?1',
					'bind' => array(1 => $this->data->idCriteria)
				));

				$dbase = Dbase::findFirst(array(
					'conditions' => 'idDbase = ?1 AND idAccount = ?2',
					'bind' => array(1 => $model->idDbase,
									2 => $this->exportfile->idAccount)
				));

				if (!$model || !$dbase) {
					throw new InvalidArgumentException("Criteria do not exists");
				}
				break;
		}
		
		$this->model = $model;
		$this->data->model = $model;
	}
	
	private function prepareDirectory()
	{
		$filePath = $this->appPath->path . '/tmp/efiles/';
		if (!file_exists($filePath)) {
			mkdir($filePath, 0777, true);
		}
		$this->tmpPath = str_replace("\\", "/", $filePath);
	}

	private function createTmpTable()
	{
		$this->db = Phalcon\DI::getDefault()->get('db');
		$this->tablename = "tmp{$this->data->idCriteria}{$this->data->criteria}";
		$newtable = "CREATE TEMPORARY TABLE $this->tablename LIKE tmpexport";
		$this->db->execute($newtable);
	}
	
	private function validateCustomFields()
	{
		if ($this->data->fields == 'custom-fields') {
			$this->customfields = true;
		}
	}

	private function prepareData()
	{
		try {
			$this->findExportFile();
			$this->findCriteria();
			$this->prepareDirectory();
			$this->createTmpTable();
			$this->validateCustomFields();
		}	
		catch (Exception $e) {
			$this->exportfile->status = "Cancelado";
			$this->saveExportFile();
			throw new Exception("Exception while creating tmp table... {$e}");
		}
	}
	
	public function startExporting()
	{
		$this->prepareData();
		
		try {
			$contactIterator = new TotalContactIterator();
			$contactIterator->setCustomFields($this->customfields);
			$contactIterator->setData($this->data);
			$contactIterator->initialize();
			
			$this->exportfile->contactsToProcess = $contactIterator->getTotalContacts();
			$this->saveExportFile();
			
			$this->customfieldsData = $contactIterator->getCustomFieldsData();
			
			if (!empty($this->customfieldsData)) {
				$this->addCustomFieldsToTmpTable();
			}
			
			foreach ($contactIterator as $contact) {
				$status = (empty($contact['status']) ? "null" : "'{$contact['status']}'");
				$email = (empty($contact['email']) ? "null" : "'{$contact['email']}'");
				$name = (empty($contact['name']) ? "null" : "'{$contact['name']}'");
				$lastName = (empty($contact['lastName']) ? "null" : "'{$contact['lastName']}'");
				$birthDate = (empty($contact['birthDate']) ? "null" : "'{$contact['birthDate']}'");
				
				$fields = "{$contact['idContact']}, {$status}, {$email}, {$name}, {$lastName}, {$birthDate}, {$contact['createdon']}";
				
				if (isset($this->customfieldsData->arrayCustomfieldsNames) && count($this->customfieldsData->arrayCustomfieldsNames) > 0) {
					foreach ($this->customfieldsData->arrayCustomfieldsNames as $cfname) {
						$fields .= (empty($contact[$cfname]) ? ", null" : ", '{$contact[$cfname]}'");
					}
				}
//				$this->logger->log("Entra");
				$this->contactsToSave[] = $fields;
				unset($fields);
				
				$this->i++;
				
				if (count($this->contactsToSave) == self::CONTACTS_PER_UPDATE) {
					$this->setDataInTmpTable();
					unset($this->contactsToSave);
					$this->exportfile->contactsProcessed = $this->i;
					$this->saveExportFile();
				}
			}
			
			if (count($this->contactsToSave) > 0) {
				$this->setDataInTmpTable();
                unset($this->contactsToSave);
				$this->exportfile->contactsProcessed = $this->i;
				$this->saveExportFile();
			}

			$this->saveFileInServer();
			$this->exportfile->contactsProcessed = $this->i;
			$this->exportfile->status = "Finalizado";
			$this->saveExportFile();
		}
		catch (Exception $e) {
			$this->db->execute("DROP TEMPORARY TABLE $this->tablename");
			$this->exportfile->status = 'Cancelado';
			$this->saveExportFile();
			$this->logger->log("Exception: {$e}");
			throw new \Exception("{$e}");
		}
	}
	
	private function saveExportFile()
	{
		if (!$this->exportfile->save()) {
			foreach ($this->exportfile->getMessages() as $msg) {
				$this->logger->log("Error while updating exportfile... {$msg}");
			}
		}
	}
	
	private function addCustomFieldsToTmpTable()
	{
		if (!empty($this->customfieldsData->customfieldsColumns)) {
			$this->cfSQL = "ALTER TABLE {$this->tablename} ADD ({$this->customfieldsData->customfieldsColumns})";
//			$this->logger->log("SQL: {$this->cfSQL}");
			$this->db->execute($this->cfSQL);
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
		
		$customFields = (isset($this->customfieldsData->customfieldsNames) && !empty($this->customfieldsData->customfieldsNames) ? $this->customfieldsData->customfieldsNames : "");
		$insert = "INSERT INTO {$this->tablename} (idContact, status, email, name, lastName, birthDate, createdon {$customFields})
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
		$customFields = (isset($this->customfieldsData->customfieldsNames) && !empty($this->customfieldsData->customfieldsNames) ? $this->customfieldsData->customfieldsNames : "");
		
		$exportfile =  "SELECT email, name, lastName, birthDate, status {$customFields}
						FROM {$this->tablename}
						INTO OUTFILE  '{$this->tmpPath}{$this->exportfile->name}.csv'
						CHARACTER SET utf8
						FIELDS TERMINATED BY ','
						ENCLOSED BY '\"'
						LINES TERMINATED BY '\n'";

//		$this->logger->log($exportfile);				
						
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
		$path = "{$this->tmpPath}{$this->exportfile->name}.csv";
		
		if (!unlink($path)) {
			$this->logger->log("File could not delete from server!");
//			throw new Exception('File could not delete from server!');
		}
	}
}