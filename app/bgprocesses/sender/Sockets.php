<?php
class Sockets
{
	protected $reply;
	protected $pull;

	public function __construct() {
		
		$context = new ZMQContext(1);

		$this->reply = new ZMQSocket($context, ZMQ::SOCKET_REP);
		$this->reply->bind("tcp://*:5556");

		$this->pull = new ZMQSocket($context, ZMQ::SOCKET_PULL);
		$this->pull->bind("tcp://*:5557");
	}

	public function getReply() {
		return $this->reply;
	}

	public function getPull() {
		return $this->pull;
	}
}