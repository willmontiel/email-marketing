<?php
class PoolHandler extends Handler
{	
	protected $children = array();
	protected $tmpChildren = array();
	const NUMBER_MAX_OF_TMP_CHILDREN = 2;
	const INITIAL_CHILDREN = 4;

	protected $tasks;
	
	public function getEvents()
	{
		$events = array('Idle', 'WP');
		
		return $events;
	}
	
	public function setTasks(TasksHandler $tasks)
	{
		$this->tasks = $tasks;
	}
	
	public function handleEvent(Event $event)
	{
		switch ($event->type){
			case 'Idle':
				$this->childrenWithOutConfirmation();
				break;
			case 'WP':
				$this->deadChildren();
				break;
		}
	}
	public function createInitialChildren()
	{
		for ($i=0; $i<self::INITIAL_CHILDREN; $i++) {
			$this->newChild();
		}
	}
	public function newChild($temporary = false)
	{
		$child = new ChildHandler($this->registry);
		if (!$temporary) {
			$this->children[] = $child->forkChild();
		}
		else {
			$this->tmpChildren[] = $child->forkChild();
		}
		$child->setPool($this);
		$child->setTmp($temporary);
		$child->register();
		$child->sendPing();
	}
	
	public function childrenWithOutConfirmation()
	{		
		$children = array();
		
		foreach ($this->children as $child) {			
			if(!$child->getConfirmed()) {				
				$children[] = $child;				
				$child->sendPing();
			}
		}
		
		if(count($this->tmpChildren) > 0) {			
			
			foreach ($this->tmpChildren as $tmpchild) {
				if(!$tmpchild->getConfirmed()) {					
					$children[] = $tmpchild;
					$tmpchild->sendPing();
				}
			}
		}		
		return $children;
	}
	
	public function getAvailableChild()
	{
		foreach ($this->children as $child) {			
			if($child->getAvailable()) {				
				return $child;
			}
		}
				
		foreach ($this->tmpChildren as $tmpchild) {			
			if($tmpchild->getAvailable()) {				
				return $tmpchild;
			}
		}
		
		if( (count($this->tmpChildren) < self::NUMBER_MAX_OF_TMP_CHILDREN) && (count($this->childrenWithOutConfirmation()) <= 0) ) {
			$this->newChild(true);
		}
		
		return NULL;
	}
	
	public function childDie(ChildHandler $child)
	{
		$this->registry->unregisterEvent('Child-'.$child->getPid());
		
		if(!$child->getTmp()) {
			$key = array_search($child, $this->children);
			unset($this->children[$key]);
		}
		else {
			$key = array_search($child, $this->tmpChildren);
			unset($this->tmpChildren[$key]);
		}
	}

	public function deadChildren()
	{
		$pid = pcntl_waitpid(-1, $status, WNOHANG);

		if ($pid > 0) {

			echo "Proceso Hijo {$pid} Termino con Estado {$status}\n";

		}
	}
}