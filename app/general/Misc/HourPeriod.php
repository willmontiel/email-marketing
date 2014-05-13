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
		$max = strtotime("+23 hours", $this->day);
		
		$hours = array();
		$date = $this->day;
		$hours[] = $date;
		do {
			$date = strtotime("+1 hour", $date);
			$hours[] = $date;
		} while ($date < $max);

		$this->timePeriods['name'] = "Aperturas por hora";
		$this->timePeriods['categories'] = $hours;
		$drilldown = array();

		for ($j = 0; $j < count($hours); $j++) {
			$drilldown[] = $this->createArrayObject('#F5A9A9');
		}
		
		if (count($this->timePeriods['data']) == 0) {
			$this->timePeriods['data'] = $drilldown;
		}

		$this->addDrilldown($hours);
	}
	
	public function addDrilldown($hours)
	{		
		$totalHours = count($hours);
		
		for ($i = 0; $i < $totalHours; $i++) {
			$start = $hours[$i];
			$end = strtotime("+1 hour", $hours[$i]);

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
