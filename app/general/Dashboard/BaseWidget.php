<?php

namespace EmailMarketing\General\Dashboard;

abstract class BaseWidget
{
	
	const CHART_INTERVALS = 15;
	
	protected $totalValue;
	protected $className;
	protected $secondaryValues;
	protected $title;
			
	function __construct($account, $property, $name, $classname = null) {
		$this->account = $account;
		$this->property = $property;
		$this->title = $name;
		$this->className = $classname;
		$this->totalValue = 0;
		$this->secondaryValues = array();
		$this->modelManager = \Phalcon\DI::getDefault()->get('modelsManager');
		$this->logger = \Phalcon\DI::getDefault()->get('logger');
		$this->processData();
	}
	
	public function getTitle()
	{
		return $this->title;
	}


	public function getTotal()
	{
		return $this->totalValue;
	}
	
	public function getSecondaryValues()
	{
		return $this->secondaryValues;
	}
	
	public function getClassName()
	{
		return $this->className;
	}
	
	abstract protected function processData();
}
