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
		$this->requester->send(sprintf("%s $idMail", 'Play-Task'));
		$response = $this->requester->recv(ZMQ::MODE_NOBLOCK);
	}
	
	public function sendPausedToParent($idMail)
	{
		$this->requester->send(sprintf("%s $idMail", 'Stop-Process'));
		$response = $this->requester->recv(ZMQ::MODE_NOBLOCK);
	}
	
	public function sendCancelToParent($idMail)
	{
		$this->requester->send(sprintf("%s $idMail", 'Cancel-Process'));
		$response = $this->requester->recv(ZMQ::MODE_NOBLOCK);
	}

	public function sendSchedulingToParent($idMail)
	{
		$this->requester->send(sprintf("%s $idMail", 'Scheduled-Task'));
		$response = $this->requester->recv(ZMQ::MODE_NOBLOCK);
	}
}