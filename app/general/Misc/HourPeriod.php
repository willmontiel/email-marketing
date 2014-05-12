<?php

namespace EmailMarketing\General\Misc;

class HourPeriod extends TimePeriod
{
	public $day;
	public $data;
	public $timePeriods = array();
	
	public function setData($day, $data)
	{
		$this->day = $day;
		$this->data = $data;
	}
	
	public function processTimePeriod()
	{
		$max = strtotime("+24 hours", $this->day);
		if ($this->data > $this->day && $this->data < $max) {
			$hours = array();
			$date = $this->day;
			$hours[] = $date;
			do {
				$date = strtotime("+1 hour", $date);
				$hours[] = $date;
			} while ($date == $max);
			
			$this->timePeriods['name'] = "Aperturas por hora";
			$this->timePeriods['categories'] = $hours;
			$drilldown = array();
			
			for ($j = 0; $j < count($hours); $j++) {
				$drilldown[] = $this->createArrayObject('#F5A9A9');
			}
			
			$this->timePeriods['data'] = $drilldown;
			
			$this->addDrilldown($hours);
		}
	}
	
	public function addDrilldown($hours)
	{		
		$totalHours = count($hours);
		
		for ($i = 0; $i < $totalHours; $i++) {
			$start = $hours[$i];
			if ($i+1 == $totalHours) {
				$end = $hours[$i-1];
			}
			else {
				$end = $hours[$i+1];
			}

			if ($this->data > $start && $this->data < $end) {
				$object = $this->timePeriods['data'][$i];
				$object['y'] += 1;
				$this->timePeriods['data'][$i] = $object;
			}
		}
	}
	
	public function getTimePeriod()
	{
		return $this->timePeriods;
	}
}
