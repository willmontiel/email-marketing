<?php
class PoolHandler extends Handler
{	
	protected $children = array();
	protected $tmpChildren = array();
	protected $engagedProcesses = array();
	const MAX_OF_TMP_CHILDREN = 4;
	const INITIAL_CHILDREN = 4;

	protected $tasks;
	
	public function getEvents()
	{
		$events = array('Idle', 'WP', 'Process-Response');
		
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
			case 'Response-From-Child':
				printf('Pool ' .$event->code .PHP_EOL);
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
				//Mover Proceso al Final del Arreglo
				$key = array_search($child, $this->children);
				unset($this->children[$key]);
				$this->children[] = $child;
				
				return $child;
			}
		}
				
		foreach ($this->tmpChildren as $tmpchild) {			
			if($tmpchild->getAvailable()) {				
				return $tmpchild;
			}
		}
		
		if( (count($this->tmpChildren) < self::MAX_OF_TMP_CHILDREN) && (count($this->childrenWithOutConfirmation()) <= 0) ) {
			$this->newChild(true);
		}
		
		return NULL;
	}
	
	public function processGotTask(ChildHandler $process, $task)
	{
		$this->engagedProcesses[$process->getPid()] = $task;
	}
	
	public function processAvailable(ChildHandler $process)
	{
		unset($this->engagedProcesses[$process->getPid()]);
	}
	
	public function findPidFromTask($task)
	{
		if(count($this->engagedProcesses) > 0) {
			foreach ($this->engagedProcesses as $pid => $content){
				if($content == $task) {
					return $pid;
				}
			}
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