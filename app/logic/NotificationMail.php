<?php

require_once "../app/library/swiftmailer/lib/swift_required.php";

class NotificationMail extends TestMail
{
	public $content;
	public $receiver = array('email' => '', 'name' => '');
	
	function __construct()
	{
		$this->logger = Phalcon\DI::getDefault()->get('logger');
		$this->urlObj = Phalcon\DI::getDefault()->get('urlManager');
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
		$this->receiver['email'] = $email;
		$this->receiver['name'] = $name;
	}
	
	public function prepareContent($content)
	{
		$this->setBody($content);
		$this->createPlaintext();
	}

	public function sendMail($sender)
	{
//		$transport = Swift_SmtpTransport::newInstance($this->mta->address, $this->mta->port);
		$transport = Swift_SendmailTransport::newInstance();
		$swift = Swift_Mailer::newInstance($transport);
		
		$subject = $sender->subject;
		$from = array($sender->fromemail => $sender->fromname);
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
	}
	
	protected function setBody($contentobj)
	{
		$editorObj = new HtmlObj();
		$editorObj->assignContent(json_decode($contentobj));
		$content = utf8_decode($editorObj->replacespecialchars($editorObj->render()));
		$this->body = $content;
	}
	
	public function setNotificationLink()
	{
		$linkdecoder = new \EmailMarketing\General\Links\ParametersEncoder();
		$linkdecoder->setBaseUri($this->urlObj->getBaseUri(true));
		
		$action = 'contacts/activate';
		$parameters = array(1, $this->contact->idContact, $this->form->idForm);
		$link = $linkdecoder->encodeLink($action, $parameters);
		
		$this->body = str_replace('%%CONFIRMLINK%%', $link, $this->body);
	}
}
