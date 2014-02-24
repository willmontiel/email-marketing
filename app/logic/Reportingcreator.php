<?php
class Reportingcreator
{
	public $mail;
	public $type;
	public $tablename;
	public $dir;
	
	
	public function __construct(Mail $mail, $type) 
	{
		$this->mail = $mail;
		$this->type = $type;
	}	

	public function createReport()
	{
		$internalNumber = uniqid();
		$name = $this->mail->idAccount . "_" . $this->mail->idMail . $internalNumber . "_" . $this->type . ".csv";
		$db = Phalcon\DI::getDefault()->get('db');
		
		$this->tablename = "tmp". $this->mail->idMail;
		
		$newtable = "CREATE TABLE $this->tablename LIKE tmpreport";
		$deletetable = "DROP TABLE $this->tablename";
		
		$db->execute($newtable);
		
		$filePath = Phalcon\DI::getDefault()->get('appPath')->path . '/tmp/mreports/';
		$filesPath = str_replace("\\", "/", $filePath);
		
		try {
			$title = $this->getAndSaveReportData($name, $filesPath);
			$db->execute($deletetable);
		}
		catch (Exception $e) {
			$db->execute($deletetable);
			Phalcon\DI::getDefault()->get('logger')->log('Exception: ' . $e->getMessage());
		}
		
		$report = new Mailreportfile();
		
		$report->idMail = $this->mail->idMail;
		$report->type = $this->type;
		$report->name = $name;
		$report->createdon = time();
		
		if (!$report->save()) {
			throw new \InvalidArgumentException('Error while creating report');
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
				$data = $this->getDataOpenReport();
				$title = 'Reporte de aperturas';
				break;
			
			case 'clicks':
				$data = $this->saveClicksReportOnDisc($name, $dir);
				$title = 'Reporte de clics sobre enlaces';
				break;
			
			case 'unsubscribed':
				$data = $this->getDataUnsubscribedReport();
				$title = 'Reporte de correos des-suscritos';
				break;
			
			case 'bounced':
				$data = $this->getDataBouncedReport();
				$title = 'Reporte de correos rebotados';
				break;
			
			default :
				throw new \InvalidArgumentException('There is not the type of report');
				break;
		}
		
		if ($data) {
			return $title;
		}
		return 'No hay valores para mostrar';
	}
	
	protected function getDataOpenReport()
	{
		$db = Phalcon\DI::getDefault()->get('db');
		
		$sql = "SELECT e.email, v.userAgent, v.date
					FROM mailevent AS v
						JOIN contact AS c ON ( c.idContact = v.idContact )
						JOIN email AS e ON ( e.idEmail = c.idEmail )
					WHERE v.idMail = ?
						AND (v.description = 'opening' OR v.description = 'opening for click')";
		
		$result = $db->query($sql, array($this->mail->idMail));
		$info = $result->fetchAll();
		
		$data = array();
		if (count($info) > 0) {
			foreach ($info as $i) {
				$data[] = array(
					'type' => 'opens',
					'email' => $i['email'],
					'userAgent' => $i['userAgent'],
					'date' => $i['date']
				);
			}
			
			$v = " ";
			foreach ($data as $o) {
				$v .= "(" . $this->mail->idMail . ", '" . $o['type'] . "', '" . $o['email'] . "', '" . $o['userAgent'] . "', " .$o['date'] .")";
			}
			
			$values = str_replace(")(", "),(", $v);
			$report = '(idMail, reportType, email, os, date) VALUES ' . $values;
			
			return $report;
		}
		return false;
	}
	
