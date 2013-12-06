<?php
class SendingprocessController extends ControllerBase
{
	public function getprocessesinfoAction()
	{
		$context = new ZMQContext();
		
		$requester = new ZMQSocket($context, ZMQ::SOCKET_REQ);
		$requester->connect("tcp://localhost:5556");
		
		$poll = new ZMQPoll();
		$poll->add($requester, ZMQ::POLL_IN);
		
		$requester->send(sprintf("%s", 'Show-Status'));
		
		$readable = $writeable = array();
		$events = $poll->poll($readable, $writeable, 1000);
		if ($events && count($readable) > 0) {
			$request = $requester->recv(ZMQ::MODE_NOBLOCK);
		
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
					$requester->send(sprintf("%s $key", 'Checking-Work'));
					$request = $requester->recv();
					sscanf($request, '%s %s', $header, $work);
					$obj->totalContacts = $mail->totalContacts;
					$obj->sentContacts = $work;
					$obj->pause = true;
				}
				$obj->task = $value->Status;
				$processesArray[] = $obj;
			}

			return $this->setJsonResponse($processesArray);
		}
		
		return NULL;
	}
	
	public function indexAction()
	{	
		
	}
	
	public function pauseAction($idTask)
	{
		$log = $this->logger;
	
		$context = new ZMQContext();
		
		$requester = new ZMQSocket($context, ZMQ::SOCKET_REQ);
		$requester->connect("tcp://localhost:5556");
		
		$requester->send(sprintf("%s $idTask", 'Stop-Process'));
		$request = $requester->recv();
		
		return $this->response->redirect('sendingprocess');
	}
}	
