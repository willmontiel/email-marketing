<?php

require_once "../app/library/swiftmailer/lib/swift_required.php";

class NotificationMail extends TestMail
{
	public $content;
	public $receiver = array('email' => '', 'name' => '');
	
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
		$this->receiver['email'] = $this->contact->email->email;
		$this->receiver['name'] = $this->contact->name;
	}

	public function setNotifyReceiver($email, $name)
	{
		$email = trim($email);
		if( !\filter_var($email, FILTER_VALIDATE_EMAIL) ) {
			throw new \Exception('Email Invalido Para Envio [To]');
		}
		$this->receiver['email'] = $email;
		$this->receiver['name'] = $name;
	}
	
	public function prepareContent($content)
	{
		$this->setBody($content);
		$this->createPlaintext();
		$this->replaceUrlImages();		
	}

	public function sendMail($sender)
	{
//		$transport = Swift_SmtpTransport::newInstance($this->mta->address, $this->mta->port);
		$transport = Swift_SendmailTransport::newInstance();
		$swift = Swift_Mailer::newInstance($transport);
		
		$subject = $sender->subject;
		
		$from_email = trim($sender->fromemail);
		if( !\filter_var($from_email, FILTER_VALIDATE_EMAIL) ) {
			throw new \Exception('Email Invalido Para Envio [From]');
		}
		
		$from = array($from_email => $sender->fromname);
		$to = array($this->receiver['email'] => $this->receiver['name']);
		$content = $this->getBody();
		$text = $this->getPlainText();
		$replyTo = $sender->reply;
		
		$message = new Swift_Message($subject);
		$message->setFrom($from);
		$message->setTo($to);
		$message->setBody($content, 'text/html');
		$message->addPart($text, 'text/plain');

		if ($replyTo != null) {
			$message->setReplyTo($replyTo);
		}
		
		$this->logger->log($content);
		
		$sendMail = $swift->send($message, $failures);
		
		if (!$sendMail){
			$this->logger->log("Error while sending mail: " . print_r($failures));
			throw new \Exception('Error while sending mail');
		}
		
		$this->logger->log('Correo Enviado');
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
	}
}
