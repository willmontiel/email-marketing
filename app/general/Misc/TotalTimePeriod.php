<?php
 
namespace EmailMarketing\General\Misc;

class TotalTimePeriod extends TimePeriod
{
	public $data;
	public $timePeriods = array();

	public function setData($data, $date = null)
	{
		$this->data = $data;
	}
	
	public function processTimePeriod()
	{
		$weekPeriod = new \EmailMarketing\General\Misc\WeekPeriod();
		$weekPeriod->setData($this->data);
		$weekPeriod->processTimePeriod();
		$this->timePeriods = $weekPeriod->getTimePeriod();
	}
	
	public function getTimePeriod()
	{
		return $this->timePeriods;
	}	
}
