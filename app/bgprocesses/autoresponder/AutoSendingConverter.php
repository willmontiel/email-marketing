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
		
		if($this->autoresponder->type == 'birthday') {
			$birthday_wrapper = new BirthdayAutoResponder();
			$birthday_wrapper->setMail($this->mail);
			$birthday_wrapper->setAutoresponder($this->autoresponder);
			$birthday_wrapper->selectTarget();
		}
		
		return $mail;
	}
	
	public function convertToMail()
	{
		$this->db->begin();
		
		$this->checkTotalContacts();
		
		$this->mail = new Mail();
		$this->mail->idAccount = $this->account->idAccount;
		$this->mail->wizardOption = 'setup';
		$this->mail->deleted = 0;
		$this->mail->previewData = $this->autoresponder->previewData;
		
		if($this->autoresponder->type == 'birthday') {
			$this->selectMailType($this->autoresponder->contentsource);
			$this->mail->status = 'Birthday';
		}
		else {
			$this->selectMailType($this->autoresponder->contentsource);
			$this->mail->status = 'Draft';
		}
			
		if(!$this->mail->save()) {
			foreach ($this->mail->getMessages() as $msg) {
				$this->logger->log("Error while saving mail {$msg}");
			}
			$this->db->rollback();
			throw new Exception('Error while saving content mail as autoresponder');
		}

		if($this->autoresponder->contentsource) {
			switch($this->autoresponder->contentsource) {
				case 'url':
					$this->saveContentFromURL();
					break;
				case 'html':
					$this->saveContentFromHTML();
					break;
				case 'editor':
					$this->saveContentFromEDITOR();
					break;
			}
		}
		else {
			$this->db->rollback();
			$this->logger->log('La autorespuesta ' . $this->autoresponder->name . ' no tiene contenido');
			throw new Exception('No hay contenido');
		}

		$this->db->commit();
		
	}
	
	protected function selectMailType($contentsource)
	{
		switch($contentsource) {
			case 'url':
			case 'html':
				$this->mail->type = 'Html';
				break;
			case 'editor':
				$this->mail->type = 'Editor';
				break;
		}
	}

	protected function checkTotalContacts()
	{
		$wrapper = new \EmailMarketing\General\Misc\InterpreterTarget();
		$total = 0;
		
		$wrapper->setData(json_decode($this->autoresponder->target));
		$wrapper->searchTotalContacts();
		$sql = $wrapper->getSQL();

		if($this->autoresponder->type == 'birthday') {
			$sql.= ' AND DATE_FORMAT(c.birthDate, \'%m-%d\') = DATE_FORMAT(NOW(), \'%m-%d\')';
			$this->logger->log('SQL FINAL ' . $sql);
		}
				
		if ($sql != false) {
			$executer = new \EmailMarketing\General\Misc\SQLExecuter();
			$executer->setSQL($sql);
			$executer->instanceDbAbstractLayer();
			$executer->queryAbstractLayer();
			$r = $executer->getResult();
			$total = $r[0]['total'];
		}
		
		if($total == 0) {
			$this->db->rollback();
			$this->logger->log('No hay destinatarios');
			throw new Exception(400);
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
		try {
			$getHtml = new LoadHtml();
			$content = $getHtml->gethtml($url, false, false, $this->account, true);
		}
		catch(Exception $e) {
			$this->db->rollback();
			$this->logger->log($e->getMessage());
			throw new Exception(500);
		}
		
		try {
			$content_processed = $this->addMetaAccent($content);
			$html = $this->changeTDStyles($content_processed);
			$this->saveContent($html);
		}
		catch(Exception $e) {
			$this->db->rollback();
			$this->logger->log($e->getMessage());
			throw new Exception(400);
		}
	}
	
	protected function saveContentFromHTML()
	{
		try {
			$content = html_entity_decode($this->autoresponder->content);
			$content_processed = $this->addMetaAccent($content);
			$html = $this->changeTDStyles($content_processed);
			$this->saveContent($html);
		}
		catch(Exception $e) {
			$this->db->rollback();
			$this->logger->log($e->getMessage());
			throw new Exception(400);
		}
	}
	
	protected function saveContentFromEDITOR()
	{
		try {
			$this->saveContent($this->autoresponder->content, true);
		}
		catch(Exception $e) {
			$this->db->rollback();
			$this->logger->log($e->getMessage());
			throw new Exception(400);
		}
	}
	
	protected function saveContent($html, $editor = false)
	{
		try {
			$mc = new Mailcontent();
			$mc->idMail = $this->mail->idMail;

			if($editor) {
				$mc->content = $html;
				
				$editorObj = new HtmlObj();
				$editorObj->setAccount($this->account);
				$editorObj->assignContent(json_decode($html));
				$html = $editorObj->render();
			}
			else {
				$mc->content = htmlspecialchars($html, ENT_QUOTES);
			}
			
			$text = new PlainText();
			$plainText = $text->getPlainText($html);

			$mc->plainText = $plainText;
		}
		catch(Exception $e) {
			$this->db->rollback();
			$this->logger->log($e->getMessage());
			throw new Exception(400);
		}
		
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
		$obj->type = $this->mail->type;			
		$obj->name = $this->autoresponder->name . ' ' . date('d/m/Y', time());
		
		$from = json_decode($this->autoresponder->from);
		$obj->sender = $from->email . '/' . $from->name;
		
		$obj->replyTo = $this->autoresponder->reply;
		
		$subject = json_decode($this->autoresponder->subject);
		
		if($subject->mode == 'dynamic') {
			$obj->subject = html_entity_decode($this->getSubject($subject), ENT_QUOTES);
		}
		else {
			$obj->subject = $subject->text;
		}
		
		$obj->target = $this->autoresponder->target;
		
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
	
	protected function addMetaAccent($html)
	{
		$search = array("</head>");
		$replace = array('<meta name="accent" content="Aquí va una tilde"></head>');
		$final_html= str_replace($search, $replace, $html);
		
		return $final_html;
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
		
		$metas = $htmlObj->getElementsByTagName('meta');
		
		if ($metas->length !== 0) {
			foreach ($metas as $meta) {
				$content = $meta->getAttribute('content');
				$search = array("\xe2\x80\x9c", "\xe2\x80\x9d", "\xe2\x80\x9f", '"', "“", "”");
				$replace = array("&quot;", "&quot;", "&quot;", "&quot;", "&quot;", "&quot;");
				$final_tag= str_replace($search, $replace, $content);
				$meta->setAttribute('content', $final_tag);
			}
		}
		
		return $htmlObj->saveHTML();
	}
}

