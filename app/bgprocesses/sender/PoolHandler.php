<?php
class PoolHandler extends Handler
{	
	protected $children = array();
	protected $tmpChildren = array();
	protected $engagedProcesses = array();
	protected $client;
	protected $tasks;
	
	public function setMaxOfTmpChildren($max)
	{
		$this->maxOfTmpChildren = $max;
	}
	
	public function setInitialChildren($initial)
	{
		$this->initialChildren = $initial;
	}
	
	public function setChildProcess($child)
	{
		$this->childProcess = $child;
	}
	
	public function getChildProcess()
	{
		return $this->childProcess;
	}
	
	public function getEvents()
	{
		$events = array('Idle', 'WP', 'Response-From-Child');
		
		return $events;
	}
	
	public function setTasks(TasksHandler $tasks)
	{
		$this->tasks = $tasks;
	}
	
	public function setClient(ClientHandler $client)
	{
		$this->client = $client;
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
		for ($i=0; $i < $this->initialChildren; $i++) {
			$this->newChild();
		}
	}
	public function newChild($temporary = false)
	{
		$child = new ChildHandler($this->registry);
		if (!$temporary) {
			$this->children[] = $child->forkChild(array($this->childProcess));
		}
		else {
			$this->tmpChildren[] = $child->forkChild(array($this->childProcess));
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
		
		if( (count($this->tmpChildren) < $this->maxOfTmpChildren) && (count($this->childrenWithOutConfirmation()) <= 0) ) {
			$this->newChild(true);
		}
		
		return null;
	}
	
	public function processGotTask(ChildHandler $process, Event $event)
	{
		$this->engagedProcesses[$process->getPid()] = $event;
	}
	
	public function processAvailable(ChildHandler $process)
	{
		unset($this->engagedProcesses[$process->getPid()]);
	}
	
	public function findPidFromTask(Event $event)
	{
		if(count($this->engagedProcesses) > 0) {
			foreach ($this->engagedProcesses as $pid => $content){
				if($content->code == $event->code) {
					return $pid;
				}
			}
		}
		return null;
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
		$log = $this->di['logger'];
		
		$pid = pcntl_waitpid(-1, $status, WNOHANG);

		if ($pid > 0) {
			
			foreach ($this->children as $key => $child) {			
				if($child->getPid() == $pid) {
					unset($this->children[$key]);
					$this->newChild();
				}
			}

			if(count($this->tmpChildren) > 0) {			

				foreach ($this->tmpChildren as $key => $tmpchild) {
					if($tmpchild->getPid() == $pid) {
						unset($this->tmpChildren[$key]);
					}
				}
			}

			if(count($this->engagedProcesses) > 0) {			

				foreach ($this->engagedProcesses as $process => $data) {
					if($process == $pid) {
						unset($this->engagedProcesses[$process]);
					}
				}
			}
		
			echo "Proceso Hijo {$pid} Termino con Estado {$status}\n";
			$log->log('Proceso Hijo ['. $pid . '] Termino con Estado [' . $status . ']' . PHP_EOL);
		}
	}
	
	public function killAllChildren()
	{
		foreach ($this->children as $child) {			
			$child->sendKill();
		}
		
		if(count($this->tmpChildren) > 0) {			
			
			foreach ($this->tmpChildren as $tmpchild) {
				$tmpchild->sendKill();
			}
		}
	}
	
	public function checkingChildWork(Event $event)
	{
		foreach ($this->children as $child) {
			if($child->getPid() == $event->data) {
				$child->askHowsMyWork();
			}
		}
		
		if(count($this->tmpChildren) > 0) {
			foreach ($this->tmpChildren as $tmpchild) {
				if($tmpchild->getPid() == $event->data) {
					$tmpchild->askHowsMyWork();
				}
			}
		}
	}
	
	public function responseFromChild($header, $content)
	{
		$send = sprintf("%s %s", $header, $content);
		$this->client->responseToClient($send);
	}

	public function childrenStatus()
	{
		$status = $Childconfirm = $Childnoconfirm = $Tmpconfirm = $Tmpnoconfirm = $Engstatus = '';
		
		foreach ($this->children as $child) {
			if(!$child->getConfirmed()) {
				$Childconfirm.= 'Process ' .$child->getPid(). ' not confirm' . PHP_EOL;
			}
			else {
				$Childnoconfirm.= 'Process ' .$child->getPid(). ' confirm' . PHP_EOL;
			}
		}
		if(count($this->tmpChildren) > 0) {			
			
			foreach ($this->tmpChildren as $tmpchild) {
				if(!$tmpchild->getConfirmed()) {
					$Tmpconfirm.= 'Temporary process ' .$tmpchild->getPid(). ' not confirm' . PHP_EOL;
				}
				else {
					$Tmpnoconfirm.= 'Temporary process ' .$tmpchild->getPid(). ' confirm' . PHP_EOL;
				}
			}
		}
		
		if(count($this->engagedProcesses) > 0) {			
			
			foreach ($this->engagedProcesses as $process => $event) {
				$Engstatus.= 'Process ' . $process . ' working data: ' . $event->data . PHP_EOL;
			}
		}
		
		$status = $Childconfirm . $Childnoconfirm . $Tmpconfirm . $Tmpnoconfirm . $Engstatus;
		
		return $status;
	}
	
	public function childrenStatusArray()
	{
		$status = array();
		
		foreach ($this->children as $child) {
			if(!$child->getConfirmed()) {
				$status[$child->getPid()]['Confirm'] = 'No';
			}
			else {
				$status[$child->getPid()]['Confirm'] = 'Yes';
			}
			$status[$child->getPid()]['Status'] = '---';
			$status[$child->getPid()]['Type'] = 'Permanent';
		}
		if(count($this->tmpChildren) > 0) {			
			
			foreach ($this->tmpChildren as $tmpchild) {
				if(!$tmpchild->getConfirmed()) {
					$status[$tmpchild->getPid()]['Confirm'] = 'No';
				}
				else {
					$status[$tmpchild->getPid()]['Confirm'] = 'Yes';
				}
				$status[$tmpchild->getPid()]['Status'] = '---';
				$status[$tmpchild->getPid()]['Type'] = 'Temporary';
			}
		}
		
		if(count($this->engagedProcesses) > 0) {			
			
			foreach ($this->engagedProcesses as $process => $event) {
				$status[$process]['Status'] = $event->data;
			}
		}
		
		return json_encode($status);
	}
}