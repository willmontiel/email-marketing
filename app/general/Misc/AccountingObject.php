<?php

namespace EmailMarketing\General\Misc;

class AccountingObject
{
	protected $accounts;
	protected $ids;
	protected $accounting;


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
		$this->createAccountIdentifiers();
		$times = $this->createRelationshipDate();
		$values = $this->createAccounting($times);
		$this->accounting = $this->processData($values);
	}
	
	protected function createAccountIdentifiers()
	{
		$ids = array();
		foreach ($this->accounts as $account) {
			$ids[] = $account->idAccount;
		}
		
		$this->ids = implode(',', $ids);
	}


	protected function createAccounting($times)
	{
		$db = \Phalcon\DI::getDefault()->get('db');
		
		$lastMonthSQl = $this->getSql($times->lastTime, $times->currentTime);
		$result1 = $db->query($lastMonthSQl);
		$lastMonth = $result1->fetchAll();
		
		
		$currentMonthSQL = $this->getSql($times->currentTime, $times->nextTime);
		$result2 = $db->query($currentMonthSQL);
		$currentMonth = $result2->fetchAll();
		
		$array = array();
		
		foreach ($lastMonth as $last) {
			foreach ($currentMonth as $current) {
				if ($last['idAccount'] == $current['idAccount']) {
					$array[] = array(
						'idAccount' => $last['idAccount'],
						'account' => $last['companyName'],
						'lastMonth' => $last['actives'],
						'currentMonth' => $current['actives']
					);
				}
			}
		}
		
		return $array;
	}
	
	protected function processData($values)
	{
		foreach ($values as $value) {
			foreach ($this->accounts as $account) {
				if ($account->idAccount !== $value['idAccount']) {
					$values[] = array(
						'idAccount' => $account->idAccount,
						'account' => $account->companyName,
						'lastMonth' => 0,
						'currentMonth' => 0
					);
				}
			}
		}
		
		return $values;
	}
	
	protected function getSql($time1, $time2)
	{
		$sql = "SELECT a.idAccount, a.companyName, COUNT(c.idContact) AS actives
						  FROM account AS a
						  JOIN dbase AS d ON (d.idAccount = a.idAccount) 
						  JOIN contact AS c ON (c.idDbase = d.idDbase) 
						  JOIN email AS e ON (e.idEmail = c.idEmail)
					  WHERE a.idAccount IN ({$this->ids})
						  AND c.createdon > {$time1}
						  AND c.createdon < {$time2}
						  AND c.unsubscribed = 0
						  AND e.bounced = 0
						  AND e.spam = 0
					 	  AND e.blocked = 0
						  GROUP BY 1, 2";
		
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
		return $this->accounting;
	}
}