<?php
class TasksHandler extends Handler
{
	protected $readyTasks;
	protected $scheduledTasks;
	protected $publisher;
	protected $pool;
	
	function __construct($registry) {
		parent::__construct($registry);
		
		$this->readyTasks = array();
		$this->scheduledTasks = array();
		$this->publisher = $this->di['publisher'];
	}
	
	public function getEvents()
	{
		$events = array('Idle');
		
		return $events;
	}
	
	public function setPool(PoolHandler $pool)
	{
		$this->pool = $pool;
	}

	public function handleEvent(Event $event)
	{
		switch ($event->type) {
			case 'Idle':
				if($this->checkReadyTasks()) {
					$this->SendReadyTasks();
				}
				break;
		}
	}

	public function SendReadyTasks()
	{
		while((count($this->readyTasks) > 0) && $this->pool->getAvailableChild()) {
			
			$process = $this->pool->getAvailableChild();

			$this->sendTask($this->readyTasks[0], $process);

			array_shift($this->readyTasks);
		}
	}
	
	public function sendTask($task, $process)
	{
		$send = sprintf("%d Process-Task %s", $process->getPid(), $task);
		
		$this->publisher->send($send);
		
		$process->setAvailable(FALSE);
	}
	
	public function taskScheduling($task, $schedule)
	{
		$this->scheduledTasks[] = array($schedule, $task);
	}
	
	public function checkScheduleTasks()
	{
		while(count($this->scheduledTasks) > 0)
		{
			foreach ($this->scheduledTasks as $task) {
				if($task[0] == time()) {
					$this->saveReadyTask($task[1]);
					
				}
			}
		}
	}
	
	public function saveReadyTask($task)
	{
		$this->readyTasks[] = $task;
	}
	
	public function checkReadyTasks()
	{
		if(count($this->readyTasks) > 0) {
			return TRUE;
		}
		else {
			return FALSE;
		}
	}
}