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
		$times = $this->createRelationshipDate();
		$values = $this->createAccounting($times);
		$this->accounting = $this->processData($values);
	}
	
	protected function createAccounting($times)
	{
		$db = \Phalcon\DI::getDefault()->get('db');
		
		$lastMonthSQl = $this->getSql($times->currentTime);
		$result1 = $db->query($lastMonthSQl);
		$lastMonth = $result1->fetchAll();
		
		$currentMonthSQL = $this->getSql($times->nextTime);
		$result2 = $db->query($currentMonthSQL);
		$currentMonth = $result2->fetchAll();
		
		$array = array();
		
		if (count($lastMonth) > 0 && count($lastMonth) > 0) {
			foreach ($lastMonth as $last) {
				foreach ($currentMonth as $current) {
					if ($last['idAccount'] == $current['idAccount']) {
						$array[$current['idAccount']] = array(
							'idAccount' => $last['idAccount'],
							'account' => $last['companyName'],
							'lastMonth' => $last['actives'],
							'currentMonth' => $current['actives']
						);
					}
				}
			}
		}
		else if (count($lastMonth) > 0) {
			foreach ($lastMonth as $last) {
				$array[$current['idAccount']] = array(
					'idAccount' => $last['idAccount'],
					'account' => $last['companyName'],
					'lastMonth' => $last['actives'],
					'currentMonth' => 0
				);
			}
		}
		else if (count($currentMonth) > 0) {
			foreach ($currentMonth as $current) {
				$array[$current['idAccount']] = array(
					'idAccount' => $current['idAccount'],
					'account' => $current['companyName'],
					'lastMonth' => 0,
					'currentMonth' => $current['actives']
				);
			}
		}
		
		$this->logger->log(print_r($array, true));
		return $array;
	}
	
	protected function processData($values)
	{
		foreach ($this->accounts as $account) {
			if (!isset($values[$account->idAccount])) {
				$values[$account->idAccount] = array(
					'idAccount' => $account->idAccount,
					'account' => $account->companyName,
					'lastMonth' => 0,
					'currentMonth' => 0
				);
			}
		}
		
		$this->logger->log(print_r($values, true));
		return $values;
	}
	
	protected function getSql($time)
	{
		$sql = "SELECT a.idAccount, a.companyName, SUM(i.actives) AS actives
					FROM indicator AS i
					JOIN dbase AS d ON (d.idDbase = i.idDbase)
					LEFT JOIN account AS a ON (a.idAccount = d.idAccount)
				WHERE i.date < {$time}
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