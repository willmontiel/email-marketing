<?php
class Reportingcreator
{
	public $mail;
	public $type;
	public $tablename;
	public $dir;
	public $logger;
	
	public function __construct(Mail $mail, $type) 
	{
		$this->mail = $mail;
		$this->type = $type;
		$this->logger = Phalcon\DI::getDefault()->get('logger');
	}	

	public function createReport()
	{
		$internalNumber = uniqid();
		$name = $this->mail->idAccount . "_" . $this->mail->idMail . $internalNumber . "_" . $this->type . ".csv";
		$db = Phalcon\DI::getDefault()->get('db');
		
		$this->tablename = "tmp". $this->mail->idMail;
		
		$newtable = "CREATE TEMPORARY TABLE $this->tablename LIKE tmpreport";
		
		$db->execute($newtable);
		
		$filePath = Phalcon\DI::getDefault()->get('appPath')->path . '/tmp/mreports/';
		$filesPath = str_replace("\\", "/", $filePath);
		
		try {
			$title = $this->getAndSaveReportData($name, $filesPath);
		}
		catch (Exception $e) {
			$db->execute("DROP TEMPORARY TABLE $this->tablename");
			throw new \Exception("{$e}");
			Phalcon\DI::getDefault()->get('logger')->log('Exception: ' . $e->getMessage());
		}
		
		$report = new Mailreportfile();
		
		$report->idMail = $this->mail->idMail;
		$report->type = $this->type;
		$report->name = $name;
		$report->createdon = time();
		
		if (!$report->save()) {
			throw new \Exception('Error while creating report');
		}
		else {
			$report->title = $title;
			return $report;
		}
	}
	
	protected function getAndSaveReportData($name, $dir)
	{
		switch ($this->type) {
			case 'opens':
				$data = $this->getQueryForOpenReport($name, $dir);
				$title = 'REPORTE DE APERTURAS DE CORREO';
				break;
			
			case 'clicks':
				$data = $this->getQueryForClicksReport($name, $dir);
				$title = 'REPORTE DE CLICS SOBRE ENLACE';
				break;
			
			case 'unsubscribed':
				$data = $this->getQueryForUnsubscribedReport($name, $dir);
				$title = 'REPORTE CORREOS DES-SUSCRITOS';
				break;
			
			case 'bounced':
				$data = $this->getQueryForBouncedReport($name, $dir);
				$title = 'REPORTE CORREOS REBOTADOS';
				break;
			
			case 'spam':
				$data = $this->getQueryForSpamReport($name, $dir);
				$title = 'REPORTE CORREOS QUE HAN MARCADO SPAM';
				break;
			
			default :
				throw new \Exception('There is not the type of report');
				break;
		}
		
		if ($this->saveReport($data['generate'], $data['save'])) {
			return $title;
		}
		return 'No hay valores para mostrar';
	}
	
	protected function getQueryForOpenReport($name, $dir)
	{
		$phql = "SELECT null, ". $this->mail->idMail .", 'opens', e.email, null, null, null, null, null, null, v.opening
					FROM mxc AS v
						JOIN contact AS c ON (c.idContact = v.idContact)
						JOIN email AS e ON (e.idEmail = c.idEmail)
					WHERE v.idMail = ". $this->mail->idMail ."
						AND v.opening != 0";
		
		$sql = "INSERT INTO $this->tablename ($phql)";
		
		$report =  "SELECT FROM_UNIXTIME(date, '%d-%m-%Y %H:%i:%s'), email, os 
						FROM {$this->tablename}
						INTO OUTFILE  '{$dir}{$name}'
						FIELDS TERMINATED BY ','
						ENCLOSED BY '\"'
						LINES TERMINATED BY '\n'";
						
		$data = array(
			'generate' => $sql,
			'save' => $report
		);
		
		return $data;
	}
	
