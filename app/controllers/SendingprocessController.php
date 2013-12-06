<?php
class SendingprocessController extends ControllerBase
{
	public function getprocessesinfoAction()
	{
		$context = new ZMQContext();
		
		$requester = new ZMQSocket($context, ZMQ::SOCKET_REQ);
		$requester->connect("tcp://localhost:5556");

		$requester->send(sprintf("%s", 'Show-Status'));
		$request = $requester->recv();
		
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
			}
			else {
				$obj->status = 'Working';
				$mail = Mail::findFirstByIdMail($value->Status);
				$obj->totalContacts = $mail->totalContacts;
				$obj->sentContacts = '---';
			}
			$obj->task = $value->Status;
			$processesArray[] = $obj;
		}
					
		return $this->setJsonResponse($processesArray);
	}
	
	public function indexAction()
	{	
		
	}
}	
