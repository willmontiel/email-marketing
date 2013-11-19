<?php
class ParentSender
{
	public function initializePool()
	{
		$context = new ZMQContext();
		$requester = new ZMQSocket($context, ZMQ::SOCKET_REQ); 
	}
	
	public function forkChild()
	{
		$pid = pcntl_fork();
		if ($pid > 0) {
			echo "Soy el parent... child process={$pid}\n";
		}
		elseif ($pid == 0) {
			echo "Soy el child!!!\n";
			echo "Mi PID es: " . getmypid() . PHP_EOL;
		}
		else {
			echo "Error! \n";
		}
	}
}

$x = new ParentSender();

$x->forkChild();