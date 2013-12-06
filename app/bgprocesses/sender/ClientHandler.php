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
		$events = array('New-Task', 'Scheduled-Task', 'Cancel-Process', 'Play-Process', 'Stop-Process', 'Are-You-There', 'Time-To-Die', 'Show-Status', 'Show-Status-Console', 'Checking-Work');
		
		return $events;
	}
	
	public function handleEvent(Event $event)
	{
		switch ($event->type) {
			case 'New-Task':
				$this->sendNewTask($event->data);
				$this->reply->send("New One..");
				break;
			case 'Scheduled-Task':
				$this->scheduling();
				$this->reply->send("Scheduling..");
				break;
			case 'Cancel-Process':
				$this->cancelTaskInProcess($event->data);
				$this->reply->send("Canceling..");
				break;
			case 'Play-Process':
				$this->playTask($event->data);
				$this->reply->send("Playing..");
				break;
			case 'Stop-Process':
				$this->stopTaskInProcess($event->data);
				$this->reply->send("Stoping..");
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
		$this->tasks->sendCommandToProcess('Cancel', $pid);
		
	}
	
	public function stopTaskInProcess($data)
	{
		$pid = $this->pool->findPidFromTask($data);
		$this->tasks->sendCommandToProcess('Stop', $pid);
	}
	
	public function playTask($data)
	{
		
	}
	
	public function responseToClient($content)
	{
		$this->reply->send($content);
	}
}