	protected function getQueryForClicksReport($name, $dir)
	{
		
		$sql = null;
		$report = null;
		
		$sqlc = "SELECT c.idContact, e.email, c.name, c.lastName, c.birthDate, cf.name AS field, IF(fi.textValue = null, fi.numberValue, textValue) AS value, l.link, ml.click
				 FROM mxcxl AS ml
					 JOIN contact AS c ON (c.idContact = ml.idContact)
					 JOIN email AS e ON (e.idEmail = c.idEmail)
					 LEFT JOIN fieldinstance AS fi ON (fi.idContact = c.idContact)
					 LEFT JOIN customfield AS cf ON (cf.idCustomfield = fi.idCustomfield)
					 JOIN maillink AS l ON (l.idMailLink = ml.idMailLink)
				 WHERE ml.idMail = {$this->mail->idMail}";
				
		$this->logger->log($sqlc);		 
				 
		$db = Phalcon\DI::getDefault()->get('db');
		$result = $db->query($sqlc);
		$contacts = $result->fetchAll();	 
		
		if (count($contacts) > 0) {
			$model = $this->modelContacts($contacts);
		
			$this->logger->log(print_r($model, true));
			$fields = "";
			$comma = "";
			if (count($model->fields) > 0) {
				$values = implode(' VARCHAR(200), ', $model->fields);
				$fields = implode(', ', $model->fields);
				$comma = ",";
				$this->logger->log($values);
				$addFields = "ALTER TABLE {$this->tablename} ADD ({$values})";
				$this->logger->log($addFields);

				$db = Phalcon\DI::getDefault()->get('db');
				$add = $db->execute($addFields);

				if (!$add) {
					throw new \Exception('Error while adding customfields in tmp db');
				}
			}
			
			$first = true;
			$values = "";
			foreach ($model->model as $contact) {
				if (count($model->fields) > 0) {
					$f = true;
					$fi = "";
					foreach ($model->fields as $field) {
						if ($f) {
							$fi .= "'{$contact[$field]}'";
						}
						else {
							$fi .= ", '{$contact[$field]}'";
						}
						
					}
				}
				
				if ($first) {
					$values .= "(null, {$this->mail->idMail}, 'clicks', '{$contact['email']}', '{$contact['name']}', '{$contact['lastName']}', '{$contact['birthDate']}', null, '{$contact['link']}', null, null, {$contact['click']} {$fi})";
				}
				else {
					$values .= ", (null, {$this->mail->idMail}, 'clicks', '{$contact['email']}', '{$contact['name']}', '{$contact['lastName']}', '{$contact['birthDate']}', null, '{$contact['link']}', null, null, {$contact['click']} {$fi})";
				}
				
				$first = false;
			}
			
			$this->logger->log($values);
			
			$sql = "INSERT INTO $this->tablename (idTmpReport, idMail, reportType, email, name, lastName, birthdate,
					os, link, bouncedType, category, date {$comma}{$fields})
					VALUES {$values}";
					
			$this->logger->log($sql);
			
			$report =  "SELECT FROM_UNIXTIME(date, '%d-%m-%Y %H:%i:%s'), link, email, name, lastName, birthDate, {$fields} 
							FROM {$this->tablename}
							INTO OUTFILE  '{$dir}{$name}'
							FIELDS TERMINATED BY ','
							ENCLOSED BY '\"'
							LINES TERMINATED BY '\n'";
							
			$this->logger->log($report);				
		}

		$data = array(
			'generate' => $sql,
			'save' => $report
		);
		
