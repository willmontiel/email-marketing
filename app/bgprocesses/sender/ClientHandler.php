<?php
class ClientHandler extends Handler
{
	protected $pool;
	protected $tasks;
	
	public function getEvents()
	{
		$events = array('NewTask');
		
		return $events;
	}
	
	public function handleEvent(Event $event)
	{
		switch ($event->type) {
			case 'NewTask':
				$this->sendNewTask($event->data);
				break;
			case 'ScheduledTask':
				$this->scheduling($event->data);
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
	
	protected function scheduling($data)
	{
		$task = $data->content;
		$schedule = $data->schedule;
		
		$this->tasks->taskScheduling($task, $schedule);
	}
}