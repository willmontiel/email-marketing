<?php
class Registry
{
	protected $events;

	function __construct() {
		$this->events = array();
	}

	public function getEvents()
	{
		return array_keys($this->events);
	}
	
	public function registerEvent($event, $handler)
	{
		if(isset($this->events[$event])) {
			$this->events[$event][] = $handler;
		}
		else {
			$this->events[$event] = array($handler);
		}
	}
	
	public function unregisterEvent($event)
	{
		unset($this->events[$event]);
	}

	public function handleEvent(Event $event)
	{
	
		if(isset($this->events[$event->type])) {
			$handlers = $this->events[$event->type];
			
			foreach ($handlers as $handler) {
				$handler->handleEvent($event);
			}
		}
		else {
			printf('Este evento no existe! '.$event->type. PHP_EOL);
		}
	}	
}