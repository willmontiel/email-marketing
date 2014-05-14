<?php

namespace EmailMarketing\General\Misc;

class TimePeriodModel
{
	protected $timePeriod;
	protected $type;
	protected $model = array();


	public function __construct($type) 
	{
		$this->logger = \Phalcon\DI::getDefault()->get('logger');
		$this->type = $type;
		$this->model = $this->createDrilldownObject("{$this->type} por semana");
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
			$array = $this->createArrayObject('#8dc63f');
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
		$array = $this->createArrayObject('#197b30');
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
		$hourModel['color'] = '#005826';
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
