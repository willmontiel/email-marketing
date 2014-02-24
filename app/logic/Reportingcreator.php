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
				$data = $this->getQueryForOpenReport($name, $dir);
				$title = 'Reporte de aperturas';
				break;
			
			case 'clicks':
				$data = $this->getQueryForClicksReport($name, $dir);
				$title = 'Reporte de clics sobre enlaces';
				break;
			
			case 'unsubscribed':
				$data = $this->getQueryForUnsubscribedReport($name, $dir);
				$title = 'Reporte de correos des-suscritos';
				break;
			
			case 'bounced':
				$data = $this->getQueryForBouncedReport($name, $dir);
				$title = 'Reporte de correos rebotados';
				break;
			
			default :
				throw new \InvalidArgumentException('There is not the type of report');
				break;
		}
		
		if ($this->saveReport($data['generate'], $data['save'])) {
			return $title;
		}
		return 'No hay valores para mostrar';
	}
	
	protected function getQueryForOpenReport($name, $dir)
	{
		$phql = "SELECT null, ". $this->mail->idMail .", 'opens', e.email, null, null, v.userAgent, null, null, null, v.date
					FROM mailevent AS v
						JOIN contact AS c ON (c.idContact = v.idContact)
						JOIN email AS e ON (e.idEmail = c.idEmail)
					WHERE v.idMail = ". $this->mail->idMail ."
						AND (v.description = 'opening' OR v.description = 'opening for click')";
		
		$sql = "INSERT INTO $this->tablename ($phql)";
		
		$report =  "SELECT FROM_UNIXTIME(date, '%d-%M-%Y %H:%i:%s'), email, os 
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
		$phql = "SELECT null, " . $this->mail->idMail . ", 'clicks', e.email, null, null, null, l.link, null, null, ml.click
				 FROM mxcxl AS ml
					JOIN contact AS c ON (c.idContact = ml.idContact)
					JOIN email AS e ON (e.idEmail = c.idEmail)
					JOIN maillink AS l ON (l.idMailLink = ml.idMailLink)
				 WHERE ml.idMail = " . $this->mail->idMail;
		
		$sql = "INSERT INTO $this->tablename ($phql)";
		
		$report =  "SELECT FROM_UNIXTIME(date, '%d-%M-%Y %H:%i:%s'), email, link 
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
	
	protected function getQueryForUnsubscribedReport($name, $dir)
	{
		$phql = "SELECT null, " . $this->mail->idMail. ", 'unsubscribed', e.email, c.name, c.lastName, null, null, null, null, v.date
					FROM mailevent AS v
						JOIN contact AS c ON (c.idContact = v.idContact)
						JOIN email AS e ON (c.idEmail = e.idEmail)
				WHERE v.idMail = " . $this->mail->idMail . " AND v.description = 'unsubscribed'";
		
		$sql = "INSERT INTO $this->tablename ($phql)";
		
		$report =  "SELECT FROM_UNIXTIME(date, '%d-%M-%Y %H:%i:%s'), email, name, lastName 
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
		$sql = "SELECT null, " . $this->mail->idMail . ", e.email, null, null, null, null, b.type, b.description, v.date
					FROM mailevent AS v
						JOIN contact AS c ON (c.idContact = v.idContact)
						JOIN email AS e ON (e.idEmail = c.idEmail)
						JOIN bouncedcode AS b ON (b.idBouncedCode = v.idBouncedCode)
				WHERE v.idMail = " . $this->mail->idMail . " AND v.description = 'bounced'";
		
		$report =  "SELECT FROM_UNIXTIME(date, '%d-%M-%Y %H:%i:%s'), email, bouncedType, category
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
	
	protected function saveReport($generate,$save) 
	{
		$db = Phalcon\DI::getDefault()->get('db');
		$s = $db->execute($generate);
		$g = $db->execute($save);
		
		if (!$s || !$g) {
			throw new \InvalidArgumentException('Error while generting info in tmp db');
		}
		return true;
	}
}