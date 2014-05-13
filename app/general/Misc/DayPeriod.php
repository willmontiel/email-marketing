<?php

namespace EmailMarketing\General\Misc;

class DayPeriod extends TimePeriod
{
	public $timePeriods = array();
	public $logger;
	
	public function __construct() 
	{
		//$this->logger = \Phalcon\DI::getDefault()->get('logger');
	}
	
	protected function createPeriods()
	{
		$hours = array();
		$h = $this->start;
		
		for ($i=0; $i<25; $i++) {
			$hours[] = $h;
			$h += 3600;
		}
		
		$this->name = date('D d/M', $this->start);

		return $hours;
	}
	
	protected function createChild()
	{
		return new HourPeriod();
	}

}