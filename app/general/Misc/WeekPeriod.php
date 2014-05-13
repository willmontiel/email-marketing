<?php

namespace EmailMarketing\General\Misc;

class WeekPeriod extends TimePeriod
{
	public $timePeriods = array();
	public $logger;
	
	public function __construct() 
	{
//		$this->logger = \Phalcon\DI::getDefault()->get('logger');
	}

	protected function createPeriods() 
	{
		$d = $this->start;
		$days = array();
		for ($i=0; $i<8; $i++) {
			$days[] = $d;
			$d = strtotime("next day", $d);
		}
		
		$this->name = 'Semana del ' . date('d/M/y', $this->start) . ' al ' .date('d/M/y', strtotime("next saturday", $this->start));

		return $days;
	}
	
	protected function createChild()
	{
		return new DayPeriod();
	}

}