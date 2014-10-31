<?php

namespace EmailMarketing\General\Misc;

class RuleInterpreter
{
	protected $logger;
	protected $object;

	public function __construct() 
	{
		$this->logger = \Phalcon\DI::getDefault()->get('logger');
	}
	
	public function setObject($object) 
	{
		$this->object = $object;
	}
	
	public function validateObject()
	{
		
	}	
}