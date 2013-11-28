<?php
abstract class Handler
{
	public $registry;
	public $di;

	public function __construct($registry)
	{
		$this->registry = $registry;
		$this->di =  \Phalcon\DI\FactoryDefault::getDefault();
	}
	
	public function register()
	{
		$events = $this->getEvents();
		
		foreach ($events as $event) {
			$this->registry->registerEvent($event, $this);
		}
		
	}
	
	abstract public function getEvents();
	abstract public function handleEvent(Event $event);
}