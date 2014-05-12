<?php
namespace EmailMarketing\General\Misc;

abstract class TimePeriod
{
	public function createArrayObject($color) 
	{
		$object = array();
		$object['y'] = 0;
		$object['color'] = $color;
		$object['drilldown'] = array();

		return $object;
	}
	
	public function createDrilldownObject($name)
	{
		$object = array();
		$object['name'] = $name;
		$object['categories'] = array();
		$object['data'] = array();
	}
	
	abstract function setData($data, $date);	
	abstract function processTimePeriod();
	abstract function getTimePeriod();
}