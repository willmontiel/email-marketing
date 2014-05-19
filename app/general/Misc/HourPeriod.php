<?php

namespace EmailMarketing\General\Misc;

class HourPeriod extends TimePeriod
{
	
	public function __construct() 
	{
		$this->children = array();
	}


	
	protected function createPeriods()
	{
		$this->name = date('H', $this->start).':00';

		return array();
	}

	protected function createChild()
	{
		return null;
	}

}
