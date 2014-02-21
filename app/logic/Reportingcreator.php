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
				$data = $this->getDataClicksReport();
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
		
		if ($data !== false) {
			$this->saveReport($data, $name, $dir);
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
	
	protected function getDataClicksReport()
	{
		$db = Phalcon\DI::getDefault()->get('db');
		
		$data[] = array(
			'email' => 'otrocorreo@otro.correo',
			'date' => 1386878942,
			'link' => 'https://www.google.com'
		);

		$data[] = array(
			'email' => 'otrocorreo2@otro2.correo2',
			'date' => 1386747891,
			'link' => 'https://www.facebook.com'
		);

		$data[] = array(
			'email' => 'otrocorreo3@otro3.correo3',
			'date' => 1386698537,
			'link' => 'https://www.google.com'
		);
		
		$v = " ";
		
		foreach ($data as $c) {
			$v .= "(" . $this->mail->idMail . ", " . "'clicks'" . ", '" . $c['email'] . "', '" . $c['link'] . "', " .$c['date'] .")";
		}
		
		$values = str_replace(")(", "),(", $v);
		$report = ' (idMail, reportType, email, link, date) VALUES ' . $values;
		
		return $report;
	}
	
	protected function getDataUnsubscribedReport()
	{
		$db = Phalcon\DI::getDefault()->get('db');
		
		$data[] = array(
			'type' => 'unsubcribed',
			'email' => 'newmail@new.mail',
			'date' => 1386687891,
			'name' => 'fulano',
			'lastname' => ''
		);
		
		$data[] = array(
			'type' => 'unsubcribed',
			'email' => 'newmail1@new1.mail1',
			'date' => 1386687891,
			'name' => '',
			'lastname' => 'perez2'
		);
		
		$data[] = array(
			'type' => 'unsubcribed',
			'email' => 'newmail2@new2.mail2',
			'date' => 1386687891,
			'name' => 'fulano3',
			'lastname' => 'perez3'
		);
		
		$data[] = array(
			'type' => 'unsubcribed',
			'email' => 'otrocorreo3@otro3.correo3',
			'date' => 1386687891,
			'name' => '',
			'lastname' => ''
		);
		
		$v = " ";
		
		foreach ($data as $u) {
			$v .= "(" . $this->mail->idMail . ", " . "'unsubscribed'" . ", '" . $u['email'] . "', '" . $u['name'] . "', '" . $u['lastname'] . "', " . $u['date'] .")";
		}
		
		$values = str_replace(")(", "),(", $v);
		$report = ' (idMail, reportType, email, name, lastName, date) VALUES ' . $values;
		
		return $report;
	}
	
	protected function saveBouncedReport($name, $dir)
	{
		$db = Phalcon\DI::getDefault()->get('db');
		
		$bouncedcontact[] = array(
			'id' => 20,
			'email' => 'newmail@new.mail',
			'date' => 1386687891,
			'type' => 'Temporal',
			'category' => 'Buzon Lleno'
		);
		
		$bouncedcontact[] = array(
			'id' => 240,
			'email' => 'newmail1@new1.mail1',
			'date' => 1386687891,
			'type' => 'Otro',
			'category' => 'Rebote General'
		);
		
		$bouncedcontact[] = array(
			'id' => 57,
			'email' => 'newmail2@new2.mail2',
			'date' => 1386687891,
			'type' => 'Permanente',
			'category' => 'Direccion Mala'
		);
		
		$bouncedcontact[] = array(
			'id' => 59,
			'email' => 'newmail54@new3.mail3',
			'date' => 1386687891,
			'type' => 'Temporal',
			'category' => 'Buzon Lleno'
		);
		
		$v = " ";
		
		foreach ($bouncedcontact as $b) {
			$v .= "(" . $this->mail->idMail . ", " . "'bounced'" . ", '" . $b['email'] . "', '" . $b['type'] . "', '" . $b['category'] . "', " . $b['date'] .")";
		}
		
		$values = str_replace(")(", "),(", $v);
		
		$report = ' (idMail, reportType, email, bouncedType, category, date) VALUES ' . $values;
		
		return $report;
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