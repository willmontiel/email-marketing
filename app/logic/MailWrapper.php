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
	
	public function setDbase(Dbase $dbase)
	{
		$this->dbase = $dbase;
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
	
	public function setSocialsKeys($fbiduser, $fbtoken, $twiduser, $twtoken)
	{
		$this->fbiduser = $fbiduser;
		$this->fbtoken = $fbtoken;
		$this->twiduser = $twiduser;
		$this->twtoken = $twtoken;
	}
	
	public function processDataForMail()
	{
		$this->validateTarget();
		$this->processScheduleDate();
		$this->saveContent();
	}
	
	public function validateTarget()
	{
		$target = $this->content->target;
		if (!empty($target)) {
			$this->target = new stdClass();
			$this->target->target = $target;
		}
		
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
			$target->setAccount($this->account);
			$target->setIdsDbase($idsDbase);
			$target->setIdsContactlist($idsContactlist);
			$target->setIdsSegment($idsSegment);
			$target->setFilters($byEmail, $byOpen, $byClick, $byExclude);
			$target->createTargetObj();
			$this->target = $target->getTargetObject();

//			$this->logger->log("Target: " . print_r($this->target, true));
		}
	}

	protected function processScheduleDate()
	{
		$schedule = $this->content->scheduleDate;
		if(!empty($schedule)) {
			if ($schedule == 'now') {
				$this->scheduleDate = time();
			}
			else if ($schedule !== '' || !empty($schedule)) {
				list($day, $month, $year, $hour, $minute) = preg_split('/[\s\/|-|:]+/', $schedule);
				$this->scheduleDate = mktime($hour, $minute, 0, $month, $day, $year);
			}
		}
	}
	
	protected function processSocialPosts()
	{
		if($this->fbaccounts || $this->twaccounts) {
			$socialmail = Socialmail::findFirstByIdMail($this->mail->idMail);
			if(!$socialmail) {
				$socialmail = new Socialmail();
				$socialmail->idMail = $this->mail->idMail;
			}
			
			$socialnet = new SocialNetworkConnection();
			if($this->fbaccounts) {
				$socialmail->fbdescription = $socialnet->saveFacebookDescription($this->content->fbtitlecontent, $this->content->fbdescriptioncontent, $this->content->fbmessagecontent, $this->content->fbimagepublication);
			}
			if($this->twaccounts) {
				$socialmail->twdescription = $socialnet->saveTwitterDescription($this->content->twpublicationcontent);
			}
			
			if (!$socialmail->save()) {
				$e = array();
				foreach ($socialmail->getMessages() as $msg) {
					$e[] = $msg;
				}
				$messages = implode(", ", $e);

				$this->addMessageError('errors', $messages, 400);
				throw new \InvalidArgumentException($messages);
			}
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
		$this->mail->totalContacts = (isset($this->content->totalContacts) ? $this->content->totalContacts : 0);
		if ($this->scheduleDate != null) {
			$this->mail->scheduleDate = $this->scheduleDate;
		}
		$this->mail->createdon = $date;
		$this->mail->updatedon = $date;
		$this->mail->deleted = 0;
		$this->mail->name = $this->content->name;
		$this->mail->subject = $this->content->subject;
		
		$sender = $this->getSender();
		$this->mail->fromName = $sender->name;
		$this->mail->fromEmail = $sender->email;
		
		$this->mail->replyTo = $this->content->replyTo;
		$this->mail->target = $this->target->target;
		
		$this->fbaccounts = (!empty($this->content->fbaccounts)) ? explode(',', $this->content->fbaccounts) : null;
		$this->twaccounts = (!empty($this->content->twaccounts)) ? explode(',', $this->content->twaccounts) : null;
		if($this->fbaccounts || $this->twaccounts) {
			$socialnet = new SocialNetworkConnection();
			$this->mail->socialnetworks = $socialnet->saveSocialsIds($this->fbaccounts, $this->twaccounts);
		}
		
		if (!$this->mail->save()) {
			$e = array();
			foreach ($this->mail->getMessages() as $msg) {
				$e[] = $msg;
			}
			$messages = implode(", ", $e);
			
			$this->addMessageError('errors', $messages, 400);
			throw new \InvalidArgumentException($messages);
		}
		
		$this->processSocialPosts();
		
		if ($this->scheduleDate != null) {
			$mailSchedule = new MailScheduleObj($this->mail);
			$scheduled = $mailSchedule->scheduleTask();
			
			if (!$scheduled) {
				$this->addMessageError('errors', 'Ha ocurrido un error por favor contacte al administrador', 500);
				$this->logger->log("Error while saving mail {$this->mail->idMail} scheduleDate in Mailschedule table account {$this->mail->idAccount}");
				throw new \Exception('Error while saving mail scheduleDate in Mailschedule table');
			}
		}
		
		return $this->mail;
	}
	
	protected function isAValidDomain($domain)
	{
		$invalidDomains = array(
			'yahoo',
			'hotmail',
			'live',
			'gmail',
			'aol'
		);
		
		$d = explode('.', $domain);
		
		foreach ($invalidDomains as $invalidDomain) {
			if ($invalidDomain == $d[0]) {
				return false;
			}
		}
		return true;
	}
	
	public function getSender()
	{
		$parts = explode('/', $this->content->sender);
		$email = trim(strtolower($parts[0]));
		$domain = explode('@', $email);
		$name = $parts[1];
		
		$sender = new stdClass();
		$sender->name = $name;
		$sender->email = $email;
		
		$s = $this->searchSender($sender);
		
		if (!$s) {
			if (!\filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$this->addMessageError('errors', 'Ha enviado una dirección de correo de remitente inválida, por favor verifique la información', 422);
				throw new InvalidArgumentException("Invalid sender email");
			}

			if (!$this->isAValidDomain($domain[1])) {
				$this->addMessageError('errors', 'Ha enviado una dirección de correo de remitente invalida, recuerde que no debe usar dominios de correo públicas como hotmail o gmail', 422);
				throw new InvalidArgumentException("Invalid sender domain");
			}

			if (empty($name)) {
				$this->addMessageError('errors', 'No ha enviado un nombre de remitente válido, por favor verifique la información', 422);
				throw new InvalidArgumentException("Invalid sender name");
			}
			
			$this->saveSender($sender);
		}
		
		return $sender;
	}
	
	private function searchSender($sender) 
	{
		$findSender = Sender::findFirst(array(
			'conditions' => 'idAccount = ?1 AND email = ?2',
			'bind' => array(1 => $this->account->idAccount,
							2 => $sender->email)
		));
		
		return $findSender;
	}
	
	private function saveSender($sender)
	{
		$newsender = new Sender();
		$newsender->idAccount = $this->account->idAccount;
		$newsender->email = $sender->email;
		$newsender->name = $sender->name;
		$newsender->createdon = time();

		if (!$newsender->save()) {
			foreach ($newsender->getMessages() as $msg) {
				$this->flashSession->error($msg);
			}
			throw new Exception("Error while saving account remittent");
		}
	}
	
	private function saveContent()
	{
		if ($this->mailcontent != null) {
			if (!empty($this->content->googleAnalytics)) {
				$campaignName = $this->content->campaignName;
				$googleAnalytics = explode(',', $this->content->googleAnalytics);
				
				if (empty($campaignName)) {
					$campaignName = substr($this->mail->name, 0, 24);
				}
				else if (strlen($campaignName) > 25) {
					$campaignName = substr($campaignName, 0, 24);
				}
				
				$this->mailcontent->campaignName = $campaignName;
				$this->mailcontent->googleAnalytics = json_encode($googleAnalytics);
			}
			else {
				$this->mailcontent->campaignName = null;
				$this->mailcontent->googleAnalytics = null;
			}
			
			$this->mailcontent->plainText = $this->content->plainText;
			
			
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
		ini_set('auto_detect_line_endings', '1');
		
		$this->mail; $this->content;
		$jsonObject = array();
		
		$jsonObject['id'] = $this->mail->idMail;      
		$jsonObject['name'] = $this->mail->name;
		$jsonObject['subject'] = $this->mail->subject;
		
		$jsonObject['target'] = '';
		if (!empty($this->mail->target)) {
			$jsonObject['target'] = $this->mail->target;
		}
		
		$jsonObject['sender'] = "{$this->mail->fromEmail}/{$this->mail->fromName}";
		$jsonObject['replyTo'] = $this->mail->replyTo;
		$jsonObject['type'] = $this->mail->type;
		$jsonObject['fbaccounts'] = '';
		$jsonObject['twaccounts'] = '';
		$jsonObject['fbmessagecontent'] = '';
		$jsonObject['fbimagepublication'] = 'default';
		$jsonObject['fbtitlecontent'] = '';
		$jsonObject['fbdescriptioncontent'] = '';
		$jsonObject['twpublicationcontent'] = '';
		
		if( !empty($this->mail->socialnetworks) ) {
			$socials = json_decode($this->mail->socialnetworks);
			$jsonObject['fbaccounts'] = (isset($socials->facebook)) ? implode(',', $socials->facebook) : '';
			$jsonObject['twaccounts'] = (isset($socials->twitter)) ? implode(',', $socials->twitter) : '';
			
			$socialmail = Socialmail::findFirstByIdMail($this->mail->idMail);
			$fbdesc = ($socialmail && !empty($socialmail->fbdescription)) ? json_decode($socialmail->fbdescription) : '';
			$twdesc = ($socialmail && !empty($socialmail->twdescription)) ? json_decode($socialmail->twdescription) : '';
			
			$jsonObject['fbmessagecontent'] = ($fbdesc != '') ? $fbdesc->message : '';
			$jsonObject['fbimagepublication'] = ($fbdesc != '') ? $fbdesc->image : '';
			$jsonObject['fbtitlecontent'] = ($fbdesc != '') ? $fbdesc->title : '';
			$jsonObject['fbdescriptioncontent'] = ($fbdesc != '') ? $fbdesc->description : '';
					
			$jsonObject['twpublicationcontent'] = ($twdesc != '') ? $twdesc->message : '';
		}
		
//		$filter = null;
//		if ($this->mail->target != null) {
//			$target = json_decode($this->mail->target);
//			$filter = $target->filter;
//			
//			if(!empty($filter)) {
//				$type = $filter->type;
//				$criteria = $filter->criteria;
//			}
//			
//			$ids = implode(',', $target->ids);
//			
//			
//			if ($target->destination == 'dbases') {
//				$jsonObject['dbases'] = $ids;
//			}
//			else if ($target->destination == 'contactlists') {
//				$jsonObject['contactlists'] = $ids;
//			}
//			else if ($target->destination == 'segments') {
//				$jsonObject['segments'] = $ids;
//			}
//		}
		
//		$jsonObject['filterByEmail'] = '';
//		$jsonObject['filterByOpen'] = '';
//		$jsonObject['filterByClick'] = '';
//		$jsonObject['filterByExclude'] = '';
//		
//		if ($filter != null) {
//			if ($type == 'email') {
//				$jsonObject['filterByEmail'] = $filter->criteria;
//			}
//			else if ($type == 'open') {
//				$jsonObject['filterByOpen'] = $criteria;
//			}
//			else if ($type == 'click') {
//				$jsonObject['filterByClick'] = $criteria;
//			}
//			else if ($type == 'mailExclude') {
//				$jsonObject['filterByExclude'] = $criteria;
//			}
//		}
		
		
		$jsonObject['mailcontent'] = 0;
		$jsonObject['campaignName'] = '';
		$jsonObject['googleAnalytics'] = '';
		
		if ($this->mailcontent != null) {
			$jsonObject['mailcontent'] = (empty($this->mailcontent->content))?0:1;
			
			$campaignName = $this->mailcontent->campaignName;
			if ($campaignName != null) {
				$jsonObject['campaignName'] = $campaignName;
			}
			
			if ($this->mailcontent->googleAnalytics != null) {
				$googleAnalytics = json_decode($this->mailcontent->googleAnalytics);
				$jsonObject['googleAnalytics'] = implode(',', $googleAnalytics);
			}
		}
		
		if (empty($this->mail->previewData)) {
			$preview = 'null';
		}
		else {
			$preview = $this->mail->previewData;
		}
		
		$jsonObject['previewData'] = $preview;
		
//		if ($this->mail->type == 'Editor') {
//			$plainText = htmlentities($this->mailcontent->plainText);
//		}
//		else {
//			$plainText = $this->mailcontent->plainText;
//		}
		
//		$plainText = (empty($this->mailcontent->plainText) ? '' : utf8_encode($this->mailcontent->plainText));
		$plainText = $this->mailcontent->plainText;
		
		if (!mb_check_encoding($plainText, 'UTF-8')) {
			if (mb_check_encoding($plainText, 'ISO-8859-1')) {
				$plainText = mb_convert_encoding($plainText, 'UTF-8', 'ISO-8859-1');
			}
			else {
				throw new \Exception('Codificacion invalida en texto');
			}
		}
		
		$jsonObject['plainText'] = $plainText;
		$jsonObject['totalContacts'] = $this->mail->totalContacts;
		
		if ($this->mail->scheduleDate != null || $this->mail->scheduleDate != 0)  {
			$schedule = date('d/m/Y H:i', $this->mail->scheduleDate);
		}
		else {
			$schedule = null;
		}
		
		$jsonObject['scheduleDate'] = $schedule;
		
		$socialnet = new SocialNetworkConnection();
		$socialnet->setAccount($this->account);
		$socialnet->setFacebookConnection($this->fbiduser, $this->fbtoken);
		$socialnet->setTwitterConnection($this->twiduser, $this->twtoken);
		
		$redirect = '/socialmedia/create/' . $this->mail->idMail;
		$jsonObject['fbloginurl'] = $socialnet->getFbUrlLogIn($redirect);
		$jsonObject['twloginurl'] = $socialnet->getTwUrlLogIn($redirect);
		
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
