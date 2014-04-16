<?php

class MailWrapper extends BaseWrapper
{
	protected $account;
	protected $content;
	protected $mail = null;
	protected $mailcontent = null;
	protected $target = null;
	protected $scheduleDate;


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
	
	public function setMail(Mail $mail = null)
	{
		$this->mail = $mail;
	}
	
	public function setMailContent(Mailcontent $mailcontent)
	{
		$this->mailcontent = $mailcontent;
	}
	
	public function processDataForMail()
	{
		$this->processTarget();
		$this->processScheduleDate();
	}
	
	public function processDataForMailContent()
	{
		$this->processMailContent();
	}
	
	public function processTarget()
	{
		$idsDbase = $this->content->dbases;
		$idsContactlist = $this->content->contactlists;
		$idsSegment = $this->content->segments;
		
		$byEmail = $this->content->filterByEmail;
		$byOpen = $this->content->filterByOpen;
		$byClick = $this->content->filterByClick;
		$byExclude = $this->content->filterByExclude;
				
		if (!empty($idsDbase) || !empty($idsContactlist) || !empty($idsSegment)) {
			$target = new TargetObj();
			$target->setIdsDbase($idsDbase);
			$target->setIdsContactlist($idsContactlist);
			$target->setIdsSegment($idsSegment);
			$target->setFilters($byEmail, $byOpen, $byClick, $byExclude);
			$target->createTargetObj();
			$this->target = $target->getTargetObject();

			$this->logger->log("Target: " . print_r($this->target, true));
		}
	}
	
	protected function processMailContent()
	{
		if ($this->content->content != '') {
			switch ($this->content->type) {
				case 'Editor':
					if ($this->content->plainText == '') {
						$editorObj = new HtmlObj;
						$editorObj->assignContent(json_decode($this->content->content));
						$content = $editorObj->render();
						
						$text = new PlainText();
						$this->content->plainText = $text->getPlainText($content);
					}
					break;
				
				case 'Html':
					$text = new PlainText();
					$this->content->plainText = $text->getPlainText($this->content->content);
					break;
			}
		}
	}


	protected function processScheduleDate()
	{
		$schedule = $this->content->scheduleDate;
		if ($schedule == 'now') {
			$this->scheduleDate = time();
		}
		else if ($schedule !== '' || !empty($schedule)) {
			list($month, $day, $year, $hour, $minute) = preg_split('/[\s\/|-|:]+/', $schedule);
			$this->scheduleDate = mktime($hour, $minute, 0, $month, $day, $year);
		}
	}
	
	
	public function saveMail()
	{
		$date = time();
		
//		if ($this->target == false) {
//			$message = 'No hay contactos registrados, por favor seleccione otra base de datos, lista o segmento';
//			$this->addMessageError('errors', $message, 400);
//			throw new \InvalidArgumentException($message);
//		}
		
		if ($this->mail == null) {
			$this->mail = new Mail();
		}
		
		$this->mail->idAccount = $this->account->idAccount;
		$this->mail->type = $this->content->type;
		$this->mail->status = 'draft';
		$this->mail->wizardOption = 'setup';
		$this->mail->totalContacts = $this->target->totalContacts;
		$this->mail->scheduleDate = $this->scheduleDate;
		$this->mail->createdon = $date;
		$this->mail->updatedon = $date;
		$this->mail->deleted = 0;
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
				$e[] = $msg;
			}
			$messages = implode(", ", $e);
			
			$this->addMessageError('errors', $messages, 400);
			throw new \InvalidArgumentException($messages);
		}
	}
	
	public function saveContent()
	{
		$this->mailcontent = new Mailcontent();
		
		$this->mailcontent->idMail = $this->mail->idMail;
		$this->mailcontent->content = $this->content->content;
		$this->mailcontent->plainText = $this->content->plainText;
		$this->mailcontent->googleAnalytics = $this->content->googleAnalytics;
		$this->mailcontent->campaignName = $this->content->campaignName;
		
		if (!$this->mailcontent->save()) {
			$e = array();
			foreach ($this->mailcontent->getMessages() as $msg) {
				$e[] = $msg;
			}
			$messages = implode(", ", $e);
			$this->addMessageError('errors', $messages, 400);
			throw new \InvalidArgumentException($messages);
		}
	}
	
	public function convertMailToJson()
	{
		$this->mail; $this->content;
		$jsonObject = array();
		//Header
		$jsonObject['id'] = $this->mail->idMail;                  
//		$jsonObject['idAccount'] = $this->mail->idAccount;
//		$jsonObject['status'] = $this->mail->status;
//		$jsonObject['wizardOption'] = $this->mail->wizardOption;
//		$jsonObject['createdon'] = $this->mail->createdon;
//		$jsonObject['updatedon'] = $this->mail->updatedon;
//		$jsonObject['deleted'] = $this->mail->deleted;
		$jsonObject['name'] = $this->mail->name;
		$jsonObject['subject'] = $this->mail->subject;
		$jsonObject['fromName'] = $this->mail->fromName;
		$jsonObject['fromEmail'] = $this->mail->fromEmail;
		$jsonObject['replyTo'] = $this->mail->replyTo;
		
		$jsonObject['dbases'] = $this->content->dbases;
		$jsonObject['contactlists'] = $this->content->contactlists;
		$jsonObject['segments'] = $this->content->segments;
		$jsonObject['filterByEmail'] = $this->content->filterByEmail;
		$jsonObject['filterByOpen'] = $this->content->filterByOpen;
		$jsonObject['filterByClick'] = $this->content->filterByClick;
		$jsonObject['filterByExclude'] = $this->content->filterByExclude;
		
		$jsonObject['content'] = $this->mailcontent->content;
		$jsonObject['plainText'] = $this->mailcontent->plainText;
		
		$jsonObject['scheduleDate'] = $this->mailcontent->scheduleDate;
		
//		$jsonObject['clicks'] = $this->mail->clicks;
//		$jsonObject['bounced'] = $this->mail->bounced;
//		$jsonObject['spam'] = $this->mail->spam;
//		$jsonObject['unsubscribed'] = $this->mail->unsubscribed;
		
//		$jsonObject['target'] = $this->mail->target;
//		$jsonObject['previewData'] = $this->mail->previewData;
//		$jsonObject['socialnetworks'] = $this->mail->socialnetworks;
//		$jsonObject['totalContacts'] = $this->mail->totalContacts;
//		$jsonObject['scheduleDate'] = $this->mail->scheduleDate;
//		$jsonObject['finishedon'] = $this->mail->finishedon;
//		$jsonObject['uniqueOpens'] = $this->mail->uniqueOpens;
//		$jsonObject['startedon'] = $this->mail->startedon;

		return $jsonObject;
	}
	
	public function getResponse() 
	{
		$response = new stdClass();
		$response->key = 'mails';
		$response->data = $this->convertMailToJson();
		$response->code = 200;

		return $response;
	}
}