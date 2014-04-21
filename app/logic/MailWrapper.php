<?php

class MailWrapper extends BaseWrapper
{
	protected $account;
	protected $content;
	protected $mail = null;
	protected $mailcontent = null;
	protected $target = null;
	protected $scheduleDate = null;


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

	public function setMailContent($mailcontent = null)
	{
		$this->mailcontent = $mailcontent;
	}
	
	public function processDataForMail()
	{
		$this->processTarget();
		$this->processScheduleDate();
		$this->saveContent();
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
	
	private function saveContent()
	{
		if ($this->mailcontent != null) {
			$this->mailcontent->plainText = $this->content->plainText;
//			$this->mailcontent->googleAnalytics = $this->content->googleAnalytics;
//			$this->mailcontent->campaignName = $this->content->campaignName;

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
	}
	
	public function convertMailToJson()
	{
		$this->mail; $this->content;
		$jsonObject = array();
		
		$jsonObject['id'] = $this->mail->idMail;      
		$jsonObject['name'] = $this->mail->name;
		$jsonObject['subject'] = $this->mail->subject;
		$jsonObject['fromName'] = $this->mail->fromName;
		$jsonObject['fromEmail'] = $this->mail->fromEmail;
		$jsonObject['replyTo'] = $this->mail->replyTo;
		$jsonObject['type'] = $this->mail->type;
		$jsonObject['dbases'] = '';
		$jsonObject['contactlists'] = '';
		$jsonObject['segments'] = '';
		
		$filter = null;
		if ($this->mail->target != null) {
			$target = json_decode($this->mail->target);
			$filter = $target->filter;
			
			$this->logger->log('Filter: ' . print_r($filter, true));
			$type = $filter->type;
			
			$criteria = $filter->criteria;
			$ids = implode(',', $target->ids);
			
			$this->logger->log('Criteria: ' . $criteria);
			
			if ($target->destination == 'dbases') {
				$jsonObject['dbases'] = $ids;
			}
			else if ($target->destination == 'contactlists') {
				$jsonObject['contactlists'] = $ids;
			}
			else if ($target->destination == 'segments') {
				$jsonObject['segments'] = $ids;
			}
		}
		
		$jsonObject['filterByEmail'] = '';
		$jsonObject['filterByOpen'] = '';
		$jsonObject['filterByClick'] = '';
		$jsonObject['filterByExclude'] = '';
		
		if ($filter != null) {
			if ($type == 'email') {
				$jsonObject['filterByEmail'] = $filter->criteria;
			}
			else if ($type == 'open') {
				$jsonObject['filterByOpen'] = $criteria;
			}
			else if ($type == 'click') {
				$jsonObject['filterByClick'] = $criteria;
			}
			else if ($type == 'mailExclude') {
				$jsonObject['filterByExclude'] = $criteria;
			}
		}
		
		if ($this->mailcontent != null) {
			$jsonObject['mailcontent'] = (empty($this->mailcontent->content))?0:1;
		}
		else {
			$jsonObject['mailcontent'] = 0;
		}
		
		if (empty($this->mail->previewData)) {
			$preview = 'null';
		}
		else {
			$preview = $this->mail->previewData;
		}
		
		$jsonObject['previewData'] = $preview;
		$jsonObject['plainText'] = $this->mailcontent->plainText;
		$jsonObject['totalContacts'] = $this->mail->totalContacts;
		$jsonObject['scheduleDate'] = date('d/m/Y H:i', $this->mail->scheduleDate);
		
		$this->logger->log('Mail: ' . print_r($jsonObject, true));
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