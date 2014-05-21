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
		$phql = "SELECT null, " . $this->mail->idMail . ", 'clicks', e.email, null, null, null, l.link, null, null, ml.click
				 FROM mxcxl AS ml
					JOIN contact AS c ON (c.idContact = ml.idContact)
					JOIN email AS e ON (e.idEmail = c.idEmail)
					JOIN maillink AS l ON (l.idMailLink = ml.idMailLink)
				 WHERE ml.idMail = " . $this->mail->idMail;
		
		$sql = "INSERT INTO $this->tablename ($phql)";
		
		$report =  "SELECT FROM_UNIXTIME(date, '%d-%m-%Y %H:%i:%s'), email, link 
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
		$phql = "SELECT null, " . $this->mail->idMail . ", 'spam', e.email, null, null, null, null, 'hard', b.description, v.bounced
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
		$db = Phalcon\DI::getDefault()->get('db');
		$s = $db->execute($generate);
		$g = $db->execute($save);
		
		if (!$s || !$g) {
			throw new \Exception('Error while generting info in tmp db');
		}
		$db->execute("DROP TEMPORARY TABLE $this->tablename");
		return true;
	}
}