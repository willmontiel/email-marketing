<?php

namespace EmailMarketing\General\Misc;

class TimePeriodModel
{
	protected $timePeriod;
	protected $model = array();


	public function __construct() 
	{
		$this->logger = \Phalcon\DI::getDefault()->get('logger');
		$this->model = $this->createDrilldownObject("Aperturas por semana");
	}
	
	public function setTimePeriod(TimePeriod $timePeriod)
	{
		$this->timePeriod = $timePeriod;
	}
	
	public function modelTimePeriod($obj = null, $ind = 0)
	{
		if ($obj == null) {
			$obj = $this->timePeriod;
		}
		
		if ($ind == 1) {
			$this->model['categories'][] = $obj->getPeriodName();
			$array = $this->createArrayObject('#81BEF7');
			$array['y'] = $obj->getTotal();

			$dayModel = null;
			if ($obj->getTotal() != 0) {
				foreach ($obj->getTimePeriod() as $child) {
					$dayModel = $this->addDayData($child, $obj->getPeriodName(), $dayModel);
				}
			}
			
			$array['drilldown'] = $dayModel;
			$this->model['data'][] = $array;
		}
		
		foreach ($obj->getTimePeriod() as $child) {
			$this->modelTimePeriod($child, $ind+1);
		}
	}
	
	protected function addDayData($obj, $name = null, $model = null)
	{
		if ($model == null) {
			$model = $this->createDrilldownObject($name);
		}
		$model['categories'][] = $obj->getPeriodName();
		$array = $this->createArrayObject('#58FAAC');
		$array['y'] = $obj->getTotal();
		
		$hourModel = null;
		foreach ($obj->getTimePeriod() as $child) {
			$hourModel = $this->addHourData($child, $obj->getPeriodName(), $hourModel);
		}
		$array['drilldown'] = $hourModel;
		$model['data'][] = $array;
		
		return $model;
	}
	
	protected function addHourData($data, $name = null, $hourModel = null)
	{
		if ($hourModel == null) {
			$hourModel = $this->createDrilldownObject($name);
		}
		$hourModel['categories'][] = $data->getPeriodName();
		$hourModel['data'][] = $data->getTotal();
		
		return $hourModel;
	}
		
	protected function createArrayObject($color) 
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
		
		return $object;
	}

	public function getModelTimePeriod()
	{
		return $this->model;
	}
}
