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
	
	public function getContactsMonth()
	{
		return $this->contactsMonth;
	}

	public function getSentMonth()
	{
		return $this->sentMonth;
	}
	
	public function setAccounts($accounts)
	{
		$this->accounts = $accounts;
	}
	
	public function setAccountingModel($timeContacts, $timeSent)
	{
		foreach ($this->accounts as $account) {
			if (!isset($this->accounting[$account->idAccount])) {
				$this->accounting[$account->idAccount] = array(
					'idAccount' => $account->idAccount,
					'account' => $account->companyName,
					$timeContacts => 0,
					$timeSent => 0
				);
			}
		}
	}
	
	public function setSimpleAccountingModel(\Account $account, $timeContacts, $timeSent)
	{
		if (!isset($this->accounting[$account->idAccount])) {
			$this->accounting[$account->idAccount] = array(
				'idAccount' => $account->idAccount,
				'account' => $account->companyName,
				$timeContacts => 0,
				$timeSent => 0
			);
		}
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
		$this->processAccountingArray('contactsLastMonth', 'sentLastMonth');
		
		$this->createAccounting($current, $nextperiod);
		$this->processAccountingArray('contactsCurrentMonth', 'sentCurrentMonth');
		
	}
	
	public function processAccountingArray($timeContacts, $timeSent)
	{
		if (count($this->contactsMonth) > 0) {
			foreach ($this->contactsMonth as $totalcontact) {
				$this->accounting[$totalcontact['idAccount']][$timeContacts] = $totalcontact['total'] ;
			}
		}
		
		if (count($this->sentMonth) > 0) {
			foreach ($this->sentMonth as $totalsent) {
				$this->accounting[$totalsent['idAccount']][$timeSent] = $totalsent['total'] ;
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

	public function createAccounting($firstperiod, $secondperiod, $idAccount = null)
	{
		$this->contactsMonth = array();
		$this->sentMonth = array();
		
		$db = \Phalcon\DI::getDefault()->get('db');
		
		$monthContactsSQl = $this->getSQLForTotalContacts($firstperiod, $secondperiod, $idAccount);
		$resultC = $db->query($monthContactsSQl);
		$this->contactsMonth = $resultC->fetchAll();		
		
		
		$monthSentSQL = $this->getSQLForTotalMailsSent($firstperiod, $secondperiod, $idAccount);
		$resultS= $db->query($monthSentSQL);
		$this->sentMonth = $resultS->fetchAll();
	}
	
	protected function getSQLForTotalMailsSent($time1, $time2, $idAccount = null)
	{
		$sql = "SELECT a.idAccount, COUNT( mc.idContact ) AS total
					FROM mail AS m
					LEFT JOIN mxc AS mc ON ( mc.idMail = m.idMail ) 
					JOIN account AS a ON ( a.idAccount = m.idAccount ) 
				WHERE m.updatedon >= {$time1}
					AND m.updatedon < {$time2}
					AND m.status = 'sent'";
					
		if($idAccount != null) {
			$sql.= " AND a.idAccount = {$idAccount}";
		}
		$sql.= " GROUP BY 1 ";
				
		return $sql;
	}

	protected function getSQLForTotalContacts($time1, $time2, $idAccount = null)
	{
		$sql = "SELECT a.idAccount, SUM(i.actives) AS total
					FROM indicator AS i
					JOIN dbase AS d ON (d.idDbase = i.idDbase)
					JOIN account AS a ON (a.idAccount = d.idAccount)
				WHERE i.date >= {$time1}
					AND i.date < {$time2}";
					
		if($idAccount != null) {
			$sql.= " AND a.idAccount = {$idAccount}";
		}
		$sql.= " GROUP BY 1 ";
		
		return $sql;
	}


	public function getAccounting()
	{
		return $this->accounting;
	}
}