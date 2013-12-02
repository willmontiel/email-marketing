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
				$this->taskScheduling();
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
	
	public function sendTask($task, ChildHandler $process)
	{
		$send = sprintf("%d Processing-Task %s", $process->getPid(), $task);
		
		$this->publisher->send($send);
		
		$process->setAvailable(FALSE);
		
		$this->pool->processGotTask($process, $task);
	}
	
	public function taskScheduling()
	{
		$this->scheduledTasks = Mailschedule::find(array("limit" => 20, "order"  => "scheduleDate ASC"));
		
		if(count($this->scheduledTasks) > 0){
			$this->checkScheduledTasks();
		}
	}
	
	public function checkScheduledTasks()
	{
		foreach ($this->scheduledTasks as $task) {
			if($task->scheduleDate <= time()) {
				$this->saveReadyTask($task->idMail);
				$task->delete();
			}
		}
		$this->SendReadyTasks();
	}
	
	public function saveReadyTask($task)
	{
		$this->readyTasks[] = $task;
	}
	
	public function sendCommandToProcess($command, $pid)
	{
		$send = sprintf("%d %s %s", $pid, $command, $pid);

		$this->publisher->send($send);
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
