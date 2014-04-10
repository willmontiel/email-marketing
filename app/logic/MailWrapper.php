<?php

class MailWrapper
{
	protected $account;
	protected $content;
	protected $mail;
	protected $target = null;
	
	public function __construct() 
	{
		$this->logger = Phalcon\DI::getDefault()->get('logger');
	}

	public function setAccount(Account $account)
	{
		$this->account = $account;
	}
	
	public function setContent($content)
	{
		$this->content = $content;
	}
	
	public function setMail(Mail $mail)
	{
		$this->mail = $mail;
	}
	
	public function processData()
	{
		$this->getTarget();
	}

	public function getTarget()
	{
		$idsDbase = $this->content->dbases;
		$idsContactlist = $this->content->contactlists;
		$idsSegment = $this->content->segments;
		
		$byEmail = $this->content->filterByEmail;
		$byOpen = $this->content->filterByOpen;
		$byClick = $this->content->filterByClick;
		$byExclude = $this->content->filterByExclude;
				
		if (!empty($idsDbase) || !empty($idsContactlist) || !empty($idsSegment)) {
			try {
				$target = new TargetObj();
				$target->setIdsDbase($idsDbase);
				$target->setIdsContactlist($idsContactlist);
				$target->setIdsSegment($idsSegment);
				$target->setFilters($byEmail, $byOpen, $byClick, $byExclude);
				$target->createTargetObj();
				$this->target = $target->getTargetObject();
				
				$this->logger->log("Target: " . print_r($this->target, true));
			}
			catch (InvalidArgumentException $e) {
				$this->logger->log('Exception: [' . $e . ']');
			}
		}
	}
	
	public function saveMail()
	{
		$date = time();
		
//		if ($this->target == false) {
//			$message = 'No hay contactos registrados, por favor seleccione otra base de datos, lista o segmento';
//			
//			$response = new stdClass();
//			$response->key = 'errors';
//			$response->data = $message;
//			$response->code = 400;
//			
//			return $response;
//		}
		
		$mail = new Mail();
			
		$mail->idAccount = $this->account->idAccount;
		$mail->type = $this->content->type;
		$mail->status = 'draft';
		$mail->wizardOption = 'setup';
		$mail->totalContacts = $this->target->totalContacts;
		$mail->scheduleDate = $this->content->scheduleDate;
		$mail->createdon = $date;
		$mail->updatedon = $date;
		$mail->deleted = 0;
		$mail->name = $this->content->name;
		$mail->subject = $this->content->subject;
		$mail->fromName = $this->content->fromName;
		$mail->fromEmail = $this->content->fromEmail;
		$mail->replyTo = $this->content->replyTo;
		$mail->target = $this->target->target;
		$mail->previewData = $this->content->previewData;
		$mail->socialNetworks = $this->content->socialNetworks;

		if (!$mail->save()) {
			$e = array();
			foreach ($mail->getMessages() as $msg) {
				$this->logger->log("Error while saving mail: {$msg}");
				$e[] = $msg;
			}
			$messages = implode(", ", $e);
			
			$response = new stdClass();
			$response->key = 'errors';
			$response->data = $messages;
			$response->code = 400;
			return $response;
		}
		
		$response = new stdClass();
		$response->key = 'mails';
		$response->data = $this->convertMailToJson($mail);
		$response->code = 200;

		return $response;
	}
	
	public function updateMail()
	{
		$date = time();
		
//		if ($this->target == false) {
//			$message = 'No hay contactos registrados, por favor seleccione otra base de datos, lista o segmento';
//			
//			$response = new stdClass();
//			$response->key = 'errors';
//			$response->data = $message;
//			$response->code = 400;
//			
//			return $response;
//		}
		
		$this->mail->type = $this->content->type;
		$this->mail->status = 'draft';
		$this->mail->wizardOption = 'setup';
		$this->mail->totalContacts = $this->target->totalContacts;
		$this->mail->scheduleDate = $this->content->scheduleDate;
		$this->mail->updatedon = $date;
		$this->mail->name = $this->content->name;
		$this->mail->subject = $this->content->subject;
		$this->mail->fromName = $this->content->fromName;
		$this->mail->fromEmail = $this->content->fromEmail;
		$this->mail->replyTo = $this->content->replyTo;
		$this->mail->target = $this->target->target;
		$this->mail->previewData = $this->content->previewData;
		$this->mail->socialNetworks = $this->content->socialNetworks;

		if (!$this->mail->save()) {
			$e = array();
			foreach ($this->mail->getMessages() as $msg) {
				$this->logger->log("Error while saving mail: {$msg}");
				$e[] = $msg;
			}
			$messages = implode(", ", $e);
			
			$response = new stdClass();
			$response->key = 'errors';
			$response->data = $messages;
			$response->code = 400;
			return $response;
		}
		
		$response = new stdClass();
		$response->key = 'mails';
		$response->data = $this->convertMailToJson($this->mail);
		$response->code = 200;

		return $response;
	}
	
	private function convertMailToJson($mail)
	{
		$jsonObject = array();
		//Header
		$jsonObject['id'] = $mail->idMail;
		$jsonObject['idAccount'] = $mail->idAccount;
		$jsonObject['status'] = $mail->status;
		$jsonObject['wizardOption'] = $mail->wizardOption;
		$jsonObject['createdon'] = $mail->createdon;
		$jsonObject['updatedon'] = $mail->updatedon;
		$jsonObject['deleted'] = $mail->deleted;
		$jsonObject['name'] = $mail->name;
		$jsonObject['subject'] = $mail->subject;
		$jsonObject['fromName'] = $mail->fromName;
		$jsonObject['fromEmail'] = $mail->fromEmail;
		$jsonObject['replyTo'] = $mail->replyTo;
		
		//
		$jsonObject['clicks'] = $mail->clicks;
		$jsonObject['bounced'] = $mail->bounced;
		$jsonObject['spam'] = $mail->spam;
		$jsonObject['unsubscribed'] = $mail->unsubscribed;
		
		
		$jsonObject['target'] = $mail->target;
		$jsonObject['previewData'] = $mail->previewData;
		$jsonObject['socialnetworks'] = $mail->socialnetworks;
		$jsonObject['totalContacts'] = $mail->totalContacts;
		$jsonObject['scheduleDate'] = $mail->scheduleDate;
		$jsonObject['finishedon'] = $mail->finishedon;
		$jsonObject['uniqueOpens'] = $mail->uniqueOpens;
		$jsonObject['startedon'] = $mail->startedon;

		return $jsonObject;
	}
}