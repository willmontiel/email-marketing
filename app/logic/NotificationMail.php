<?php

require_once "../app/library/swiftmailer/lib/swift_required.php";

class NotificationMail extends TestMail
{
	public $content;
	public $receiver = array();
	
	function __construct()
	{
		$this->logger = Phalcon\DI::getDefault()->get('logger');
		$this->urlManager = Phalcon\DI::getDefault()->get('urlManager');
	}
	
	public function setForm(Form $form)
	{
		$this->form = $form;
	}
	
	public function setContact(Contact $contact)
	{
		$this->contact = $contact;
	}

	public function setContactReceiver()
	{
		$this->receiver[$this->contact->email->email] = $this->contact->name;
	}
	
	public function setSubject($subject)
	{
		$this->subject = $subject;
	}

	public function setNotifyReceiver($email_array, $name_array)
	{
		$emails = explode(',', $email_array);
		$names = explode(',', $name_array);
		
		foreach ($emails as $key => $email)
		{
			if( \filter_var($email, FILTER_VALIDATE_EMAIL) ) {
				$this->receiver[$email] = $names[$key];
			}
		}
		
		if(empty($this->receiver)){
			throw new \Exception('Email Invalido Para Envio [To]');
		}
	}
	
	public function prepareContent($content)
	{
		$this->setBody($content);
		$this->createPlaintext();
		$this->replaceCustomFieldsValues();
		$this->replaceUrlImages(false);		
	}

	public function sendMail($sender)
	{
//		$transport = Swift_SmtpTransport::newInstance($this->mta->address, $this->mta->port);
		$transport = Swift_SendmailTransport::newInstance();
		$swift = Swift_Mailer::newInstance($transport);
		
//		$this->subject = $sender->subject;
		
		$from_email = trim($sender->fromemail);
		if( !\filter_var($from_email, FILTER_VALIDATE_EMAIL) ) {
			throw new \Exception('Email Invalido Para Envio [From]');
		}
		
		$from = array($from_email => $sender->fromname);
		$to = array($this->receiver['email'] => $this->receiver['name']);
		$content = $this->getBody();
		$text = $this->getPlainText();
		$replyTo = $sender->reply;

		$message = new Swift_Message($this->subject);
		$message->setFrom($from);
		$message->setBody($content, 'text/html');
		$message->addPart($text, 'text/plain');

		if ($replyTo != null) {
			$message->setReplyTo($replyTo);
		}
		
		foreach ($this->receiver as $email => $name){
			$to = array($email => $name);
			$this->logger->log('Mail send to: ' . print_r($to, true));
			$message->setTo($to);
			$sendMail = $swift->send($message, $failures);
			if (!$sendMail){
				$this->logger->log("Error while sending mail: " . print_r($failures));
			}
		}
		
		$this->logger->log('Correo Enviado');
	}
	
	protected function replaceCustomFieldsValues()
	{
		preg_match_all('/%%([a-zA-Z0-9_\-]*)%%/', $this->plainText, $textFields);
		preg_match_all('/%%([a-zA-Z0-9_\-]*)%%/', $this->body, $htmlFields);

		$result_preg = array_merge($textFields[0], $htmlFields[0]);	
		
		$search_fields = array_unique($result_preg);
		
		$searchCustomFields = array();
		$replaceCustomFields = array();
		
		$searchPrimaryFields = array('%%EMAIL%%', '%%NOMBRE%%', '%%APELLIDO%%', '%%FECHA_DE_NACIMIENTO%%');
		$replacePrimaryFields = array($this->contact->email->email, $this->contact->name, $this->contact->lastName, $this->contact->birthDate);
		
		$phql = "	SELECT cf.name, fi.textValue, fi.numberValue, cf.type 
							FROM Customfield AS cf 
								JOIN Fieldinstance AS fi ON (cf.idCustomField = fi.idCustomField) 
							WHERE fi.idContact = {$this->contact->idContact}	";
							
		$modelsManager = Phalcon\DI::getDefault()->get('modelsManager');
		$customfield_query = $modelsManager->executeQuery($phql);
		$customfields = array();
		
		$accents =  array('Ñ', 'ñ', 'Á', 'á', 'É', 'é', 'Í', 'í', 'Ó', 'ó', 'Ú', 'ú');
		$woaccents = array('N', 'n', 'A', 'a', 'E', 'e', 'I', 'i', 'O', 'o', 'U', 'u');
		
		foreach ($customfield_query as $cf){
			$cf_name = strtoupper(str_replace($accents, $woaccents, $cf->name));
			$customfields[$cf_name] = ($cf->type == 'Date') ? date('d/m/Y',$cf->numberValue) : $cf->textValue;
		}
		foreach ($search_fields as $s_f) {
			$field_html = str_replace(array('_', '%%'), array(' ', ''), $s_f);
			if(isset($customfields[$field_html])){ 
				$searchCustomFields[] = $s_f;
				$replaceCustomFields[] = $customfields[$field_html];
			}
		}
		
		$search = array_merge($searchPrimaryFields, $searchCustomFields);
		$replace = array_merge($replacePrimaryFields, $replaceCustomFields);
		
		$this->body = str_replace($search, $replace, $this->body);
		$this->plainText = str_replace($search, $replace, $this->plainText);
		$this->subject = str_replace($search, $replace, $this->subject);
	}
	
	protected function setBody($contentobj)
	{
		$editorObj = new HtmlObj();
		$editorObj->setAccount($this->account);
		$editorObj->assignContent(json_decode($contentobj));
		$this->body = utf8_decode($editorObj->replacespecialchars($editorObj->render()));
	}
	
	public function setNotificationLink()
	{
		$linkdecoder = new \EmailMarketing\General\Links\ParametersEncoder();
		$linkdecoder->setBaseUri($this->urlManager->getBaseUri(true));
		
		$action = 'contacts/activate';
		$parameters = array(1, $this->contact->idContact, $this->form->idForm);
		$link = $linkdecoder->encodeLink($action, $parameters);
		
		$this->body = str_replace('%%CONFIRMLINK%%', $link, $this->body);
		$this->plainText = str_replace('%%CONFIRMLINK%%', $link, $this->plainText);
	}
}
