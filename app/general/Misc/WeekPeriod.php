<?php

namespace EmailMarketing\General\Misc;

class WeekPeriod extends TimePeriod
{
	public $data;
	public $timePeriods = array();
	public $logger;
	
	public function __construct() 
	{
		$this->logger = \Phalcon\DI::getDefault()->get('logger');
	}

	public function setData($data, $date = null)
	{
		$this->data = $data;
	}
	
	public function processTimePeriod()
	{
		$min = min($this->data);
		$max = max($this->data);

		$weeks = array();

		if (date('N', $min != 7)) {
			$sunday = strtotime("last sunday", $min);
		}
		else {
			$sunday = strtotime("midnight", $min);
		}

		$weeks[] = $sunday;

		$date = $sunday;
		
		do {
			$date = strtotime("next sunday", $date);
			$weeks[] = $date;
		} while ($date < $max);

		$this->timePeriods[0]['categories'] = $weeks;
		$this->timePeriods[0]['name'] = 'Aperturas por semana';
		$drilldown = array();
		
		for ($j = 0; $j < count($weeks); $j++) {
			$drilldown[] = $this->createArrayObject('#2E9AFE');
		}
		
		$this->timePeriods[0]['data'] = $drilldown;
		$this->addDrilldown($weeks);
	}
	
	private function addDrilldown($weeks)
	{
		$dayPeriod = new \EmailMarketing\General\Misc\DayPeriod();
		foreach ($this->data as $data) {
			$totalWeeks = count($weeks);
			for ($i = 0; $i < $totalWeeks; $i++) {
				$start = $weeks[$i];
				if ($i+1 == $totalWeeks) {
					$end = $weeks[$i-1];
				}
				else {
					$end = $weeks[$i+1];
				}

				if ($data > $start && $data < $end) {
					$object = $this->timePeriods[0]['data'][$i];
					$object['y'] += 1;
					$dayPeriod->setData($weeks[$i], $data);
					$dayPeriod->processTimePeriod();
					$object['drilldown'] = $dayPeriod->getTimePeriod();
					$this->timePeriods[0]['data'][$i] = $object;
				}
			}
		}
	}
	
	public function getTimePeriod()
	{
		return $this->timePeriods;
	}	
}