	protected function saveClicksReportOnDisc($name, $dir)
	{
		$db = Phalcon\DI::getDefault()->get('db');
		
		$phql = "SELECT null, " . $this->mail->idMail . ", 'clicks', e.email, null, null, null, l.link, null, null, ml.click
				 FROM mxcxl AS ml
					JOIN contact AS c ON (c.idContact = ml.idContact)
					JOIN email AS e ON (e.idEmail = c.idEmail)
					JOIN maillink AS l ON (l.idMailLink = ml.idMailLink)
				 WHERE ml.idMail = " . $this->mail->idMail;
		
		$sql = "INSERT INTO $this->tablename ($phql)";
		
		Phalcon\DI::getDefault()->get('logger')->log('SQL: ' . $sql);
		$db->execute($sql);
		
		$report =  "SELECT FROM_UNIXTIME(date, '%d-%M-%Y %H:%i:%s'), email, link 
						FROM {$this->tablename}
						INTO OUTFILE  '{$dir}{$name}'
						FIELDS TERMINATED BY ','
						ENCLOSED BY '\"'
						LINES TERMINATED BY '\n'";
			
		
		$ok = $db->execute($report);
		
		if (!$ok) {
			throw new \InvalidArgumentException('Error while generting info in tmp db');
		}
		return true;
	}
	
	protected function getDataUnsubscribedReport()
	{
		$db = Phalcon\DI::getDefault()->get('db');
		
		$sql = "SELECT e.email, v.date, c.name, c.lastName
					FROM mailevent AS v
						JOIN contact AS c ON (c.idContact = v.idContact)
						JOIN email AS e ON (c.idEmail = e.idEmail)
				WHERE v.idMail = ? AND v.description = 'unsubscribed'";
		
		$result = $db->query($sql, array($this->mail->idMail));
		$info = $result->fetchAll();
		
		if (count($info) > 0) {
			$data = array();
			
			foreach ($info as $i) {
				$data[] = array(
					'email' => $i['email'],
					'date' => $i['date'],
					'name' => $i['name'],
					'lastname' => $i['lastName']
				);
			}
			
			$v = " ";
		
			foreach ($data as $u) {
				$v .= "(" . $this->mail->idMail . ", " . "'unsubscribed'" . ", '" . $u['email'] . "', '" . $u['name'] . "', '" . $u['lastname'] . "', " . $u['date'] .")";
			}

			$values = str_replace(")(", "),(", $v);
			$report = ' (idMail, reportType, email, name, lastName, date) VALUES ' . $values;

			return $report;
		}
		return false;
	}
	
	protected function getDataBouncedReport()
	{
		$db = Phalcon\DI::getDefault()->get('db');
		
		$sql = "SELECT e.email, v.date, b.type, b.description
					FROM mailevent AS v
						JOIN contact AS c ON (c.idContact = v.idContact)
						JOIN email AS e ON (e.idEmail = c.idEmail)
						JOIN bouncedcode AS b ON (b.idBouncedCode = v.idBouncedCode)
				WHERE v.idMail = ? AND v.description = 'bounced'";
		
		$result = $db->query($sql, array($this->mail->idMail));
		$info = $result->fetchAll();
		
		if (count($info) > 0) {
			$bouncedcontact = array();
			
			foreach ($info as $i) {
				$bouncedcontact[] = array(
					'email' => $i['email'],
					'date' => $i['date'],
					'type' => $i['type'],
					'category' => $i['description']
				);
			}
			
			$v = " ";

			foreach ($bouncedcontact as $b) {
				$v .= "(" . $this->mail->idMail . ", " . "'bounced'" . ", '" . $b['email'] . "', '" . $b['type'] . "', '" . $b['category'] . "', " . $b['date'] .")";
			}

			$values = str_replace(")(", "),(", $v);
			$report = ' (idMail, reportType, email, bouncedType, category, date) VALUES ' . $values;

			return $report;
		}
		return false;
	}
	
	protected function saveReport($data, $name, $dir) 
	{
		$db = Phalcon\DI::getDefault()->get('db');
		
		Phalcon\DI::getDefault()->get('logger')->log("Dir: " . $dir . $name);
		
		$db->execute("INSERT INTO $this->tablename $data");
		
		$report =  "SELECT email, name, lastName, os, FROM_UNIXTIME(date, '%d-%M-%Y %H:%i:%s')
						FROM {$this->tablename}
						INTO OUTFILE  '{$dir}{$name}'
						FIELDS TERMINATED BY ','
						ENCLOSED BY '\"'
						LINES TERMINATED BY '\n'";
			
		
		$ok = $db->execute($report);
		
		if (!$ok) {
			throw new \InvalidArgumentException('Error while generting info in tmp db');
		}
	}
}