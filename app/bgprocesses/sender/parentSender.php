<?php
class ParentSender
{
	protected $pids;

	public function __construct()
	{
		$this->pids = array();
	}
	public function initializePool()
	{
		$context = new ZMQContext();
		$requester = new ZMQSocket($context, ZMQ::SOCKET_REQ); 
	}
	
	public function forkChild()
	{
		$pid = pcntl_fork();
		if ($pid > 0) {
			echo "New child process={$pid}\n";
			$this->pids[] = $pid;
		}
		elseif ($pid == 0) {
			pcntl_exec('/usr/bin/php', array('childSender.php'));
		}
		else {
			echo "Error! \n";
		}
	}

	public function waitForAll()
	{
		sleep(10);
	}
}

$x = new ParentSender();

for ($i=0; $i<10; $i++) {
	$x->forkChild();
}

$x->waitForAll();