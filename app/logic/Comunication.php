<?php
class Comunication
{
	protected $requester;
	
	public function __construct() {
		$context = new ZMQContext();
		
		$this->requester = new ZMQSocket($context, ZMQ::SOCKET_REQ);
		$this->requester->connect("tcp://localhost:5556");
	}

	public function sendToParent($idMail)
	{
		printf('Inicia proceso' .PHP_EOL);
		$this->requester->send(sprintf("%s $idMail", 'New-Task'));
		$response = $this->requester->recv();
	}
}