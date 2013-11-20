<?php
//require_once '../bootstrap/phbootstrap.php';

class ChildSender
{
	public function initialize()
	{
		$pid = getmypid();
		$msg = "free";
		
		$context = new ZMQContext();
		//  Socket to talk to clients
		$responder = new ZMQSocket($context, ZMQ::SOCKET_REQ);
		$responder->connect("tcp://localhost:5556");
		$responder->send("$msg");
		
		$request = $responder->recv();
		
		switch ($request) {
			case 'doSomething':
				$this->doSomething();
				break;
		}
		
	}
	
	public function doSomething()
	{
		printf("I'm on it");
	}
}

$x = new ChildSender();

$x->initialize();