		return $data;
	}
	
	protected function modelContacts($contacts)
	{
		$modelc = array();
		$fields = array();
		
		foreach ($contacts as $contact) {
			if (!isset($modelc[$contact['idContact']])) {
				$array = array(
					'idContact' => $contact['idContact'],
					'email' => $contact['email'],
					'name' => $contact['name'],
					'lastName' => $contact['lastName'],
					'click' => $contact['click'],
					'link' => $contact['link'],
				);
				
				if (!empty($contact['field'])) {
					$field = $this->cleanString($contact['field']);
					$array[$field] = (isset($contact['value']) AND !empty($contact['value']) ? $contact['value'] : '');
					if (!in_array($field, $fields)) {
						$fields[] = $field;
					}
				}
				
				$modelc[$contact['idContact']] = $array;
			}
			else if (isset($modelc[$contact['idContact']]) && !empty($contact['field'])) {
				$field = $this->cleanString($contact['field']);
				$modelc[$contact['idContact']][$field] = (isset($contact['value']) AND !empty($contact['value']) ? $contact['value'] : '');
				if (!in_array($field, $fields)) {
					$fields[] = $field;
				}
			}
		}
		
		$object = new stdClass();
		$object->model = $modelc;
		$object->fields = $fields;
		
		return $object;
	}

	
	protected function cleanString($string) 
	{
		$string = str_replace(' ', '_', $string); // Replaces all spaces with hyphens.
		return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
	}


	protected function getQueryForUnsubscribedReport($name, $dir)
	{
		$phql = "SELECT null, " . $this->mail->idMail. ", 'unsubscribed', e.email, c.name, c.lastName, null, null, null, null, v.unsubscribe
					FROM mxc AS v
						JOIN contact AS c ON (c.idContact = v.idContact)
						JOIN email AS e ON (c.idEmail = e.idEmail)
				WHERE v.idMail = " . $this->mail->idMail . " AND v.unsubscribe != 0";
		
		$sql = "INSERT INTO $this->tablename ($phql)";
		
		$report =  "SELECT FROM_UNIXTIME(date, '%d-%m-%Y %H:%i:%s'), email, name, lastName 
						FROM {$this->tablename}
						INTO OUTFILE  '{$dir}{$name}'
						FIELDS TERMINATED BY ','
						ENCLOSED BY '\"'
						LINES TERMINATED BY '\n'";
		
		$data = array(
			'generate' => $sql,
			'save' => $report
		);
		
		return $data;
	}
	
	protected function getQueryForBouncedReport($name, $dir)
	{
		$phql = "SELECT null, " . $this->mail->idMail . ", 'bounced', e.email, null, null, null, null, b.type, b.description, v.bounced
					FROM mxc AS v
						JOIN contact AS c ON (c.idContact = v.idContact)
						JOIN email AS e ON (e.idEmail = c.idEmail)
						JOIN bouncedcode AS b ON (b.idBouncedCode = v.idBouncedCode)
				WHERE v.idMail = " . $this->mail->idMail . " AND v.bounced != 0";
		
		$sql = "INSERT INTO $this->tablename ($phql)";
		
		$report =  "SELECT FROM_UNIXTIME(date, '%d-%m-%Y %H:%i:%s'), email, bouncedType, category
						FROM {$this->tablename}
						INTO OUTFILE  '{$dir}{$name}'
						FIELDS TERMINATED BY ','
						ENCLOSED BY '\"'
						LINES TERMINATED BY '\n'";
		
		$data = array(
			'generate' => $sql,
			'save' => $report
		);
		
		return $data;
		
	}
	
	protected function getQueryForSpamReport($name, $dir)
	{
		$phql = "SELECT null, " . $this->mail->idMail . ", 'spam', e.email, null, null, null, null, b.type, b.description, v.bounced
					FROM mxc AS v
						JOIN contact AS c ON (c.idContact = v.idContact)
						JOIN email AS e ON (e.idEmail = c.idEmail)
						JOIN bouncedcode AS b ON (b.idBouncedCode = v.idBouncedCode)
				WHERE v.idMail = " . $this->mail->idMail . " AND v.spam != 0";
		
		$sql = "INSERT INTO $this->tablename ($phql)";
		
		$report =  "SELECT FROM_UNIXTIME(date, '%d-%m-%Y %H:%i:%s'), email, bouncedType, category
						FROM {$this->tablename}
						INTO OUTFILE  '{$dir}{$name}'
						FIELDS TERMINATED BY ','
						ENCLOSED BY '\"'
						LINES TERMINATED BY '\n'";
		
		Phalcon\DI::getDefault()->get('logger')->log("SQL: {$sql}");
		Phalcon\DI::getDefault()->get('logger')->log("Report: {$report}");
						
		$data = array(
			'generate' => $sql,
			'save' => $report
		);
		
		return $data;
	}
	
	protected function saveReport($generate,$save) 
	{
		if ($generate != null AND $save != null) {
			$db = Phalcon\DI::getDefault()->get('db');
			$s = $db->execute($generate);
			$g = $db->execute($save);

			if (!$s || !$g) {
				throw new \Exception('Error while generting info in tmp db');
			}

			$db->execute("DROP TEMPORARY TABLE $this->tablename");
			return true;
		}
		
		return false;
	}
}