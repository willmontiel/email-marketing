<?php
class ClientHandler extends Handler
{
	protected $pool;
	protected $tasks;
	protected $reply;
			
	function __construct($registry) {
		parent::__construct($registry);
		
		$this->reply = $this->di['reply'];
	}
	
	public function getEvents()
	{
		$events = array('Play-Task', 'Scheduled-Task', 'Cancel-Process', 'Stop-Process', 'Are-You-There', 'Time-To-Die', 'Show-Status', 'Show-Status-Console', 'Checking-Work');
		
		return $events;
	}
	
	public function handleEvent(Event $event)
	{
		switch ($event->type) {
			case 'Play-Task':
				$this->sendNewTask($event);
				$this->reply->send("Playing One..");
				break;
			case 'Scheduled-Task':
				$this->scheduling();
				$this->reply->send("Scheduling..");
				break;
			case 'Cancel-Process':
				$this->cancelTaskInProcess($event);
				$this->reply->send("Canceling..");
				break;
			case 'Stop-Process':
				$this->stopTaskInProcess($event);
				$this->reply->send("Stopping..");
				break;
			case 'Are-You-There':
				$this->reply->send("I'm Here");
				break;
			case 'Time-To-Die':
				$this->pool->killAllChildren();
				$this->reply->send("I'm Dead");
				exit(0);
				break;
			case 'Show-Status':
				$poolStatus = $this->pool->childrenStatusArray();
				$this->reply->send($poolStatus);
				break;
			case 'Show-Status-Console':
				$poolStatus = $this->pool->childrenStatus();
				$poolStatus = 'Parent Working' . PHP_EOL . $poolStatus;
				$this->reply->send($poolStatus);
				break;
			case 'Checking-Work':
				$this->pool->checkingChildWork($event);
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
	
	public function sendNewTask(Event $event)
	{
		$process = $this->pool->getAvailableChild();
		
		if($process && !$this->tasks->checkReadyTasks()) {
			$this->tasks->sendTask($event, $process);	
		}
		else {
			$this->tasks->saveReadyTask($event);
		}
	}
	
	public function scheduling()
	{
		$this->tasks->taskScheduling();
	}
	
	public function cancelTaskInProcess(Event $event)
	{
		$pid = $this->pool->findPidFromTask($event);
		if($pid === NULL) {
			$this->tasks->removeSaveTask($event);
		}
		else {
			$this->tasks->sendCommandToProcess('Cancel', $pid);
		}
		
	}
	
	public function stopTaskInProcess(Event $event)
	{
		$pid = $this->pool->findPidFromTask($event);
		if($pid === NULL) {
			$this->tasks->removeSaveTask($event);
		}
		else {
			$this->tasks->sendCommandToProcess('Stop', $pid);
		}
	}

	public function responseToClient($content)
	{
		$this->reply->send($content);
	}
}