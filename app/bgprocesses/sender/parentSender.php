<?php
//require_once '../bootstrap/phbootstrap.php';

class ParentSender
{
	protected $pids;

	public function __construct()
	{
		$this->pids = array();
	}
	
	public function initializePool()
	{
		
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

for ($i=0; $i<4; $i++) {
	$x->forkChild();
} 

$context = new ZMQContext(1);
//  Socket to talk to clients
$responder = new ZMQSocket($context, ZMQ::SOCKET_REP);
$responder->bind("tcp://*:5556");

while (true) {
	$request = $responder->recv();
	printf("$request \n");
	
	switch ($request) {
		case 'free':
			$responder->send("doSomething");
			break;
	}
}
