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
		
		$time = strtotime("-1 day", $times->currentTime);
		$this->updateTotalContacts($time, $times->currentTime);
		
		$time = strtotime("-1 day", $times->nextTime);
		$this->updateTotalContacts($time, $times->nextTime);
	}
	
	protected function updateTotalContacts($current, $time)
	{
		$sql = "INSERT INTO indicator (idDbase, date, actives, bounced, spam, blocked)
					(SELECT d.idDbase,
							{$current},
							SUM(IF(e.bounced = 0 AND e.spam = 0 AND e.blocked = 0, 1, 0)) AS actives,
							SUM(IF(e.bounced > 0, 1, 0)) AS bounced,
							SUM(IF(e.spam > 0, 1, 0)) AS spam,
							SUM(IF(e.blocked > 0, 1, 0)) AS blocked
						FROM dbase AS d
						JOIN contact AS c ON (c.idDbase = d.idDbase)
						JOIN email AS e ON (e.idEmail = c.idEmail)
					WHERE c.createdon < {$time}
					GROUP BY d.idDbase)
				ON DUPLICATE KEY UPDATE
					actives = VALUES(actives),
					bounced = VALUES(bounced),
					spam = VALUES(spam),
					blocked = VALUES(blocked)";
//		$this->logger->log("SQL {$sql}");
		$this->db->execute($sql);
	}
	
	protected function createRelationshipDate()
	{
		$month = date('M', time());
		$year = date('Y', time());
		
		$t = strtotime("1 {$month} {$year}");
		
		$firstTime = strtotime("-1 month", $t);
		$secondTime = $t;
		$thirdTime = strtotime("+1 month", $secondTime);
		
		$times = new \stdClass();
		
		$times->lastTime = $firstTime;
		$times->currentTime = $secondTime;
		$times->nextTime = $thirdTime;
		
		return $times;
	}
}