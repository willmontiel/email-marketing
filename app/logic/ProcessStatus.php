<?php
class ProcessStatus
{
	public $requester;
	public function __construct() 
	{
		$context = new ZMQContext();
		
		$this->requester = new ZMQSocket($context, ZMQ::SOCKET_REQ);
		$this->requester->connect("tcp://localhost:5556");
	}
	
	public function getStatus()
	{
		$poll = new ZMQPoll();
		$poll->add($this->requester, ZMQ::POLL_IN);
		
		$this->requester->send(sprintf("%s", 'Show-Status'));
		
		$readable = $writeable = array();
		$events = $poll->poll($readable, $writeable, 1000);
		
		if ($events && count($readable) > 0) {
			$request = $this->requester->recv(ZMQ::MODE_NOBLOCK);
		
			$status = json_decode($request);

			$processesArray = array();
			foreach ($status as $key => $value) {
				$obj = new stdClass();
				$obj->pid = $key;
				$obj->type = $value->Type;
				$obj->confirm = $value->Confirm;
				if ($value->Status == '---') {
					$obj->status = 'Free';
					$obj->totalContacts = '---';
					$obj->sentContacts = '---';
					$obj->pause = false;
				}
				else {
					$obj->status = 'Working';
					$mail = Mail::findFirstByIdMail($value->Status);
					$this->requester->send(sprintf("%s $key", 'Checking-Work'));
					$request = $this->requester->recv();
					sscanf($request, '%s %s', $header, $work);
					$obj->totalContacts = $mail->totalContacts;
					$obj->sentContacts = $work;
					$obj->pause = true;
				}
				$obj->task = $value->Status;
				$processesArray[] = $obj;
			}

			return $processesArray;
		}
		return NULL;
	}
	
	public function pauseTask($idTask)
	{
		$this->requester->send(sprintf("%s $idTask", 'Stop-Process'));
		$request = $this->requester->recv(ZMQ::MODE_NOBLOCK);
	}
}