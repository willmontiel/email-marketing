<?php

namespace EmailMarketing\General\Misc;

class AccountingObject
{
	protected $accounts;
	protected $ids;
	protected $accounting = array();
	protected $contactsMonth;
	protected $sentMonth;


	public function __construct() 
	{
		$this->logger = \Phalcon\DI::getDefault()->get('logger');
	}
	
	public function setAccounts($accounts)
	{
		$this->accounts = $accounts;
	}
	
	public function createCurrentAndLastAccounting()
	{
		foreach ($this->accounts as $account) {
			if (!isset($this->accounting[$account->idAccount])) {
				$this->accounting[$account->idAccount] = array(
					'idAccount' => $account->idAccount,
					'account' => $account->companyName,
					
					'contactsLastMonth' => 0,
					'classLastContact' => '',
					
					'sentLastMonth' => 0,
					'classLastSent' => '',
					
					'contactsCurrentMonth' => 0,
					'classCurrentContact' => '',
					
					'sentCurrentMonth' => 0,
					'classCurrentSent' => '',
				);
			}
		}
		
		
		$current = strtotime("1 " . date('M', time()) . " " . date('Y', time()));
		$lastperiod = strtotime("-1 month", $current);
		$nextperiod = strtotime("+1 month", $current);
		
		$this->createAccounting($lastperiod, $current);
		
		if (count($this->contactsMonth) > 0) {
			foreach ($this->contactsMonth as $lastContact) {
				$this->accounting[$lastContact['idAccount']]['contactsLastMonth'] = $lastContact['total'] ;
			}
		}
		
		if (count($this->sentMonth) > 0) {
			foreach ($this->sentMonth as $lastSent) {
				$this->accounting[$lastSent['idAccount']]['sentLastMonth'] = $lastSent['total'] ;
			}
		}
		
		$this->createAccounting($current, $nextperiod);
		
		if (count($this->contactsMonth) > 0) {
			foreach ($this->contactsMonth as $currentContact) {
				$this->accounting[$currentContact['idAccount']]['contactsCurrentMonth'] = $currentContact['total'];
			}
		}
		
		if (count($this->sentMonth) > 0) {
			foreach ($this->sentMonth as $currentSent) {
				$this->accounting[$currentSent['idAccount']]['sentCurrentMonth'] = $currentSent['total'];
			}
		}
	}
	
	
	public function classColor()
	{
		foreach ($this->accounting as $accounting) {
			if ($accounting['contactsLastMonth'] > $accounting['contactsCurrentMonth']) {
				$this->accounting[$accounting['idAccount']]['classLastContact'] = 'text-green-color';
				$this->accounting[$accounting['idAccount']]['classCurrentContact'] = 'text-red-color';
			}
			else if ($accounting['contactsLastMonth'] < $accounting['contactsCurrentMonth']) {
				$this->accounting[$accounting['idAccount']]['classLastContact'] = 'text-red-color';
				$this->accounting[$accounting['idAccount']]['classCurrentContact'] = 'text-green-color';
			}
			
			if ($accounting['sentLastMonth'] > $accounting['sentCurrentMonth']) {
				$this->accounting[$accounting['idAccount']]['classLastSent'] = 'text-green-color';
				$this->accounting[$accounting['idAccount']]['classCurrentSent'] = 'text-red-color';
			}
			else if ($accounting['sentLastMonth'] < $accounting['sentCurrentMonth']) {
				$this->accounting[$accounting['idAccount']]['classLastSent'] = 'text-red-color';
				$this->accounting[$accounting['idAccount']]['classCurrentSent'] = 'text-green-color';
			}
		}
	}

	protected function createAccounting($firstperiod, $secondperiod)
	{
		$this->contactsMonth = array();
		$this->sentMonth = array();
		
		$db = \Phalcon\DI::getDefault()->get('db');
		
		$monthContactsSQl = $this->getSQLForTotalContacts($firstperiod, $secondperiod);
		$resultC = $db->query($monthContactsSQl);
		$this->contactsMonth = $resultC->fetchAll();		
		
		
		$monthSentSQL = $this->getSQLForTotalMailsSent($firstperiod, $secondperiod);
		$resultS= $db->query($monthSentSQL);
		$this->sentMonth = $resultS->fetchAll();
	}
	
	
	
	protected function getSQLForTotalMailsSent($time1, $time2)
	{
		$sql = "SELECT a.idAccount, COUNT( mc.idContact ) AS total
					FROM mail AS m
					LEFT JOIN mxc AS mc ON ( mc.idMail = m.idMail ) 
					JOIN account AS a ON ( a.idAccount = m.idAccount ) 
				WHERE m.updatedon >= {$time1}
					AND m.updatedon < {$time2}
					AND m.status = 'sent'
				GROUP BY 1 ";
					
		return $sql;
	}


	protected function getSQLForTotalContacts($time1, $time2)
	{
		$sql = "SELECT a.idAccount, SUM(i.actives) AS total
					FROM indicator AS i
					JOIN dbase AS d ON (d.idDbase = i.idDbase)
					JOIN account AS a ON (a.idAccount = d.idAccount)
				WHERE i.date >= {$time1}
					AND i.date < {$time2}
				GROUP BY 1";
		
		return $sql;
	}

	
	protected function createRelationshipDate()
	{
		$currentMonth = date('M', time());
		$year = date('Y', time());
		$t = strtotime("1 {$currentMonth} {$year}");
		
		$firstTime = strtotime("-1 month", $t);
		$secondTime = $t;
		$thirdTime = strtotime("+1 month", $secondTime);
		
		$times = new \stdClass();
		
		$times->lastTime = $firstTime;
		$times->currentTime = $secondTime;
		$times->nextTime = $thirdTime;
		
		return $times;
	}
	
	public function getAccounting()
	{
		$this->logger->log("Accounting: " . print_r($this->accounting, true));
		return $this->accounting;
	}
}