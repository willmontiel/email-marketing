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
		$this->getAndSaveReportData($name, $filesPath);
		
		$db->execute($deletetable);
		
		$report = new Mailreportfile();
		
		$report->idMail = $this->mail->idMail;
		$report->type = $this->type;
		$report->name = $name;
		$report->createdon = time();
		
		if (!$report->save()) {
			throw new \InvalidArgumentException('Error while creating report');
		}
		else {
			return $report;
		}
	}
	
	protected function getAndSaveReportData($name, $dir)
	{
		switch ($this->type) {
			case 'opens':
				$this->saveOpenReport($name, $dir);
				break;
			case 'clicks':
				$this->saveClicksReport($name, $dir);
				break;
			case 'unsubscribed':
				$this->saveUnsubscribedReport($name, $dir);
				break;
			case 'bounced':
				break;
		}
	}
	
	protected function saveOpenReport($name, $dir)
	{
		$db = Phalcon\DI::getDefault()->get('db');
		
		$opencontact[] = array(
			'id' => 100,
			'email' => 'recipient00001@test001.local.discardallmail.drh.net',
			'date' => 1386687891,
			'os' => 'Ubuntu'
		);

		$opencontact[] = array(
			'id' => 145,
			'email' => 'recipient00002@test002.local.discardallmail.drh.net',
			'date' => 1386687891,
			'os' => 'Windows'
		);

		$opencontact[] = array(
			'id' => 161,
			'email' => 'recipient00003@test003.local.discardallmail.drh.net',
			'date' => 1386687891,
			'os' => 'Windows'
		);

		$opencontact[] = array(
			'id' => 199,
			'email' => 'recipient00003@test007.local.discardallmail.drh.net',
			'date' => 1386688891,
			'os' => 'Windows Phone'
		);
		
		$v = " ";
		foreach ($opencontact as $o) {
			$v .= "(" . $this->mail->idMail . ", " . "'opens'" . ", '" . $o['email'] . "', '" . $o['os'] . "', " .$o['date'] .")";
		}
		
		$values = str_replace(")(", "),(", $v);
//		Phalcon\DI::getDefault()->get('logger')->log("Values: " . $values);
	
		$db->execute("INSERT INTO $this->tablename (idMail, reportType, email, os, date) VALUES $values");
		
		$report =  "SELECT email, name, lastName, os, FROM_UNIXTIME(date, '%d-%M-%Y %H:%i:%s')
						FROM {$this->tablename}
						INTO OUTFILE  '{$dir}{$name}'
						FIELDS TERMINATED BY ','
						ENCLOSED BY '\"'
						LINES TERMINATED BY '\n'";

		$db->execute($report);
	}
	
	protected function saveClicksReport($name, $dir)
	{
		$db = Phalcon\DI::getDefault()->get('db');
		
		$clickcontact[] = array(
			'id' => 100,
			'email' => 'otrocorreo@otro.correo',
			'date' => 1386878942,
			'link' => 'https://www.google.com'
		);

		$clickcontact[] = array(
			'id' => 145,
			'email' => 'otrocorreo2@otro2.correo2',
			'date' => 1386747891,
			'link' => 'https://www.facebook.com'
		);

		$clickcontact[] = array(
			'id' => 161,
			'email' => 'otrocorreo3@otro3.correo3',
			'date' => 1386698537,
			'link' => 'https://www.google.com'
		);

		$v = " ";
		
		foreach ($clickcontact as $c) {
			$v .= "(" . $this->mail->idMail . ", " . "'clicks'" . ", '" . $c['email'] . "', '" . $c['link'] . "', " .$c['date'] .")";
		}
		
		$values = str_replace(")(", "),(", $v);
		
//		Phalcon\DI::getDefault()->get('logger')->log("Values: " . $values);
		
		$db->execute("INSERT INTO $this->tablename (idMail, reportType, email, link, date) VALUES $values");
		
		$report =  "SELECT email, link, FROM_UNIXTIME(date, '%d-%M-%Y %H:%i:%s')
						FROM {$this->tablename}
						INTO OUTFILE  '{$dir}{$name}'
						FIELDS TERMINATED BY ','
						ENCLOSED BY '\"'
						LINES TERMINATED BY '\n'";

		$db->execute($report);
	}
	
	protected function saveUnsubscribedReport($name, $dir)
	{
		$db = Phalcon\DI::getDefault()->get('db');
		
		$unsubscribedcontact[] = array(
			'id' => 20,
			'email' => 'newmail@new.mail',
			'date' => 1386687891,
			'name' => 'fulano',
			'lastname' => ''
		);
		
		$unsubscribedcontact[] = array(
			'id' => 240,
			'email' => 'newmail1@new1.mail1',
			'date' => 1386687891,
			'name' => '',
			'lastname' => 'perez2'
		);
		
		$unsubscribedcontact[] = array(
			'id' => 57,
			'email' => 'newmail2@new2.mail2',
			'date' => 1386687891,
			'name' => 'fulano3',
			'lastname' => 'perez3'
		);
		
		$unsubscribedcontact[] = array(
			'id' => 161,
			'email' => 'otrocorreo3@otro3.correo3',
			'date' => 1386687891,
			'name' => '',
			'lastname' => ''
		);
		
		$v = " ";
		
		foreach ($unsubscribedcontact as $u) {
			$v .= "(" . $this->mail->idMail . ", " . "'unsubscribed'" . ", '" . $u['email'] . "', '" . $u['name'] . "', '" . $u['lastname'] . "', " . $u['date'] .")";
		}
		
		$values = str_replace(")(", "),(", $v);
		
//		Phalcon\DI::getDefault()->get('logger')->log("Values: " . $values);
		
		$db->execute("INSERT INTO $this->tablename (idMail, reportType, email, name, lastName, date) VALUES $values");
		
		$report =  "SELECT email, name, lastName, FROM_UNIXTIME(date, '%d-%M-%Y %H:%i:%s')
						FROM {$this->tablename}
						INTO OUTFILE  '{$dir}{$name}'
						FIELDS TERMINATED BY ','
						ENCLOSED BY '\"'
						LINES TERMINATED BY '\n'";

		$db->execute($report);
	}
	
	protected function saveBouncedReport($name, $dir)
	{
		
	}
}