<?php
class Communication
{
	protected $requester;
	
	public function __construct($socket) {
		Phalcon\DI::getDefault()->get('logger')->log("Connecting to: [" . $socket . "]");

		$context = new ZMQContext();

		$this->requester = new ZMQSocket($context, ZMQ::SOCKET_REQ);
		$this->requester->connect($socket);
	}
	
	public function getStatus($type)
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
				$processesArray[] = $this->getStatusArray($key, $value, $type);
			}

			return $processesArray;
		}
		return NULL;
	}
	
	
	protected function getStatusArray($key, $value, $type)
	{
		$obj = new stdClass();
		$obj->pid = $key;
		$obj->type = $value->Type;
		$obj->confirm = $value->Confirm;
		$obj->task = $value->Status;
		if ($value->Status == '---') {
			$obj->status = 'Free';
			$obj->totalContacts = '---';
			$obj->sentContacts = '---';
			$obj->pause = false;
		}
		else{
			if($type === 'Mail') {
				$mail = Mail::findFirstByIdMail($value->Status);
				$this->requester->send(sprintf("%s $key", 'Checking-Work'));
				$request = $this->requester->recv();
				sscanf($request, '%s %s', $header, $work);
				$obj->totalContacts = $mail->totalContacts;
				$obj->sentContacts = $work;
			}
			else if($type === 'Import') {
				$importdetails = json_decode($value->Status);
				$obj->task = $importdetails->idImportproccess;
				$import = Importproccess::findFirstByIdImportproccess($importdetails->idImportproccess);
				$obj->totalContacts = $import->totalReg;
				$obj->sentContacts = $import->processLines;
			}
			$obj->status = 'Working';
			$obj->pause = true;
		}
		
		return $obj;
	}

	public function sendPlayToParent($idMail)
	{
		$mail = Mail::findFirstByIdMail($idMail);
		
		if(!$this->verifySentStatus($mail)) {

			$this->requester->send(sprintf("%s $idMail $idMail", 'Play-Task'));
			$response = $this->requester->recv(ZMQ::MODE_NOBLOCK);
		}
	}
	
	public function sendImportToParent($data, $code)
	{
		$this->requester->send(sprintf("%s $data $code", 'Play-Task'));
		$response = $this->requester->recv(ZMQ::MODE_NOBLOCK);
	}
	
	public function sendPausedToParent($idMail)
	{		
		$mail = Mail::findFirstByIdMail($idMail);

		if(!$this->verifySentStatus($mail)) {
			
			$this->requester->send(sprintf("%s $idMail $idMail", 'Stop-Process'));
			$response = $this->requester->recv(ZMQ::MODE_NOBLOCK);
			
			return true;
		}
		
		return false;
	}
	
	public function sendCancelToParent($idMail)
	{
		$log = Phalcon\DI::getDefault()->get('logger');
		$mail = Mail::findFirstByIdMail($idMail);
		
		if($mail && !$this->verifySentStatus($mail)) {
			if($mail->status == 'Sending') {
				$this->requester->send(sprintf("%s $idMail $idMail", 'Cancel-Process'));
				$response = $this->requester->recv(ZMQ::MODE_NOBLOCK);
				//No necesito cambiar el estado del Mail, porque el proceso dueño del Mail se hara cargo de esto
			}
			else {
				if($mail->status == 'Scheduled') {
					$scheduled = Mailschedule::findFirstByIdMail($idMail);
					if($scheduled) {
						$scheduled->delete();
					}
					$this->requester->send(sprintf("%s $idMail $idMail", 'Scheduled-Task'));
					$response = $this->requester->recv(ZMQ::MODE_NOBLOCK);
				}else {
					$sql = "UPDATE mxc SET status = 'canceled' WHERE idMail = {$idMail}";
					$db = Phalcon\DI::getDefault()->get('db');
					$query = $db->query($sql);
					$result = $query->execute();
					if (!$result) {
						$log->log("Error updating MxC to Cancel");
					}
				}
				//Debo cambiar explicitamente el estado del Mail, porque aun no hay un proceso manejando el envio
				$mail->status = 'Cancelled';

				if(!$mail->save()) {
					foreach ($mail->getMessages() as $msg) {
						$log->log($msg);
					}
				}
			}
			return true;
		}
		else {
			return false;
		}
	}

	public function sendSchedulingToParent($idMail)
	{
		$this->requester->send(sprintf("%s $idMail $idMail", 'Scheduled-Task'));
		$response = $this->requester->recv(ZMQ::MODE_NOBLOCK);
	}
	
	protected function verifySentStatus(Mail $mail)
	{
		if($mail->status == 'Sent') {
			return TRUE;
		}
		return FALSE;
	}
	
	public function sendPausedImportToParent($idImport)
	{		
		$import = Importproccess::findFirstByIdImportproccess($idImport);

		if($import->totalReg != $import->processLines) {
			
			$this->requester->send(sprintf("%s $idImport $idImport", 'Stop-Process'));
			$response = $this->requester->recv(ZMQ::MODE_NOBLOCK);
		}
	}
}
