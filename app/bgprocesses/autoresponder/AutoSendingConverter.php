<?php

class AutoSendingConverter
{
	function __construct() {
		$this->db = Phalcon\DI::getDefault()->get('db');
		$this->logger = Phalcon\DI::getDefault()->get('logger');
	}

	public function setAutoresponder(Autoresponder $autoresponder)
	{
		$this->autoresponder = $autoresponder;
	}
	
	public function setAccount($account)
	{
		$this->account = $account;
	}

	public function getMail()
	{
		$MailWrapper = new MailWrapper();
		$MailWrapper->setMail($this->mail);
		$MailWrapper->setContent($this->createContentForMail());
		$MailWrapper->setAccount($this->account);
		$MailWrapper->processDataForMail();
		$mail = $MailWrapper->saveMail();
		$this->createMxA($mail);
		return $mail;
	}
	
	public function convertToMail()
	{
		if($this->autoresponder->contentsource == 'url') {
			$this->db->begin();
			$this->mail = new Mail();
			$this->mail->idAccount = $this->account->idAccount;
			$this->mail->type = 'Html';
			$this->mail->status = 'Draft';
			$this->mail->wizardOption = 'setup';
			$this->mail->deleted = 0;
			$this->mail->previewData = $this->autoresponder->previewData;
			
			if(!$this->mail->save()) {
				foreach ($this->mail->getMessages() as $msg) {
					$this->logger->log("Error while saving mail {$msg}");
				}
				$this->db->rollback();
				throw new Exception('Error while saving content mail as autoresponder');
			}
			
			$this->saveContentFromURL();
			$this->db->commit();
		}
	}
	
	protected function saveContentFromURL()
	{
		$objJson = json_decode($this->autoresponder->content);
		$url = $objJson->url;

		if(!filter_var($url, FILTER_VALIDATE_URL)) {
			$this->db->rollback();
			throw new Exception('Wrong URL');
		}

		$getHtml = new LoadHtml();
		$content = $getHtml->gethtml($url, false, false, $this->account, true);

		$html = $this->changeTDStyles($content);
		
		$mc = new Mailcontent();
		$mc->idMail = $this->mail->idMail;

		$text = new PlainText();
		$plainText = $text->getPlainText($html);

		$mc->content = htmlspecialchars($html, ENT_QUOTES);
		$mc->plainText = $plainText;

		if(!$mc->save()) {
			foreach ($mc->getMessages() as $msg) {
				$this->logger->log("Error while saving mail html content {$msg}");
			}
			$this->db->rollback();
			throw new Exception('Error while saving content mail as autoresponder');
		}
	}
	
	protected function createContentForMail()
	{
		$obj = new stdClass();
		
		$obj->scheduleDate = 'now';
		$obj->type = 'Html';
		$obj->name = $this->autoresponder->name . ' ' . date('d/m/Y', time());
		
		$from = json_decode($this->autoresponder->from);
		$obj->sender = $from->email . '/' . $from->name;
		
		$obj->replyTo = $this->autoresponder->reply;
		
		$subject = json_decode($this->autoresponder->subject);
		
		if($subject->mode == 'dynamic') {
			$obj->subject = $this->getSubject($subject);
		}
		else {
			$obj->subject = $subject->text;
		}
		
		$obj->dbases = '';
		$obj->contactlists = '';
		$obj->segments = '';
		
		$obj->filterByEmail = '';
		$obj->filterByOpen = '';
		$obj->filterByClick = '';
		$obj->filterByExclude = '';
		
		$target = json_decode($this->autoresponder->target);
		$destination = $target->destination;
		$obj->$destination = $target->ids;
		
		return $obj;
	}
	
	protected function createMxA(Mail $mail)
	{
		$mxa = new Mxa();
		$mxa->idAutoresponder = $this->autoresponder->idAutoresponder;
		$mxa->idMail = $mail->idMail;
		
		if(!$mxa->save()) {
			foreach ($mxa->getMessages() as $msg) {
				$this->logger->log("Error while saving MxA {$msg}");
			}
			$this->db->rollback();
			throw new Exception('Error while saving MxA');
		}
	}
	
	protected function getSubject($option)
	{
		$url = json_decode($this->autoresponder->content);
		$subject = '';
		switch (strtolower($option->text)) {
			case 'meta tag':
				$tags = get_meta_tags($url->url);
				$subject = ( isset($tags[$option->tag]) ) ? $tags[$option->tag] : '';
				break;
		}
		return $subject;
	}
	
	protected function changeTDStyles($html)
	{
		$htmlObj = new DOMDocument();
		@$htmlObj->loadHTML($html);

		$tds = $htmlObj->getElementsByTagName('td');

		if ($tds->length !== 0) {
			foreach ($tds as $td) {
				$td_style = $td->getAttribute('style');
				if ($td_style) {
					$styles = explode(";", $td_style);
					foreach($styles as $key => $style) {
						if(strpos($style,'font-family') !== false) {
							unset($styles[$key]);
							$style = str_replace("'","",$style);
							array_push($styles, $style);
						}
						else if(trim($style) === '') {
							unset($styles[$key]);
						}
					}
					$full_style = implode(';', $styles);
					$td->setAttribute('style', $full_style);
				}
			}
		}
		
		return $htmlObj->saveHTML();
	}
}

