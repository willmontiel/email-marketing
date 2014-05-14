<?php
 
namespace EmailMarketing\General\Misc;

class TotalTimePeriod extends TimePeriod
{
	
	public function __construct() 
	{
	//	$this->logger = \Phalcon\DI::getDefault()->get('logger');
	}
	
	protected function createChild()
	{
		return new WeekPeriod();
	}
	
	protected function createPeriods()
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
		$last = strtotime("next sunday", $date);
		
		$this->name = date('Y-m-d', $weeks[0]) . ' - ' . date('Y-m-d', $last);
		
		return $weeks;
	}
}
