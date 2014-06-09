<?php

namespace EmailMarketing\General\Misc;

class AccountingObject
{
	protected $accounts;
	protected $ids;
	protected $accounting = array();


	public function __construct() 
	{
		$this->logger = \Phalcon\DI::getDefault()->get('logger');
	}
	
	public function setAccounts($accounts)
	{
		$this->accounts = $accounts;
	}
	
	
	public function startAccounting()
	{
		$this->modelAccounts();
		$times = $this->createRelationshipDate();
		$this->createAccounting($times);
	}
	
	protected function modelAccounts()
	{
		foreach ($this->accounts as $account) {
			if (!isset($this->accounting[$account->idAccount])) {
				$this->accounting[$account->idAccount] = array(
					'idAccount' => $account->idAccount,
					'account' => $account->companyName,
					
					'contactsLastMonth' => 0,
					'sentLastMonth' => 0,
					
					'contactsCurrentMonth' => 0,
					'sentCurrentMonth' => 0,
				);
			}
		}
		
		$this->logger->log("Accounting: " . print_r($this->accounting, true));
	}	

	protected function createAccounting($times)
	{
		$db = \Phalcon\DI::getDefault()->get('db');
		
		$lastMonthContactsSQl = $this->getSQLForTotalContacts($times->currentTime);
		$result1 = $db->query($lastMonthContactsSQl);
		$lastContactsMonth = $result1->fetchAll();
		
		$currentMonthContactsSQL = $this->getSQLForTotalContacts($times->nextTime);
		$result2 = $db->query($currentMonthContactsSQL);
		$currentContactsMonth = $result2->fetchAll();
		
		
		
		$lastMonthSentSQL = $this->getSQLForTotalMailsSent($times->lastTime, $times->currentTime);
		$result4= $db->query($lastMonthSentSQL);
		$lastSentMonth = $result4->fetchAll();
		
		$currentMonthSentSQL = $this->getSQLForTotalMailsSent($times->currentTime, $times->nextTime);
		$result3 = $db->query($currentMonthSentSQL);
		$currentSentMonth = $result3->fetchAll();
		
		if (count($lastContactsMonth) > 0) {
			foreach ($lastContactsMonth as $lastContact) {
				$this->accounting[$lastContact['idAccount']]['contactsLastMonth'] = $lastContact['total'] ;
			}
		}
				
		if (count($currentContactsMonth) > 0) {
			foreach ($currentContactsMonth as $currentContact) {
				$this->accounting[$currentContact['idAccount']]['contactsCurrentMonth'] = $currentContact['total'] ;
			}
		}
		
		if (count($lastSentMonth) > 0) {
			foreach ($lastSentMonth as $lastSent) {
				$this->accounting[$lastSent['idAccount']]['sentLastMonth'] = $lastSent['total'] ;
			}
		}
		
		if (count($currentSentMonth) > 0) {
			foreach ($currentSentMonth as $currentSent) {
				$this->accounting[$currentSent['idAccount']]['sentCurrentMonth'] = $currentSent['total'] ;
			}
		}
	}
	
	
	
	protected function getSQLForTotalMailsSent($time1, $time2)
	{
		$sql = "SELECT a.idAccount, COUNT( mc.idContact ) AS total
					FROM mail AS m
					LEFT JOIN mxc AS mc ON ( mc.idMail = m.idMail ) 
					JOIN account AS a ON ( a.idAccount = m.idAccount ) 
				WHERE m.updatedon >= {$time1}
					AND m.updatedon < {$time2}
				GROUP BY 1 ";
					
		$this->logger->log("SQL: $sql");
		return $sql;
	}


	protected function getSQLForTotalContacts($time)
	{
		$sql = "SELECT a.idAccount, SUM(i.actives) AS total
					FROM indicator AS i
					JOIN dbase AS d ON (d.idDbase = i.idDbase)
					JOIN account AS a ON (a.idAccount = d.idAccount)
				WHERE i.date < {$time}
				GROUP BY 1";
		
		$this->logger->log("SQL: $sql");
		return $sql;
	}

	protected function createRelationshipDate()
	{
		$currentMonth = date('M', time());
		
		switch ($currentMonth) {
			case 'Jan':
				$time = $this->createTimes('Dec', 'Jan', 'Feb');
				break;

			case 'Feb':
				$time = $this->createTimes('Jan', 'Feb', 'Mar');
				break;

			case 'Mar':
				$time = $this->createTimes('Feb', 'Mar', 'Apr');
				break;

			case 'Apr':
				$time = $this->createTimes('Mar', 'Apr', 'May');
				break;

			case 'May':
				$time = $this->createTimes('Apr', 'May', 'Jun');
				break;

			case 'Jun':
				$time = $this->createTimes('May', 'Jun', 'Jul');
				break;

			case 'Jul':
				$time = $this->createTimes('Jun', 'Jul', 'Aug');
				break;

			case 'Aug':
				$time = $this->createTimes('Jul', 'Aug', 'Sep');
				break;

			case 'Sep':
				$time = $this->createTimes('Aug', 'Sep', 'Oct');
				break;

			case 'Oct':
				$time = $this->createTimes('Sep', 'Oct', 'Nov');
				break;

			case 'Nov':
				$time = $this->createTimes('Oct', 'Nov', 'Dec');
				break;

			case 'Dec':
				$time = $this->createTimes('Nov', 'Dec', 'Jan');
				break;
		}
		
		return $time;
	}


	protected function createTimes($last, $current, $next)
	{
		$year = date('Y', time());

		$currentTime = strtotime("1 {$current} {$year}");

		$year1 = ($next == 'Jan' ? $year+1 : $year);
		$nextTime = strtotime("1 {$next} {$year1}");

		$year2 = ($last == 'Dec' ? $year-1 : $year);
		$lastTime = strtotime("1 {$last} {$year2}");

		$times = new \stdClass();
		
		$times->lastTime = $lastTime;
		$times->currentTime = $currentTime;
		$times->nextTime = $nextTime;
		
		return $times;
	}
	
	public function getAccounting()
	{
		$this->logger->log("Accounting: " . print_r($this->accounting, true));
		return $this->accounting;
	}
}