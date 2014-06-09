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
		$this->updateTotalContacts();
	}
	
	protected function updateTotalContacts()
	{
		$times = $this->createRelationshipDate();
		
		$sql = "INSERT IGNORE INTO indicator (idDbase, date, actives, bounced, spam, blocked)
					(SELECT d.idDbase,
							{$times->currentTime},
							SUM(IF(e.bounced = 0 AND e.spam = 0 AND e.blocked = 0, 1, 0)),
							SUM(IF(e.bounced > 0, 1, 0)),
							SUM(IF(e.spam > 0, 1, 0)),
							SUM(IF(e.blocked > 0, 1, 0))
						FROM dbase AS d
						JOIN contact AS c ON (c.idDbase = d.idDbase)
						JOIN email AS e ON (e.idEmail = c.idEmail)
					WHERE c.createdon < {$times->nextTime}
					GROUP BY d.idDbase)";
		$this->logger->log("SQL {$sql}");
		$this->db->execute($sql);
	}
	
	protected function createRelationshipDate()
	{
		$currentMonth = date('M', time());
		$year = date('Y', time());
		$t = strtotime("1 {$currentMonth} {$year}");
		
		$nextTime = strtotime("+1 month", $t);
		$currentTime = strtotime("-1 day", $nextTime);
		
		
		$times = new \stdClass();
		
		$times->currentTime = $currentTime;
		$times->nextTime = $nextTime;
		
		return $times;
	}
}