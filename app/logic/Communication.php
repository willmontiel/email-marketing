<?php
class Communication
{
	protected $requester;
	
	public function __construct($log = null) {
		$context = new ZMQContext();

		$this->requester = new ZMQSocket($context, ZMQ::SOCKET_REQ);
		if ($log) {
			$log->log("Connecting to: [" . SocketConstants::getMailRequestsEndPointPeer() . "]");
		}
		$this->requester->connect(SocketConstants::getMailRequestsEndPointPeer());
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
	
	public function sendPlayToParent($idMail)
	{
		$mail = Mail::findFirstByIdMail($idMail);
		
		if(!$this->verifySentStatus($mail)) {

			$this->requester->send(sprintf("%s $idMail", 'Play-Task'));
			$response = $this->requester->recv(ZMQ::MODE_NOBLOCK);
		}
	}
	
	public function sendPausedToParent($idMail)
	{		
		$mail = Mail::findFirstByIdMail($idMail);

		if(!$this->verifySentStatus($mail)) {
			
			$this->requester->send(sprintf("%s $idMail", 'Stop-Process'));
			$response = $this->requester->recv(ZMQ::MODE_NOBLOCK);
		}
	}
	
	public function sendCancelToParent($idMail)
	{
		$mail = Mail::findFirstByIdMail($idMail);
		
		if(!$this->verifySentStatus($mail)) {
			if($mail->status == 'Sending') {
				$this->requester->send(sprintf("%s $idMail", 'Cancel-Process'));
				$response = $this->requester->recv(ZMQ::MODE_NOBLOCK);
				//No necesito cambiar el estado del Mail, porque el proceso dueño del Mail se hara cargo de esto
			}
			else if($mail->status == 'Scheduled') {
				$scheduled = Mailschedule::findFirstByIdMail($idMail);
				$scheduled->delete();
				$this->requester->send(sprintf("%s $idMail", 'Scheduled-Task'));
				$response = $this->requester->recv(ZMQ::MODE_NOBLOCK);
				
				//Debo cambiar explicitamente el estado del Mail, porque aun no hay un proceso manejando el envio
				$mail->status = 'Cancelled';

				if(!$mail->save()) {
					foreach ($mail->getMessages() as $msg) {
						$this->flashSession->error($msg);
					}
				}
			}
			else {
				$phql = "UPDATE Mxc SET status = 'canceled' WHERE idMail = " . $idMail;
				$mm = Phalcon\DI::getDefault()->get('modelsManager');
				$mm->executeQuery($phql);
				if (!$mm) {
					$log->log("Error updating MxC to Cancel");
				}
			}
		}
	}

	public function sendSchedulingToParent($idMail)
	{
		$this->requester->send(sprintf("%s $idMail", 'Scheduled-Task'));
		$response = $this->requester->recv(ZMQ::MODE_NOBLOCK);
	}
	
	protected function verifySentStatus(Mail $mail)
	{
		if($mail->status == 'Sent') {
			return TRUE;
		}
		
		return FALSE;
	}
}
