<?php

class MailApiWrapper extends BaseWrapper
{
	function __construct($logger, $modelsManager)
	{
		$this->logger = $logger;
		$this->modelsManager = $modelsManager;
	}
	
	public function createTarget($target)
	{
		try{
			switch ($target->type) {
				case 'dbases':
					$query = $this->modelsManager->createQuery("SELECT name FROM Dbase WHERE idDbase IN (" . implode(',',$target->ids) . ") AND idAccount = " . $this->account->idAccount);
					$result = $query->execute();
					break;

				case 'contactlists':
					$query = $this->modelsManager->createQuery("SELECT Contactlist.name FROM Contactlist JOIN Dbase ON (Contactlist.idDbase = Dbase.idDbase) WHERE Contactlist.idContactlist IN (" . implode(',',$target->ids) . ") AND Dbase.idAccount = " . $this->account->idAccount);
					$result = $query->execute();
					break;

				case 'segments':
					$query = $this->modelsManager->createQuery("SELECT Segment.name FROM Segment JOIN Dbase ON (Segment.idDbase = Dbase.idDbase) WHERE Segment.idSegment IN (" . implode(',',$target->ids) . ") AND Dbase.idAccount = " . $this->account->idAccount);
					$result = $query->execute();
					break;
				default :
					throw new InvalidArgumentException('Type of target undefined');
					break;
			}
			
			if(count($result) <= 0) {
				throw new InvalidArgumentException('Target undefined');
			}

			$names = array();

			foreach ($result as $r) {
				$names[] = $r->name; 
			}
			
			$obj = array(
						array(
							"type" => "top-panel",
							"serialization" => array("criteria" => $target->type),
							"totalContacts" => "0"
						),
						array(
							"type" => "list-panel",
							"serialization" => array("items" => $target->ids, "names" => $names, "conditions" => "all")
						)
					);

			return json_encode($obj);
		} catch (\InvalidArgumentException $e) {
			throw new InvalidArgumentException($e->getMessage());
		} catch (\Exception $e) {
			$this->logger->log("Exception: {$e}");
			throw new InvalidArgumentException('wrong target');
		}
	}
	
	public function getContent($contentobj)
	{
		switch ($contentobj->type) {
			case 'url':
				if(!filter_var($contentobj->content, FILTER_VALIDATE_URL)) {
					throw new InvalidArgumentException('wrong URL');
				}
				try {
					$getHtml = new LoadHtml();
					$content = $getHtml->gethtml($contentobj->content, false, false, $this->account, true);
				}
				catch(Exception $e) {
					throw new Exception($e->getMessage());
				}
				break;
			default :
				throw new InvalidArgumentException('wrong type of content');
				break;
		}
				
		return $content;
	}
	
	public function validateContent($content)
	{
		if(!isset($content->name) || empty($content->name)) {
			throw new InvalidArgumentException('wrong name');
		}
		if(!isset($content->subject) || empty($content->subject)) {
			throw new InvalidArgumentException('wrong subject');
		}
		if(!isset($content->replyTo) || empty($content->replyTo)) {
			throw new InvalidArgumentException('wrong replyTo');
		}
		if(!isset($content->scheduleDate) || empty($content->scheduleDate)) {
			throw new InvalidArgumentException('wrong scheduleDate');
		}
		if(!isset($content->sender) || empty($content->sender)) {
			throw new InvalidArgumentException('wrong sender');
		}
		else {
			$parts = explode('/', $content->sender);
			if(!((isset($parts[0]) && !empty($parts[0])) && (\filter_var(trim(strtolower($parts[0])), FILTER_VALIDATE_EMAIL)) && (isset($parts[1]) && !empty($parts[1])))) {
				throw new InvalidArgumentException('wrong sender');
			}
		}
	}
	
	public function response_new_mail($mail)
	{
		$obj = array(
						"mail" => array(
											"idMail" => $mail->idMail,
											"status" => $mail->status,
											"name" => $mail->name,
											"subject" => $mail->subject,
											"sender" => $mail->fromEmail . "/" . $mail->fromName,
											"replyTo" => $mail->replyTo
										),
						"status" => "ok"
					);
		return $obj;
	}
	
	public function send_mail_to_process($mail)
	{
		$schedule = Mailschedule::findFirstByIdMail($mail->idMail);

		if($schedule) {
			$mail->status = ($mail->status == 'Draft' || $mail->status == 'draft') ? 'Scheduled' : $mail->status;
			
			if(!$mail->save()) {
				foreach ($mail->getMessages() as $msg) {
					$this->logger->log($msg);
				}
				throw new Exception('Error saving mail in auto responder');
			}

			$schedule->confirmationStatus = 'Yes';

			if(!$schedule->save()){
				foreach ($schedule->getMessages() as $msg) {
					$this->logger->log($msg);
				}
				throw new Exception('Error saving scheduling in auto responder');
			}

			$commObj = new Communication(SocketConstants::getMailRequestsEndPointPeer());
			$commObj->sendSchedulingToParent($mail->idMail);	
		}
	}
}
