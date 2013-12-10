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
				$this->sendNewTask($event->data);
				$this->reply->send("Playing One..");
				break;
			case 'Scheduled-Task':
				$this->scheduling();
				$this->reply->send("Scheduling..");
				break;
			case 'Cancel-Process':
				$this->cancelTaskInProcess($event->data);
				$this->reply->send("Canceling..");
				break;
			case 'Stop-Process':
				$this->stopTaskInProcess($event->data);
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
				$this->pool->checkingChildWork($event->data);
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
	
	public function sendNewTask($data)
	{
		$process = $this->pool->getAvailableChild();
		
		if($process && !$this->tasks->checkReadyTasks()) {
			$this->tasks->sendTask($data, $process);	
		}
		else {
			$this->tasks->saveReadyTask($data);
		}
	}
	
	public function scheduling()
	{
		$this->tasks->taskScheduling();
	}
	
	public function cancelTaskInProcess($data)
	{
		$pid = $this->pool->findPidFromTask($data);
		if($pid === NULL) {
			$this->tasks->removeSaveTask($data);
		}
		else {
			$this->tasks->sendCommandToProcess('Cancel', $pid);
		}
		
	}
	
	public function stopTaskInProcess($data)
	{
		$pid = $this->pool->findPidFromTask($data);
		if($pid === NULL) {
			$this->tasks->removeSaveTask($data);
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