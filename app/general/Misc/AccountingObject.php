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
		
//		$this->logger->log("Accounting: " . print_r($this->accounting, true));
	}	

	protected function createAccounting($times)
	{
		$db = \Phalcon\DI::getDefault()->get('db');
		
		$lastMonthContactsSQl = $this->getSQLForTotalContacts($times->lastTime, $times->currentTime);
		$result1 = $db->query($lastMonthContactsSQl);
		$lastContactsMonth = $result1->fetchAll();
		
		$currentMonthContactsSQL = $this->getSQLForTotalContacts($times->currentTime, $times->nextTime);
//		$this->logger->log("SQL: $currentMonthContactsSQL");
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
				$this->accounting[$currentContact['idAccount']]['contactsCurrentMonth'] = $currentContact['total'];
				if ($currentContact['total'] > $this->accounting[$currentContact['idAccount']]['contactsLastMonth']) {
					$this->accounting[$currentContact['idAccount']]['classCurrentContact'] = 'text-green-color';
					$this->accounting[$currentContact['idAccount']]['classLastContact'] = 'text-red-color';
				}
				else if ($currentContact['total'] < $this->accounting[$currentContact['idAccount']]['contactsLastMonth']) {
					$this->accounting[$currentContact['idAccount']]['classCurrentContact'] = 'text-red-color';
					$this->accounting[$currentContact['idAccount']]['classLastContact'] = 'text-green-color';
				}
			}
		}
		
		if (count($lastSentMonth) > 0) {
			foreach ($lastSentMonth as $lastSent) {
				$this->accounting[$lastSent['idAccount']]['sentLastMonth'] = $lastSent['total'] ;
			}
		}
		
		if (count($currentSentMonth) > 0) {
			foreach ($currentSentMonth as $currentSent) {
				$this->accounting[$currentSent['idAccount']]['sentCurrentMonth'] = $currentSent['total'];
				
				if ($currentSent['total'] > $this->accounting[$currentSent['idAccount']]['sentLastMonth']) {
					$this->logger->log("Entra {$currentSent['idAccount']}");
					$this->accounting[$currentSent['idAccount']]['classCurrentSent'] = 'text-green-color';
					$this->accounting[$currentSent['idAccount']]['classLastSent'] = 'text-red-color';
				}
				else if ($currentSent['total'] < $this->accounting[$currentSent['idAccount']]['sentLastMonth']) {
					$this->logger->log("Entra {$currentSent['idAccount']}");
					$this->accounting[$currentSent['idAccount']]['classCurrentSent'] = 'text-red-color';
					$this->accounting[$currentSent['idAccount']]['classLastSent'] = 'text-green-color';
				}
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
					AND m.status = 'sent'
				GROUP BY 1 ";
					
//		$this->logger->log("SQL: $sql");
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
		
//		$this->logger->log("SQL: $sql");
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