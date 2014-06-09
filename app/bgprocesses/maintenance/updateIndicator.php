<?php
require_once '../bootstrap/phbootstrap.php';

try {
	$indicator = new IndicatorObject();
	$indicator->update();
}
catch (Exception $e) {
	echo $e;
}


class IndicatorObject
{
	protected $logger;
	protected $db;
	
	public function __construct() 
	{
		
		$this->logger = Phalcon\DI::getDefault()->get('logger');
		$this->db = Phalcon\DI::getDefault()->get('db');
	}
	
	public function update()
	{
		$times = $this->createRelationshipDate();
		$this->updateTotalContacts($times->lastTime, $times->currentTime);
		$this->updateTotalContacts($times->currentTime, $times->nextTime);
	}
	
	protected function updateTotalContacts($time1, $time2)
	{
//		$time = strtotime("-1 day", $time1);
		
		$sql = "INSERT IGNORE INTO indicator (idDbase, date, actives, bounced, spam, blocked)
					(SELECT d.idDbase,
							{$time1},
							SUM(IF(e.bounced = 0 AND e.spam = 0 AND e.blocked = 0, 1, 0)),
							SUM(IF(e.bounced > 0, 1, 0)),
							SUM(IF(e.spam > 0, 1, 0)),
							SUM(IF(e.blocked > 0, 1, 0))
						FROM dbase AS d
						JOIN contact AS c ON (c.idDbase = d.idDbase)
						JOIN email AS e ON (e.idEmail = c.idEmail)
					WHERE c.createdon < {$time2}
					GROUP BY d.idDbase)";
//		$this->logger->log("SQL {$sql}");
		$this->db->execute($sql);
	}
	
	protected function createRelationshipDate()
	{
		$currentMonth = date('M', time());
		$year = date('Y', time());
		$t = strtotime("1 {$currentMonth} {$year}");
		
		$firstTime = strtotime("-1 month", $t);
		$secondTime = time();
		$thirdTime = strtotime("+1 month", $secondTime);
		
		$times = new \stdClass();
		
		$times->lastTime = $firstTime;
		$times->currentTime = $secondTime;
		$times->nextTime = $thirdTime;
		
		return $times;
	}
}