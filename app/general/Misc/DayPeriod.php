<?php

namespace EmailMarketing\General\Misc;

class DayPeriod extends TimePeriod
{
	public $week;
	public $data;
	public $timePeriods = array();
	public $logger;
	
	public function __construct() 
	{
		$this->logger = \Phalcon\DI::getDefault()->get('logger');
	}
	
	public function setData($week, $data)
	{
		$this->week = $week;
		$this->data = $data;
	}
	
	public function processTimePeriod()
	{
		$sun = $this->week;
		$sat = strtotime("next saturday", $sun);
		$days = array();
	
		$days[] = $sun;
		do {
			$sun = strtotime("next day", $sun);
			$days[] = $sun;
		} while ($sun < $sat);

		$this->timePeriods['name'] = 'Aperturas por día';
		$this->timePeriods['categories'] = $days;
		$drilldown = array();

		for ($j = 0; $j < count($days); $j++) {
			$drilldown[] = $this->createArrayObject('#F2F5A9');
		}
		
		if (count($this->timePeriods['data']) == 0) {
			$this->timePeriods['data'] = $drilldown;
		}
		$this->addDrilldown($days);
	}
	
	public function addDrilldown($days)
	{
		$hourPeriod = new \EmailMarketing\General\Misc\HourPeriod();
		
		$totalDays = count($days);
		
		for ($i = 0; $i < $totalDays; $i++) {
			$start = $days[$i];
			$end = strtotime("23 hours 59 minutes 59 seconds", $days[$i]);
			
			if ($this->data > $start && $this->data < $end) {
//				$this->logger->log("start: " . date("d/m/Y H:s", $start));
//				$this->logger->log("actual: " . date("d/m/Y H:s", $this->data));
//				$this->logger->log("end: " . date("d/m/Y H:s", $end));
				
				$object = $this->timePeriods['data'][$i];
//				$this->logger->log("antes: " . print_r($object, true));
				$object['y'] += 1;
				$hourPeriod->setData($days[$i], $this->data);
				$hourPeriod->processTimePeriod();
				$object['drilldown'] = $hourPeriod->getTimePeriod();
//				$this->logger->log("depues: " . print_r($object, true));
				$this->timePeriods['data'][$i] = $object;
			}
		}
	}
	
	public function getTimePeriod()
	{
		return $this->timePeriods;
	}
}