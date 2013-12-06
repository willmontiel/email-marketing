<?php
class ChildHandler extends Handler
{
	protected $pid;
	protected $confirmed;
	protected $available;
	protected $publisher;
	protected $pool;
	protected $tmp;
	protected $seq;

	public function __construct($registry)
	{
		parent::__construct($registry);
		
		$this->confirmed = FALSE;
		$this->available = FALSE;
		$this->tmp = FALSE;
		$this->seq = 0;
		
		$this->publisher = $this->di['publisher'];
	}
	
	public function getEvents()
	{
		$events = array('Child-'.$this->getPid());
		
		return $events;
	}
	
	public function handleEvent(Event $event)
	{
		switch ($event->code) {
			case 'Echo-Reply':
			case 'Echo-Tmp-Reply':
				$this->setConfirmed(TRUE);
				$this->setAvailable(TRUE);
				break;
			case 'Process-Available':
				$this->setAvailable(TRUE);
				$this->pool->processAvailable($this);
				break;
			case 'Kill-Process':
				$this->IDie();
				break;
			case 'Work-Checked':
				$this->pool->responseFromChild('Checked', $event->data);
				break;
		}
	}
	
	public function getPid() {
		return $this->pid;
	}
	
	public function getConfirmed() {
		return $this->confirmed;
	}

	public function setConfirmed($confirmed) {
		$this->confirmed = $confirmed;
	}
	
	public function getAvailable() {
		return $this->available;
	}

	public function setAvailable($available) {
		$this->available = $available;
	}
	
	public function setPool($pool) {
		$this->pool = $pool;
	}
	
	public function getTmp() {
		return $this->tmp;
	}

	public function setTmp($tmp) {
		$this->tmp = $tmp;
	}
	
	public function sendPing()
	{
		$this->seq++;
		
		if(!$this->tmp) {
			$send = sprintf("%d Echo-Request %d", $this->pid, $this->seq);
		}
		else {
			$send = sprintf("%d Echo-Tmp-Request %d", $this->pid, $this->seq);
		}
		$this->publisher->send($send);
	}
	
	public function askHowsMyWork()
	{
		$send = sprintf("%d Checking-Work", $this->pid);
		$this->publisher->send($send);
	}

	public function IDie()
	{
		$this->pool->childDie($this);
		unset($this);
	}
	
	public function sendKill()
	{
		$send = sprintf("%d Echo-Kill", $this->pid);
		$this->publisher->send($send);
	}

	public function forkChild()
	{
		$pid = pcntl_fork();
		
		if ($pid > 0) {
			
//			echo "---New Child Process {$pid}\n";

			$this->pid = $pid;
			
		}
		else if ($pid == 0) {
			
			pcntl_exec('/usr/bin/php', array('childSender.php'));
			
		}
		else {
			
			echo "Error! \n";
		}
		
		return $this;
	}
}