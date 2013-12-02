<?php
class ClientHandler extends Handler
{
	protected $pool;
	protected $tasks;
	
	public function getEvents()
	{
		$events = array('New-Task', 'Scheduled-Task', 'Cancel-Process', 'Play-Process', 'Stop-Process');
		
		return $events;
	}
	
	public function handleEvent(Event $event)
	{
		switch ($event->type) {
			case 'New-Task':
				$this->sendNewTask($event->data);
				break;
			case 'Scheduled-Task':
				$this->scheduling();
				break;
			case 'Cancel-Process':
				$this->cancelTaskInProcess($event->data);
				break;
			case 'Play-Process':
				$this->playTask($event->data);
				break;
			case 'Stop-Process':
				$this->stopTaskInProcess($event->data);
				break;
		}
	}
	
	public function setPool(PoolHandler $pool)
	{
		$this->pool = $pool;
	}
	
	public function setTasks(TasksHandler $tasks)
	{
		$this->tasks = $tasks;
	}
	
	protected function sendNewTask($data)
	{
		$process = $this->pool->getAvailableChild();
		
		if($process && !$this->tasks->checkReadyTasks()) {
			$this->tasks->sendTask($data, $process);	
		}
		else {
			$this->tasks->saveReadyTask($data);
		}
	}
	
	protected function scheduling()
	{
		$this->tasks->taskScheduling();
	}
	
	protected function cancelTaskInProcess($data)
	{
		$pid = $this->pool->findPidFromTask($data);
		$this->tasks->sendCommandToProcess('Cancel', $pid);
		
	}
	
	protected function stopTaskInProcess($data)
	{
		$pid = $this->pool->findPidFromTask($data);
		$this->tasks->sendCommandToProcess('Stop', $pid);
	}
	
	protected function playTask($data)
	{
		
	}
}