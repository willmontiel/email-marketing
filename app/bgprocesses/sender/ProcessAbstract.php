<?php
abstract class ProcessAbstract  {
	
	public function createHandlers($registry) {
		$this->client = new ClientHandler($registry);
		$this->pool = new PoolHandler($registry);
		$this->tasks = new TasksHandler($registry);
		$this->client->register();
		$this->pool->register();
		$this->tasks->register();
		
		$this->tasks->setPool($this->pool);
		$this->client->setTasks($this->tasks);
		$this->client->setPool($this->pool);
		$this->pool->setClient($this->client);
	}
	
	abstract public function getPublisherToChildrenSocket();
	abstract public function getReplyToClientSocket();
	abstract public function getPullFromChildSocket();
	abstract public function setPoolConditions();
}

?>
