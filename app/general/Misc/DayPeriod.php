<?php

namespace EmailMarketing\General\Misc;

class DayPeriod extends TimePeriod
{
	public $week;
	public $data;
	public $timePeriods = array();
	
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
	
		if ($this->data > $sun && $this->data < $sat) {
			$days[] = $sun;
			do {
				$sun = strtotime("next day", $sun);
				$days[] = $sun;
			} while ($sun == $sat);
			
			$this->timePeriods['name'] = 'Aperturas por día';
			$this->timePeriods['categories'] = $days;
			$drilldown = array();
		
			for ($j = 0; $j < count($days); $j++) {
				$drilldown[] = $this->createArrayObject('#F2F5A9');
			}
			$this->timePeriods['data'] = $drilldown;
			$this->addDrilldown($days);
		}
	}
	
	public function addDrilldown($days)
	{
		$hourPeriod = new \EmailMarketing\General\Misc\HourPeriod();
		
		$totalDays = count($days);
		
		for ($i = 0; $i < $totalDays; $i++) {
			$start = $days[$i];
			if ($i+1 == $totalDays) {
				$end = $days[$i-1];
			}
			else {
				$end = $days[$i+1];
			}

			if ($this->data > $start && $this->data < $end) {
				$object = $this->timePeriods['data'][$i];
				$object['y'] += 1;
				$hourPeriod->setData($days[$i], $this->data);
				$hourPeriod->processTimePeriod();
				$object['drilldown'] = $hourPeriod->getTimePeriod();
				$this->timePeriods['data'][$i] = $object;
			}
		}
	}
	
	public function getTimePeriod()
	{
		return $this->timePeriods;
	}
}