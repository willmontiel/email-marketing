<?php

namespace EmailMarketing\General\Misc;

abstract class TimePeriod
{
	protected $start;
	protected $data;
	protected $children;
	protected $name;

	public function setPeriodStart($start) {
		$this->start = $start;
	}
	
	public function setData($data) {
		$this->data = $data;
	}
	
	public function processTimePeriod()
	{
		$periods = $this->createPeriods();

		$this->children = array();
		
		if (count($periods) <= 0) {
			return;
		}
		
		$st = $periods[0];
		array_shift($periods);
		$p = 0;
		foreach ($periods as $t) {
			$obj = $this->createChild();
			$wdata = array();
			while ($p < count($this->data) && $this->data[$p] < $t) {
				$wdata[] = $this->data[$p];
				$p++;
			}
			$obj->setData($wdata);
			$obj->setPeriodStart($st);
			$obj->processTimePeriod();
			$this->children[] = $obj;
			$st = $t;
			
		}
		
	}
	public function getTimePeriod()
	{
		return $this->children;
	}
	
	public function getTotal()
	{
		return count($this->data);
	}

	public function getPeriodName()
	{
		return $this->name;
	}
	
	protected abstract function createPeriods();
	protected abstract function createChild();
}